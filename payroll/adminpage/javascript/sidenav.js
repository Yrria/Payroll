let iconLinks = document.querySelectorAll(".icon-link");

iconLinks.forEach(iconLink => {
    iconLink.addEventListener("click", (e) => {
        let parentLi = e.target.closest("li");
        let allLi = document.querySelectorAll(".nav-links li");

        allLi.forEach(li => {
            if (li !== parentLi) {
                li.classList.remove("showmenu");
            }
        });

        parentLi.classList.toggle("showmenu");
    });
});

let sidenav = document.querySelector(".sidenav");
let sidenavBTN = document.querySelector(".toggle-btn");

// Apply the sidenav state on load
const applySidenavState = () => {
    const sidenavState = localStorage.getItem("sidenavState");
    if (sidenavState === "minimized") {
        sidenav.classList.add("close");
    } else {
        sidenav.classList.remove("close");
    }
};

applySidenavState(); // Apply state on page load

sidenavBTN.addEventListener("click", () => {
    sidenav.classList.toggle("close");

    // Save the sidenav state to localStorage
    if (sidenav.classList.contains("close")) {
        localStorage.setItem("sidenavState", "minimized");
    } else {
        localStorage.setItem("sidenavState", "expanded");
    }
});

let disabledLinks = document.querySelectorAll(".disabled-link");
disabledLinks.forEach(link => {
    link.addEventListener("click", (e) => {
        e.preventDefault();
    });
});
