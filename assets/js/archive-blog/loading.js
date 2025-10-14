// blog-heading-and-posts.js
import gsap from "gsap";
import { SplitText } from "gsap/SplitText";

gsap.registerPlugin(SplitText);

// 1) POSTS: run ASAP on DOM ready (no font waiting)
document.addEventListener("DOMContentLoaded", () => {
    const tl = gsap.timeline({ defaults: { ease: "power2.out" } });

    tl.from(".posts article", {
        y: 30,
        opacity: 0,
        duration: 0.8,
        stagger: 0.17
    }, 0)
        .fromTo(".posts .pagination",
            { y: 20, autoAlpha: 0 },
            { y: 0, autoAlpha: 1, duration: 0.6, clearProps: "transform" },
            ">-0.6"
        );
});

// 2) HEADINGS: wait for fonts so SplitText line metrics don’t lie
function runHeadingSplits() {
    let bcTween, h1Tween;

    SplitText.create(".heading .breadcrumbs", {
        type: "words,lines",
        linesClass: "line",
        autoSplit: true,
        mask: "lines",
        onSplit: ({ lines }) => {
            // slowed down
            bcTween = gsap.from(lines, {
                yPercent: 100,
                opacity: 0,
                duration: 1.2,
                stagger: 0.2,
                ease: "expo.out"
            });
        }
    });

    SplitText.create(".heading h1", {
        type: "words,lines",
        linesClass: "line",
        autoSplit: true,
        mask: "lines",
        onSplit: ({ lines }) => {
            // slowed down to match breadcrumbs
            h1Tween = gsap.from(lines, {
                yPercent: 100,
                opacity: 0,
                duration: 1.8,
                stagger: 0.2,
                ease: "expo.out"
            });
        }
    });

    // tiny overlap so it feels cohesive, not sleepy
    const tlHead = gsap.timeline();
    if (bcTween) tlHead.add(bcTween, 0);
    if (h1Tween) tlHead.add(h1Tween, 0.15);
}

if (document.fonts && document.fonts.ready) {
    document.fonts.ready.then(runHeadingSplits);
} else {
    // if the browser doesn’t speak Font Loading API, do it after load
    window.addEventListener("load", runHeadingSplits);
}
