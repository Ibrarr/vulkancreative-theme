document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', e => {
            const id = link.getAttribute('href').slice(1);   // "why"
            const target = document.getElementById(id);
            if (target) {
                e.preventDefault();                          // stop the hash appearing
                target.scrollIntoView({ behaviour: 'smooth' });
                history.replaceState(null, '', '/');         // tidy URL → https://vulkancreative.test
            }
        });
    });
});