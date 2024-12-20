// MAIN FUNCTION
function toggleNav() {
    const sidenav = document.getElementById('sidenav');
    const mainContent = document.getElementById('mainContent');
    const toggleButton = document.querySelector('.toggle-btn');
    const compImages = document.querySelectorAll('.comp-name img'); // Assuming images are inside .comp-name

    sidenav.classList.toggle('minimized');
    mainContent.classList.toggle('shifted');
    toggleButton.classList.toggle('shifted');

    if (sidenav.classList.contains('minimized')) {
        mainContent.style.marginLeft = '60px';
        toggleButton.style.marginLeft = '-140px';
        localStorage.setItem('sidenavState', 'minimized'); // Save state as minimized

        // Adjust image styles when minimized
        compImages.forEach(image => {
            image.style.height = '7px'; // Resize image height to 7px
            image.style.marginLeft = '0px'; // Set marginLeft to 0px when minimized
        });
    } else {
        mainContent.style.marginLeft = '200px';
        toggleButton.style.marginLeft = '0px';
        localStorage.setItem('sidenavState', 'expanded'); // Save state as expanded

        // Revert image styles when expanded
        compImages.forEach(image => {
            image.style.height = ''; // Reset height to default or stylesheet value
            image.style.marginLeft = '21px'; // Set marginLeft to 22px when expanded
        });
    }
}

// Apply the saved state on page load
document.addEventListener('DOMContentLoaded', () => {
    const sidenav = document.getElementById('sidenav');
    const mainContent = document.getElementById('mainContent');
    const toggleButton = document.querySelector('.toggle-btn');
    const compImages = document.querySelectorAll('.comp-name img'); // Assuming images are inside .comp-name

    const savedState = localStorage.getItem('sidenavState');

    if (savedState === 'minimized') {
        sidenav.classList.add('minimized');
        mainContent.classList.add('shifted');
        toggleButton.classList.add('shifted');
        mainContent.style.marginLeft = '60px';
        toggleButton.style.marginLeft = '-140px';

        // Adjust image styles for minimized state
        compImages.forEach(image => {
            image.style.height = '7px'; // Resize image height to 7px
            image.style.marginLeft = '0px'; // Set marginLeft to 0px when minimized
        });
    } else {
        mainContent.style.marginLeft = '200px';
        toggleButton.style.marginLeft = '0px';

        // Ensure image styles are reset for expanded state
        compImages.forEach(image => {
            image.style.height = ''; // Reset height to default or stylesheet value
            image.style.marginLeft = '21px'; // Set marginLeft to 22px when expanded
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


