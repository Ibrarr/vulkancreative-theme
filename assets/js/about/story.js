import gsap from 'gsap';
import { SplitText } from 'gsap/SplitText';
import { prefersReducedMotion } from '../components/reduced-motion';

gsap.registerPlugin(SplitText);

// The film: a native <video>. It ships with `controls` so it plays without
// this script; here the controls step aside for the house play button until
// the film starts, then come back for scrubbing and volume. This is state, not
// motion, so it runs under reduced motion too.
document.addEventListener('DOMContentLoaded', () => {
    const wrapper = document.querySelector('.about-story .video-wrapper');
    const video = document.getElementById('our-story');
    const play = wrapper ? wrapper.querySelector('.video-play') : null;
    if (!wrapper || !video || !play) return;

    video.removeAttribute('controls');
    play.hidden = false;

    const start = () => {
        video.setAttribute('controls', '');
        const attempt = video.play();
        if (attempt && typeof attempt.catch === 'function') attempt.catch(() => {});
    };

    play.addEventListener('click', start);
    video.addEventListener('play', () => wrapper.classList.add('is-playing'));
    // The head's "Watch the Film" button scrolls here; starting playback from a
    // click keeps it inside the browser's user-gesture rule.
    document.querySelectorAll('.about-story a[href="#watch"]').forEach((link) => {
        link.addEventListener('click', start);
    });
});

// Motion. The heading's line reveal lives in about/reveal.js with the other
// section headings; this module owns the two entrances that belong to the
// film: the description's line-by-line rise and the frame's cinema wipe.
document.addEventListener('DOMContentLoaded', () => {
    if (prefersReducedMotion() || !('IntersectionObserver' in window)) return;

    const copy = document.querySelector('.split-text-story');
    const frame = document.querySelector('.about-story .video-wrapper');

    if (frame) gsap.set(frame, { clipPath: 'inset(100% 0% 0% 0%)' });

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            obs.unobserve(entry.target);

            if (entry.target === frame) {
                gsap.to(frame, { clipPath: 'inset(0% 0% 0% 0%)', duration: 0.9, ease: 'expo.out', clearProps: 'clipPath' });
                return;
            }

            // aria: 'none' because SplitText's default writes aria-label onto the
            // paragraph, which is a prohibited attribute there; reverting on
            // completion hands assistive tech the untouched paragraph.
            gsap.set(copy, { opacity: 1 });
            SplitText.create(copy, {
                type: 'lines',
                linesClass: 'line',
                mask: 'lines',
                aria: 'none',
                autoSplit: false,
                onSplit(self) {
                    return gsap.from(self.lines, {
                        yPercent: 100,
                        duration: 0.8,
                        stagger: 0.1,
                        ease: 'expo.out',
                        onComplete: () => self.revert(),
                    });
                },
            });
        });
    }, { threshold: 0.15, rootMargin: '0px 0px -4% 0px' });

    // Fonts first: the split must measure the settled line breaks.
    document.fonts.ready.then(() => {
        if (copy) observer.observe(copy);
        if (frame) observer.observe(frame);
    });

    // Never leave the frame clipped or the copy hidden if the observer stalls.
    setTimeout(() => {
        if (frame) gsap.set(frame, { clearProps: 'clipPath' });
        if (copy) gsap.set(copy, { opacity: 1 });
    }, 4000);
});
