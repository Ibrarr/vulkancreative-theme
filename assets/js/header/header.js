jQuery(document).ready(function ($) {
    let prevScrollpos = window.pageYOffset;

    window.onscroll = function () {
        let currentScrollPos = window.pageYOffset;
        const header = document.getElementById("header");

        if (currentScrollPos > 20) {
            header.classList.add("scrolled");
        } else {
            header.classList.remove("scrolled");
        }

        if (prevScrollpos > currentScrollPos || currentScrollPos <= 97) {
            // Scrolling up or at the top of the page
            header.style.top = "0"; // Adjust to your desired position
            $('.main-menu-text').fadeIn(300);
        } else {
            // Scrolling down
            header.style.top = "-100px"; // Adjust based on header height
            $('.main-menu-text').fadeOut(300);
        }

        prevScrollpos = currentScrollPos;
    };
});
