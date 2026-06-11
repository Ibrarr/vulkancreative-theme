document.addEventListener('DOMContentLoaded', () => {
    const toggleBtns = document.querySelectorAll('.theme-toggle');
    const body = document.body;

    // Force dark mode on Your Business landing page
    if (body.classList.contains('page-template-page-your-business')) {
        body.classList.add('dark-mode');
        toggleBtns.forEach(btn => btn.classList.add('theme-toggle--toggled'));
        return;
    }

    // Dark-first: the server renders body.dark-mode and an inline script in
    // header.php removes it before paint when the visitor chose light mode.
    // Here we only sync the toggle buttons with the resolved state.
    if (body.classList.contains('dark-mode')) {
        toggleBtns.forEach(btn => btn.classList.add('theme-toggle--toggled'));
    }

    toggleBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Toggle dark mode on <body>
            body.classList.toggle('dark-mode');

            // Persist dark-mode preference
            try {
                localStorage.setItem('darkMode', body.classList.contains('dark-mode') ? 'enabled' : 'disabled');
            } catch (e) {}

            // Update all toggle buttons' appearance
            toggleBtns.forEach(b => b.classList.toggle('theme-toggle--toggled'));
        });
    });
});
