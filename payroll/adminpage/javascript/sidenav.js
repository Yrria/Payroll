function toggleNav() {
    const sidenav = document.getElementById('sidenav');
    sidenav.classList.toggle('minimized');
}

document.querySelectorAll('.sidenav ul li').forEach(item => {
    item.addEventListener('click', () => {
        const dropdown = item.querySelector('ul.dropdown');
        if (dropdown) {
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }
    });
});