import gsap from "gsap";
import { SplitText } from "gsap/SplitText";

gsap.registerPlugin(SplitText);

function runHeadingSplit() {
    let h1Tween;
    const tl = gsap.timeline();

    tl.fromTo(".heading h1",
        { y: 20, autoAlpha: 0 },
        { y: 0, autoAlpha: 1, duration: 0.6, clearProps: "transform", ease: "power2.out" },
        0
    );

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

    if (h1Tween) tl.add(h1Tween, 0.05);

    tl.from(".content-area .left", {
        y: 24,
        opacity: 0,
        duration: 0.6,
        stagger: 0.08,
        ease: "power2.out"
    }, 0.45)
        .from(".content-area .right", {
            y: 24,
            opacity: 0,
            duration: 0.6,
            stagger: 0.08,
            ease: "power2.out"
        }, 0.70);
}

if (document.fonts && document.fonts.ready) {
    document.fonts.ready.then(runHeadingSplit);
} else {
    window.addEventListener("load", runHeadingSplit);
}
