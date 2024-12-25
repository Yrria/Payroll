document.addEventListener("DOMContentLoaded", function () {
    function setupDropdown(dropdownSelector, defaultValueFunction = null) {
        const dropdown = document.querySelector(dropdownSelector);
        const dropdownInput = dropdown.querySelector('.dropdown-input');
        const dropdownContent = dropdown.querySelector('.dropdown-content');
        const dropdownItems = dropdown.querySelectorAll('.dropdown-item');
        const dropdownIndicator = dropdown.querySelector('.dropdown-indicator');

        // Set the default value if a function is provided
        if (defaultValueFunction) {
            const defaultValue = defaultValueFunction();
            const defaultItem = Array.from(dropdownItems).find(item =>
                item.dataset.value === defaultValue.toString()
            );
            if (defaultItem) {
                dropdownInput.value = defaultItem.innerText;
            }
        }

        // Toggle the dropdown visibility
        function toggleDropdown(event) {
            event.stopPropagation();
            closeAllDropdowns(); // Close other open dropdowns
            dropdownContent.classList.toggle('show');
            dropdownIndicator.classList.toggle('rotated'); // Flip arrow
        }

        // Close all dropdowns except the current one
        function closeAllDropdowns() {
            document.querySelectorAll('.dropdown-content').forEach(content => {
                content.classList.remove('show');
            });
            document.querySelectorAll('.dropdown-indicator').forEach(indicator => {
                indicator.classList.remove('rotated');
            });
        }

        // Close dropdown when clicking outside
        function closeDropdown(event) {
            if (!dropdown.contains(event.target)) {
                dropdownContent.classList.remove('show');
                dropdownIndicator.classList.remove('rotated'); // Reset arrow
            }
        }

        // Event listeners
        dropdownInput.addEventListener('click', toggleDropdown);
        dropdownIndicator.addEventListener('click', toggleDropdown);

        dropdownItems.forEach(item => {
            item.addEventListener('click', function () {
                dropdownInput.value = item.innerText; // Set input value
                dropdownContent.classList.remove('show');
                dropdownIndicator.classList.remove('rotated'); // Reset arrow
            });
        });

        document.addEventListener('click', closeDropdown);
    }

    // Setup dropdowns with and without default value
    setupDropdown('.month-dropdown', () => new Date().getMonth() + 1);
    setupDropdown('.year-dropdown', () => new Date().getFullYear());
    setupDropdown('.cutoff-dropdown'); // No default value
});
