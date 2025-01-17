export const fireworkEffect = (selector) => {
    jQuery(document).ready(function ($) {
        function random(min, max) {
            return min + Math.random() * (max - min);
        }

        function createFirework(target) {
            const rect = target.getBoundingClientRect();
            const xPos = rect.left + rect.width / 2 + window.scrollX;
            const yPos = rect.top + rect.height / 2 + window.scrollY;

            for (let i = 1; i <= 20; i++) {
                const firework = document.createElement('div');
                firework.className = 'firework';
                firework.style.backgroundColor = '#FF3B30';
                firework.style.left = `${xPos}px`;
                firework.style.top = `${yPos}px`;
                firework.style.zIndex = -1;
                firework.style.animation = `launchFirework ${random(0.5, 1)}s linear forwards, 
          flyInDirection${i} ${random(0.5, 1)}s linear forwards`;

                document.body.appendChild(firework);

                setTimeout(() => {
                    firework.remove();
                }, 1000);
            }
        }

        let fireworkInterval;

        $(selector).on('mouseenter', function (e) {
            const target = e.target;

            // Trigger the firework effect immediately
            createFirework(target);

            // Start the interval for continuous fireworks
            fireworkInterval = setInterval(() => {
                createFirework(target);
            }, 500);
        });

        $(selector).on('mouseleave', function () {
            clearInterval(fireworkInterval);
        });
    });
};
