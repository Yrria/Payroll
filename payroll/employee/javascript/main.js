// MAIN FUNCTION
function toggleNav() {
    const sidenav = document.getElementById('sidenav');
    const mainContent = document.getElementById('mainContent');
    const toggleButton = document.querySelector('.toggle-btn');
    const compNameElements = document.querySelectorAll('.comp-name');

    sidenav.classList.toggle('minimized');
    mainContent.classList.toggle('shifted');
    toggleButton.classList.toggle('shifted');

    if (sidenav.classList.contains('minimized')) {
        mainContent.style.marginLeft = '60px';
        toggleButton.style.marginLeft = '-140px';
        localStorage.setItem('sidenavState', 'minimized'); // Save state as minimized

        // Adjust font size when minimized
        compNameElements.forEach(element => {
            element.style.fontSize = '12.5px';
            element.style.marginLeft = '0px';
        });
    } else {
        mainContent.style.marginLeft = '200px';
        toggleButton.style.marginLeft = '0px';
        localStorage.setItem('sidenavState', 'expanded'); // Save state as expanded

        // Revert font size when expanded
        compNameElements.forEach(element => {
            element.style.fontSize = ''; // Resets to the default or stylesheet value
            element.style.marginLeft = '';
        });
    }
}

// Apply the saved state on page load
document.addEventListener('DOMContentLoaded', () => {
    const sidenav = document.getElementById('sidenav');
    const mainContent = document.getElementById('mainContent');
    const toggleButton = document.querySelector('.toggle-btn');
    const compNameElements = document.querySelectorAll('.comp-name');

    const savedState = localStorage.getItem('sidenavState');

    if (savedState === 'minimized') {
        sidenav.classList.add('minimized');
        mainContent.classList.add('shifted');
        toggleButton.classList.add('shifted');
        mainContent.style.marginLeft = '60px';
        toggleButton.style.marginLeft = '-140px';

        // Adjust font size for minimized state
        compNameElements.forEach(element => {
            element.style.fontSize = '12.5px';
            element.style.marginLeft = '0px';
        });
    } else {
        mainContent.style.marginLeft = '200px';
        toggleButton.style.marginLeft = '0px';

        // Ensure font size is reset for expanded state
        compNameElements.forEach(element => {
            element.style.fontSize = '';
            element.style.marginLeft = '';
        });
    }
});



// DASHBOARD


// PROFILE


// ATTENDANCE


// EMPLOYYE


// PAYROLL


// LEAVE


// REPORT


