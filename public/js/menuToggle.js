const menuBtn = document.querySelector(".menu-btn");
const asideMenu = document.querySelector("#menu");
const mainBody = document.querySelector(".main-body");

if(menuBtn && asideMenu && mainBody) {
    // ✅ Load saved state on page load
    const savedState = localStorage.getItem("menuState");
    if (savedState === "collapsed") {
        asideMenu.classList.add("hidden");
        mainBody.classList.add("menu-collapsed");
    }

    // ✅ Toggle sidebar and save state
    menuBtn.addEventListener("click", () => {
        asideMenu.classList.toggle("hidden");
        mainBody.classList.toggle("menu-collapsed");

        // Save state
        if (asideMenu.classList.contains("hidden")) {
            localStorage.setItem("menuState", "collapsed");
        } else {
            localStorage.setItem("menuState", "expanded");
        }
    });

}