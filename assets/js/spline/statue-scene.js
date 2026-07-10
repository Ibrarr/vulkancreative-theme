// The interactive marble statue scene. three.js and its addons are statically
// imported here so webpack bundles them into ONE lazy chunk (three included
// once), and statue-hero.js pulls this module in with a single dynamic import.
//
// Head does a look-at (the gaze lands on the cursor) and leads fast; the torso
// leans slowly and the upper spine swings both arms plus the locked hammer. The
// head subtracts the spine motion so it stays on target. The hammer is locked to
// the right hand and both arms solve with IK so the two-hand grip never breaks.
// Look and motion are identical to the approved standalone preview; only the
// background is transparent here so the hero glow shows through, and it sizes to
// the .graphic box rather than the window.
import * as THREE from 'three';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';
import { RoomEnvironment } from 'three/examples/jsm/environments/RoomEnvironment.js';

export function buildScene(container, canvas, modelUrl, onFail) {
    const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.setClearColor(0x000000, 0); // transparent: the molten glow shows through
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 0.55; // lower than before so the marble reads as natural stone, not blown-out white

    const scene = new THREE.Scene(); // no background -> the canvas is transparent
    const pmrem = new THREE.PMREMGenerator(renderer);
    scene.environment = pmrem.fromScene(new RoomEnvironment(), 0.02).texture; // soft studio IBL = even fill + marble sheen
    try { scene.environmentIntensity = 2.7; } catch (e) { /* older three */ }

    const cam = new THREE.PerspectiveCamera(35, 1, 0.01, 500); // FIXED camera, never moves after framing
    const camTarget = new THREE.Vector3();

    const amb = new THREE.AmbientLight(0xffffff, 0.82); scene.add(amb); // strong even base so the baked-in occlusion crevices lift to grey, never black
    const key = new THREE.DirectionalLight(0xffffff, 0.28); key.position.set(0.6, 4.2, 3.6); scene.add(key); // gentle light from ABOVE + IN FRONT; kept low so shadows stay soft
    const fill = new THREE.DirectionalLight(0xffffff, 0.50); fill.position.set(-3.5, 1.3, 3); scene.add(fill); // side fill to open the shadowed side
    const rim = new THREE.DirectionalLight(0xffffff, 0.32); rim.position.set(2, 3, -4.5); scene.add(rim); // subtle back edge

    const cfg = {
        headEase: 0.035, bodyEase: 0.016, armEase: 0.02, idleAfter: 2600, idleYaw: 0.15, idlePitch: 0.06, // very low eases = strong lag/drag behind the cursor (lower headEase = more lag)
        yawSign: 1, pitchSign: 1,
        lookYaw: 0.62, lookPitch: 0.52, pitchBias: 0.06, pitchMax: 0.85, // head aims at the cursor RELATIVE to the head's on-screen spot; bias is a small residual only
        neckShare: 0.4,
        frameTopPad: 0.40, frameBotPad: 0.06, // camera framing: headroom above the head (clears the top menu); keep the bottom tight so no extra lower body shows
        hammerReach: 0.5, frameLeftPad: 0.24, frameRightPad: 0.45, frameShiftX: -0.12, // horizontal window (incl. the whole hammer); negative shift nudges the figure toward the right edge, hammer still reaching the headline
        body: { Spine1: [0.05, 0.020], Spine2: [0.045, 0.018] }, // slow full-torso lean
        arms: { Spine3: [0.035, 0.018] }, // subtle upper-spine torso follow
        introDur: 2600, introYaw: 0.55, // on load the whole upper body is turned to our right, untwisting to forward
        introLookYaw: 0.45, // head turns even further to our right than the body, then leads back to forward
        hammerFollow: [0.012, 0.013, 0.0], // small hammer follow -> keeps the wrist near its sculpted angle so the grip never clips
        hammerTurn: 0.0 // keep the hammer exactly as posed in the .blend
    };
    window.__H = { cfg, reframe: () => { computeFrame(); applyFrame(); }, renderer, scene, lights: { amb, key, fill, rim } }; // reframe() re-applies framing; renderer/lights exposed for live look tuning

    let BONES = {}, base = {}, ready = false, revealed = false, frameData = null, model = null, hammerRig = null, leftGrip = null, introStart = -1;
    let restHeadWorld = null, headRefX = 0, headRefY = 0; // the head's rest on-screen spot (NDC), so the look-at aims from where the head actually sits
    const SPINES = ['Spine1', 'Spine2', 'Spine3', 'Neck', 'Head'];
    const ARMS = ['UpperArmR', 'LowerArmR', 'HandR', 'UpperArmL', 'LowerArmL', 'HandL'];

    function computeFrame() { // frame from BONES (skinned bbox is rest-pose = wrong); include the whole hammer horizontally
        const head = new THREE.Vector3(), pelvis = new THREE.Vector3(), hR = new THREE.Vector3(), hL = new THREE.Vector3();
        BONES.Head.getWorldPosition(head); BONES.Pelvis.getWorldPosition(pelvis);
        BONES.HandR.getWorldPosition(hR); BONES.HandL.getWorldPosition(hL);
        const hammerHeadX = hR.x + (hR.x - hL.x) * cfg.hammerReach; // the mallet head sits beyond the top grip, up the handle
        const leftX = hammerHeadX - cfg.frameLeftPad;   // our-left extent (the hammer)
        const rightX = head.x + cfg.frameRightPad;      // right shoulder/arm extent
        frameData = { cx: (leftX + rightX) / 2 + cfg.frameShiftX, cz: head.z, halfW: (rightX - leftX) / 2, topY: head.y + cfg.frameTopPad, botY: pelvis.y - cfg.frameBotPad };
    }
    function applyFrame() { // fixed, front-on. Fits the head-to-waist height AND the full hammer width; the waist stays the bottom edge.
        if (!frameData) return;
        const { cx, cz, halfW, topY, botY } = frameData;
        const spanY = topY - botY;
        const w = container.clientWidth || 1, h = container.clientHeight || 1;
        const vfov = THREE.MathUtils.degToRad(35), tanV = Math.tan(vfov / 2);
        const distH = (spanY / 2) / tanV;            // fit the head-to-waist height
        const distW = halfW / (tanV * (w / h));      // fit the full width incl. the hammer
        const dist = Math.max(distH, distW);
        const centerY = botY + dist * tanV;          // anchor the BOTTOM at the waist; any extra room goes to headroom, never more lower body
        cam.position.set(cx, centerY, cz + dist);
        camTarget.set(cx, centerY, cz);
        cam.near = dist / 50; cam.far = dist * 40; cam.updateProjectionMatrix(); cam.lookAt(camTarget);
        // Cache the head's on-screen position (NDC) so the look-at aims the gaze at the cursor RELATIVE
        // to where the head actually sits on screen (upper-right), not the screen centre.
        if (restHeadWorld) { const n = restHeadWorld.clone().project(cam); headRefX = n.x; headRefY = -n.y; }
    }

    function resize() {
        const w = container.clientWidth || 1, h = container.clientHeight || 1;
        renderer.setSize(w, h, false);
        cam.aspect = w / h;
        applyFrame();
    }

    new GLTFLoader().load(modelUrl, (g) => {
        model = g.scene; scene.add(model);
        model.traverse((o) => { if (o.isBone) BONES[o.name] = o; });
        for (const n of [...SPINES, ...ARMS]) { if (BONES[n]) base[n] = BONES[n].quaternion.clone(); }
        model.updateWorldMatrix(true, true); scene.updateMatrixWorld(true);
        // Lock the hammer to the right hand: it is a separate rig, so attach it to HandR so it rides the arm and never slides out.
        hammerRig = model.getObjectByName('Hammer_Rig');
        if (hammerRig && BONES.HandR) { BONES.HandR.attach(hammerRig); }
        hammerRig.updateWorldMatrix(true, false);
        leftGrip = hammerRig.worldToLocal(BONES.HandL.getWorldPosition(new THREE.Vector3()));
        const hpR = BONES.HandR.getWorldPosition(new THREE.Vector3()), hpL = BONES.HandL.getWorldPosition(new THREE.Vector3());
        const axisHR = hpL.clone().sub(hpR).normalize().applyQuaternion(BONES.HandR.getWorldQuaternion(new THREE.Quaternion()).invert());
        base.HandR.multiply(new THREE.Quaternion().setFromAxisAngle(axisHR, cfg.hammerTurn));
        BONES.HandR.quaternion.copy(base.HandR); BONES.HandR.updateWorldMatrix(false, true);
        computeFrame();
        restHeadWorld = BONES.Head.getWorldPosition(new THREE.Vector3()); // rest gaze pivot, for the head-relative look-at reference
        resize();
        ready = true;
    }, undefined, (err) => {
        // Model failed to load: fall back to the static poster rather than an empty box.
        console.warn('[vc] statue model failed to load; showing poster fallback.', err);
        if (typeof onFail === 'function') onFail();
    });

    // Cursor target (-1..1 across the viewport), smoothed, with a gentle idle sway.
    let tx = 0, ty = 0, bx = 0, by = 0, ax = 0, ay = 0, hx = 0, hy = 0, lastMove = -1e9;
    window.addEventListener('pointermove', (e) => { tx = (e.clientX / window.innerWidth) * 2 - 1; ty = (e.clientY / window.innerHeight) * 2 - 1; lastMove = performance.now(); }, { passive: true });
    window.addEventListener('pointerleave', () => { tx = 0; ty = 0; lastMove = -1e9; });
    window.addEventListener('resize', resize);
    if (window.ResizeObserver) { new ResizeObserver(resize).observe(container); }

    const _e = new THREE.Euler(), _q = new THREE.Quaternion();
    function setBone(name, yaw, pitch) {
        const b = BONES[name]; if (!b || !base[name]) return;
        _e.set(pitch * cfg.pitchSign, yaw * cfg.yawSign, 0, 'YXZ');
        b.quaternion.copy(base[name]).multiply(_q.setFromEuler(_e)); // additive to the posed rest
    }
    // Two-bone IK: bend upper + lower so `hand` reaches `target`, keeping the elbow's natural bend side.
    const _pw = new THREE.Quaternion(), _nw = new THREE.Quaternion(), _par = new THREE.Quaternion(), _s = new THREE.Vector3();
    function bonePos(b) { return new THREE.Vector3().setFromMatrixPosition(b.matrixWorld); }
    function rotBoneWorld(bone, pivot, cur, tgt) { // rotate bone (world) so (pivot->cur) aligns to (pivot->tgt)
        const from = cur.clone().sub(pivot), to = tgt.clone().sub(pivot);
        if (from.lengthSq() < 1e-10 || to.lengthSq() < 1e-10) return; // degenerate -> skip (never emit NaN into the skeleton)
        from.normalize(); to.normalize();
        if (from.dot(to) > 0.999999) return;
        const q = new THREE.Quaternion().setFromUnitVectors(from, to);
        bone.matrixWorld.decompose(_s, _pw, _s);
        _nw.copy(q).multiply(_pw);
        bone.parent.matrixWorld.decompose(_s, _par, _s);
        bone.quaternion.copy(_par.invert().multiply(_nw));
        bone.updateWorldMatrix(false, true);
    }
    function twoBoneIK(upper, lower, hand, target) {
        const root = bonePos(upper), mid = bonePos(lower), end = bonePos(hand);
        const l1 = root.distanceTo(mid), l2 = mid.distanceTo(end);
        if (l1 < 1e-4 || l2 < 1e-4) return;
        const toT = target.clone().sub(root);
        if (toT.lengthSq() < 1e-8) return;
        const dist = Math.max(Math.abs(l1 - l2) + 1e-3, Math.min(toT.length(), (l1 + l2) - 1e-3));
        const nT = toT.clone().normalize();
        const curE = mid.clone().sub(root);
        const pole = curE.clone().addScaledVector(nT, -curE.dot(nT));
        if (pole.lengthSq() < 1e-9) pole.set(0, 0, 1);
        pole.normalize();
        const a = Math.acos(Math.max(-1, Math.min(1, (l1 * l1 + dist * dist - l2 * l2) / (2 * l1 * dist))));
        const wantElbow = root.clone().addScaledVector(nT, l1 * Math.cos(a)).addScaledVector(pole, l1 * Math.sin(a));
        rotBoneWorld(upper, root, mid, wantElbow); // 1) place the elbow
        rotBoneWorld(lower, bonePos(lower), bonePos(hand), target); // 2) place the hand on target
    }

    (function loop() {
        requestAnimationFrame(loop);
        if (ready) {
            const now = performance.now();
            let gx = tx, gy = ty;                          // body + arms: cursor relative to screen centre (subtle lean)
            let hgx = tx - headRefX, hgy = ty - headRefY;  // head: cursor relative to the head's own on-screen spot, so the gaze lands ON the cursor
            if (now - lastMove > cfg.idleAfter) { // slow idle sway when the cursor is still
                const t = (now - cfg.idleAfter) / 1000;
                gx = Math.sin(t * 0.55) * cfg.idleYaw; gy = Math.sin(t * 0.4) * cfg.idlePitch;
                hgx = gx; hgy = gy; // head sways gently around forward
            }
            bx += (gx - bx) * cfg.bodyEase; by += (gy - by) * cfg.bodyEase; // slow torso lean
            ax += (gx - ax) * cfg.armEase; ay += (gy - ay) * cfg.armEase; // arms + hammer swing
            hx += (hgx - hx) * cfg.headEase; hy += (hgy - hy) * cfg.headEase; // fast head, head-relative
            if (introStart < 0) introStart = now;
            const it = Math.min((now - introStart) / cfg.introDur, 1), f = 1 - Math.pow(1 - it, 3);
            const iy = cfg.introYaw * (1 - f);
            const [S1y, S1p] = cfg.body.Spine1, [S2y, S2p] = cfg.body.Spine2, [S3y, S3p] = cfg.arms.Spine3;
            setBone('Spine1', bx * S1y + iy * 0.4, by * S1p);
            setBone('Spine2', bx * S2y + iy * 0.6, by * S2p); // rigid turn swings the whole upper body + hammer to face forward
            setBone('Spine3', ax * S3y, ay * S3p); // swings both arms + the locked hammer, grip preserved
            const baseYaw = bx * (S1y + S2y) + ax * S3y + iy, basePitch = by * (S1p + S2p) + ay * S3p;
            const cl = (v, a, b) => (v < a ? a : v > b ? b : v);
            const hYaw = cl(cfg.lookYaw * hx + cfg.introLookYaw * (1 - f), -0.7, 0.7) - baseYaw;
            const hPitch = cl(cfg.lookPitch * hy + cfg.pitchBias, -0.22, cfg.pitchMax) - basePitch;
            setBone('Neck', hYaw * cfg.neckShare, hPitch * cfg.neckShare);
            setBone('Head', hYaw * (1 - cfg.neckShare), hPitch * (1 - cfg.neckShare));
            // Arm IK: hammer rides the right hand; both arms reach to keep the grip.
            if (hammerRig && leftGrip) {
                for (const n of ARMS) { if (BONES[n] && base[n]) BONES[n].quaternion.copy(base[n]); } // reset arms to rest
                model.updateWorldMatrix(false, true);
                const [fnx, fny, fnz] = cfg.hammerFollow;
                const follow = new THREE.Vector3(ax * fnx, -ay * fny, ay * fnz);
                const restR = bonePos(BONES.HandR), restLg = bonePos(BONES.HandL);
                const reachR = bonePos(BONES.UpperArmR).distanceTo(bonePos(BONES.LowerArmR)) + bonePos(BONES.LowerArmR).distanceTo(restR);
                const reachL = bonePos(BONES.UpperArmL).distanceTo(bonePos(BONES.LowerArmL)) + bonePos(BONES.LowerArmL).distanceTo(restLg);
                const projOut = (v, grip, sh, reach) => {
                    const eDir = grip.clone().sub(sh); const cur = eDir.length(); eDir.normalize();
                    const room = Math.max(0, reach * 0.85 - cur), o = v.dot(eDir);
                    if (o > room) v.addScaledVector(eDir, -(o - room));
                }; // allow only up to 85% reach (safety margin at the screen edges)
                projOut(follow, restLg, bonePos(BONES.UpperArmL), reachL); // left arm is the binding one
                projOut(follow, restR, bonePos(BONES.UpperArmR), reachR);
                follow.clampLength(0, 0.016);
                const rTgt = restR.clone().add(follow);
                twoBoneIK(BONES.UpperArmR, BONES.LowerArmR, BONES.HandR, rTgt);
                BONES.HandR.updateWorldMatrix(false, true); // hammer (child of HandR) follows
                const lTgt = hammerRig.localToWorld(leftGrip.clone());
                twoBoneIK(BONES.UpperArmL, BONES.LowerArmL, BONES.HandL, lTgt); // left hand always grips the hammer bottom
            }
        }
        renderer.render(scene, cam);
        if (ready && !revealed) { // crossfade in only once there is a rendered frame, so there is no blank flash
            revealed = true;
            container.classList.add('spline-ready');
        }
    })();
}
