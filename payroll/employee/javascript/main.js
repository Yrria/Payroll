// MAIN FUNCTION
function toggleNav() {
    const sidenav = document.getElementById('sidenav');
    const mainContent = document.getElementById('mainContent');
    const toggleButton = document.querySelector('.toggle-btn');

    sidenav.classList.toggle('minimized');
    mainContent.classList.toggle('shifted');
    toggleButton.classList.toggle('shifted');

    if (sidenav.classList.contains('minimized')) {
        mainContent.style.marginLeft = '60px';
        toggleButton.style.marginLeft = '-135px';
        localStorage.setItem('sidenavState', 'minimized'); // Save state as minimized
    } else {
        mainContent.style.marginLeft = '200px';
        toggleButton.style.marginLeft = '5px';
        localStorage.setItem('sidenavState', 'expanded'); // Save state as expanded
    }
}

// Apply the saved state on page load
document.addEventListener('DOMContentLoaded', () => {
    const sidenav = document.getElementById('sidenav');
    const mainContent = document.getElementById('mainContent');
    const toggleButton = document.querySelector('.toggle-btn');

    const savedState = localStorage.getItem('sidenavState');

    // Prevent unnecessary class toggles by directly setting the state
    if (savedState === 'minimized') {
        sidenav.classList.add('minimized');
        mainContent.classList.add('shifted');
        toggleButton.classList.add('shifted');
        mainContent.style.marginLeft = '60px';
        toggleButton.style.marginLeft = '-135px';
    } else {
        sidenav.classList.remove('minimized');
        mainContent.classList.remove('shifted');
        toggleButton.classList.remove('shifted');
        mainContent.style.marginLeft = '200px';
        toggleButton.style.marginLeft = '5px';
    }
});

// DASHBOARD


// PROFILE


// LEAVE


// SALARY


