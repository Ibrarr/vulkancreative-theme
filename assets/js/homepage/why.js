import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { shadowCursor } from '../components/shadow-cursor'

gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {
    gsap.from('.why .top', {
        opacity: 0,
        y: 50,
        duration: 0.6,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.why .top',
            start: 'top 95%',
            toggleActions: 'play none none none',
        }
    });

    document.querySelectorAll('.why-box-container').forEach(container => {
        const boxes = container.querySelectorAll('.why-boxes');

        gsap.from(boxes, {
            opacity: 0,
            y: 50,
            duration: 0.6,
            ease: 'power2.out',
            stagger: 0.2,
            scrollTrigger: {
                trigger: container,
                start: 'top 95%',
                toggleActions: 'play none none none',
                onEnter: () => {
                    boxes.forEach(box => {
                        const revealImage = box.querySelector('.reveal');
                        const infiniteImage = box.querySelector('.infinite');

                        // Reload the .reveal image
                        if (revealImage) {
                            const revealSrc = revealImage.src;
                            revealImage.src = '';
                            revealImage.src = revealSrc;

                            setTimeout(() => {
                                if (infiniteImage) {
                                    // Reload the .infinite image
                                    const infiniteSrc = infiniteImage.src;
                                    infiniteImage.src = '';
                                    infiniteImage.src = infiniteSrc;
                                }
                            }, 1200);

                            setTimeout(() => {
                                revealImage.style.display = 'none';
                                infiniteImage.style.display = 'block';
                            }, 1800);
                        }
                    });
                }
            }
        });
    });

    gsap.from('.why .bottom', {
        opacity: 0,
        y: 50,
        duration: 0.6,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.why .bottom',
            start: 'top 100%',
            toggleActions: 'play none none none',
        }
    });
});

shadowCursor('.why');