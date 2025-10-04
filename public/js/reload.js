// Save scroll position before reload
window.addEventListener("beforeunload", function () {
    sessionStorage.setItem("scrollPos", window.scrollY);
});

// Restore scroll position on load
window.addEventListener("load", function () {
    let scrollPos = sessionStorage.getItem("scrollPos");
    if (scrollPos) {
        window.scrollTo(0, scrollPos);
    }
});