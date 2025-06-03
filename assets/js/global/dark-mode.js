document.addEventListener('DOMContentLoaded', () => {
    const toggleBtns = document.querySelectorAll('.theme-toggle');
    const body = document.body;

    // Load user's theme preference from localStorage
    if (localStorage.getItem('darkMode') === 'enabled') {
        body.classList.add('dark-mode');
        toggleBtns.forEach(btn => btn.classList.add('theme-toggle--toggled'));
    }

    toggleBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Toggle dark mode on <body>
            body.classList.toggle('dark-mode');

            // Persist dark-mode preference
            localStorage.setItem('darkMode', body.classList.contains('dark-mode') ? 'enabled' : 'disabled');

            // Update all toggle buttons' appearance
            toggleBtns.forEach(b => b.classList.toggle('theme-toggle--toggled'));
        });
    });
});
