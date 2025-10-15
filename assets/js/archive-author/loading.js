import gsap from "gsap";
import { SplitText } from "gsap/SplitText";

gsap.registerPlugin(SplitText);

function onReady(fn) {
    if (document.fonts && document.fonts.ready) {
        document.fonts.ready.then(fn);
    } else {
        window.addEventListener("load", fn, { once: true });
    }
}

function runPosts() {
    const tl = gsap.timeline({ defaults: { ease: "power2.out" } });

    tl.fromTo(".posts article",
        { y: 30, autoAlpha: 0 },
        { y: 0, autoAlpha: 1, duration: 0.8, stagger: 0.17, clearProps: "transform" },
        0
    )
        .fromTo(".posts .pagination",
            { y: 20, autoAlpha: 0 },
            { y: 0, autoAlpha: 1, duration: 0.6, clearProps: "transform" },
            ">-0.6"
        );

    return tl;
}

function runHeadingSplits() {
    let bcTween, h1Tween;

    const tlHead = gsap.timeline({ defaults: { ease: "power2.out" } });

    tlHead
        .fromTo(".heading .breadcrumbs",
            { y: 20, autoAlpha: 0 },
            { y: 0, autoAlpha: 1, duration: 0.6, clearProps: "transform" },
            0
        )
        .fromTo(".heading h1",
            { y: 20, autoAlpha: 0 },
            { y: 0, autoAlpha: 1, duration: 0.6, clearProps: "transform" },
            ">-0.45"
        );

    SplitText.create(".heading .breadcrumbs", {
        type: "words,lines",
        linesClass: "line",
        autoSplit: true,
        mask: "lines",
        onSplit: ({ lines }) => {
            bcTween = gsap.from(lines, {
                yPercent: 100,
                opacity: 0,
                duration: 1.2,
                stagger: 0.2,
                ease: "expo.out",
                clearProps: "transform,opacity"
            });
        }
    });

    SplitText.create(".heading h1", {
        type: "words,lines",
        linesClass: "line",
        autoSplit: true,
        mask: "lines",
        onSplit: ({ lines }) => {
            h1Tween = gsap.from(lines, {
                yPercent: 100,
                opacity: 0,
                duration: 1.8,
                stagger: 0.2,
                ease: "expo.out",
                clearProps: "transform,opacity"
            });
        }
    });

    if (bcTween) tlHead.add(bcTween, 0);
    if (h1Tween) tlHead.add(h1Tween, 0.15);

    tlHead.fromTo(".author-text",
        { y: 16, autoAlpha: 0 },
        { y: 0, autoAlpha: 1, duration: 0.6, clearProps: "transform" },
        "-=1.5"          // overlap the tail of the H1
    );

    tlHead.add(runPosts(), "-=1.3"); // overlap posts with author-text
}

onReady(runHeadingSplits);