function toggleNav() {
    const sidenav = document.getElementById('sidenav');
    const mainContent = document.getElementById('mainContent');
    const toggleButton = document.querySelector('.toggle-btn');

    sidenav.classList.toggle('minimized');
    if (sidenav.classList.contains('minimized')) {
        mainContent.style.marginLeft = '60px';
    } else {
        mainContent.style.marginLeft = '200px';
    }
}