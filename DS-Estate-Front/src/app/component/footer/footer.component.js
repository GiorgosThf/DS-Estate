document.addEventListener("DOMContentLoaded", function () {
    const contentContainer = document.getElementById("content-container");
    const footer = document.getElementById("main-footer");

    // Function to check if the user has scrolled to the bottom
    function isScrolledToBottom() {
        const scrollPosition = window.scrollY + window.innerHeight;
        const totalHeight = contentContainer.offsetHeight;

        return scrollPosition >= totalHeight;
    }

    // Function to toggle footer visibility based on scroll position
    function toggleFooterVisibility() {
        if (isScrolledToBottom()) {
            footer.style.display = "block";
        } else {
            footer.style.display = "none";
        }
    }

    // Initial check and setup event listener for scrolling
    toggleFooterVisibility();
    window.addEventListener("scroll", toggleFooterVisibility);
});