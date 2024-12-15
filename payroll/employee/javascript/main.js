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
    } else {
        mainContent.style.marginLeft = '200px';
        toggleButton.style.marginLeft = '5px';
    }
}

// DASHBOARD


// PROFILE


// LEAVE


// SALARY


