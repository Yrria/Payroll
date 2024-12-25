// MAIN FUNCTION

// main content minimizer based on sidenav
function toggleNav() {
    const sidenav = document.getElementById('sidenav');
    const mainContent = document.getElementById('mainContent');
    const toggleButton = document.querySelector('.toggle-btn');

    // Toggle the 'minimized' class for sidenav and 'shifted' class for mainContent and toggleButton
    sidenav.classList.toggle('minimized');
    mainContent.classList.toggle('shifted');
    toggleButton.classList.toggle('shifted');

    // Adjust the margin of mainContent based on sidenav state
    mainContent.style.marginLeft = sidenav.classList.contains('minimized') ? '60px' : '200px';
}
/* ------------------------------ END OF MAIN BODY ------------------------------ */



// SIDE NAV
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

sidenavBTN.addEventListener("click", () => {
    sidenav.classList.toggle("close");
});

// DROPDOWN for leave and report disable link 
let disabledLinks = document.querySelectorAll(".disabled-link");
disabledLinks.forEach(link => {
    link.addEventListener("click", (e) => {
        e.preventDefault();
    });
});
/* ------------------------------ END OF SIDENAV ------------------------------ */



// header side
function updateDateTime() {
    const now = new Date();
    const options = {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    };
    const dateString = now.toLocaleDateString(undefined, options);
    const timeString = now.toLocaleTimeString();

    document.getElementById('current-date').innerText = dateString;
    document.getElementById('current-time').innerText = timeString;
}

updateDateTime();
setInterval(updateDateTime, 1000);


// Toggle the dropdown menu and add/remove the 'active' class to profile
function toggleDropdown() {
    var dropdown = document.querySelector('.dropdown-menu');
    var profile = document.querySelector('.profile');
    dropdown.classList.toggle('show');
    profile.classList.toggle('active');
}
/* ------------------------------ END OF SIDENAV/HEADER ------------------------------ */



// DASHBOARD


// PROFILE


// ATTENDANCE


// EMPLOYYE


// PAYROLL


// LEAVE


// REPORT


