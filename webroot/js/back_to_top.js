const showOnPx = 20;
const backToTopButton = document.querySelector(".back-to-top");

function goToTop() {
    document.body.scrollIntoView({
        behavior: "smooth"
    });
}

document.addEventListener("scroll", function () {
    if (document.body.scrollTop > showOnPx || document.documentElement.scrollTop > showOnPx) {
        backToTopButton.classList.remove("invisible");
        backToTopButton.classList.remove("opacity-0");
    } else {
        backToTopButton.classList.add("invisible");
        backToTopButton.classList.add("opacity-0");
    }
});

backToTopButton.addEventListener("click", goToTop);
backToTopButton.classList.add("opacity-0");
