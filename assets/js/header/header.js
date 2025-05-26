jQuery(document).ready(function ($) {
    const header        = document.getElementById("header");
    let   prevScrollPos = window.pageYOffset;

    if (prevScrollPos <= 97) {
        header.style.top = "0";
    } else {
        header.style.top = "-102px";
    }

    window.addEventListener("scroll", () => {
        const currentScrollPos = window.pageYOffset;

        // if (currentScrollPos > 20) {
        //     header.classList.add("scrolled");
        // } else {
        //     header.classList.remove("scrolled");
        // }

        if (prevScrollPos > currentScrollPos || currentScrollPos <= 97) {
            // scrolling up, or already close to the top
            header.style.top = "0";
        } else {
            // scrolling down
            header.style.top = "-102px";
        }

        prevScrollPos = currentScrollPos;
    });
});
