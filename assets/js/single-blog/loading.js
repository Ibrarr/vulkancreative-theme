import gsap from "gsap";
import { SplitText } from "gsap/SplitText";

gsap.registerPlugin(SplitText);

function runHeadingSplit() {
    let h1Tween;

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
                ease: "expo.out"
            });
        }
    });

    const tl = gsap.timeline();

    // H1 kicks off immediately
    if (h1Tween) tl.add(h1Tween, 0);

    // Columns fire at fixed times so there’s no “massive gap”
    tl.from(".content-area .left > *", {
        y: 24,
        opacity: 0,
        duration: 0.6,
        stagger: 0.08,
        ease: "power2.out"
    }, 0.45) // start ~450ms after page anims begin

        .from(".content-area .right > *", {
            y: 24,
            opacity: 0,
            duration: 0.6,
            stagger: 0.08,
            ease: "power2.out"
        }, 0.70); // then ~250ms later
}

if (document.fonts && document.fonts.ready) {
    document.fonts.ready.then(runHeadingSplit);
} else {
    window.addEventListener("load", runHeadingSplit);
}
