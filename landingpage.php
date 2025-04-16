document.addEventListener('DOMContentLoaded', function() {
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    const dropdownContainers = document.querySelectorAll('.dropdown-container');
    
    // For desktop devices (hover functionality)
    if (!('ontouchstart' in window)) {
        dropdownContainers.forEach(container => {
            container.addEventListener('mouseenter', function() {
                this.querySelector('.dropdown-menu').classList.remove('hidden');
                this.querySelector('.dropdown-menu').classList.add('block');
            });
            container.addEventListener('mouseleave', function() {
                this.querySelector('.dropdown-menu').classList.remove('block');
                this.querySelector('.dropdown-menu').classList.add('hidden');
            });
        });
    } 
    // For mobile devices (click functionality)
    else {
        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const menu = this.nextElementSibling;
                const isVisible = menu.classList.contains('block');
                // Hide all other dropdowns
                document.querySelectorAll('.dropdown-menu').forEach(dropdown => {
                    dropdown.classList.remove('block');
                    dropdown.classList.add('hidden');
                });
                // Show current dropdown if it wasn't visible
                if (!isVisible) {
                    menu.classList.remove('hidden');
                    menu.classList.add('block');
                    // Close dropdown when clicking outside
                    document.addEventListener('click', function closeDropdown(event) {
                        if (!menu.contains(event.target) && !toggle.contains(event.target)) {
                            menu.classList.remove('block');
                            menu.classList.add('hidden');
                            document.removeEventListener('click', closeDropdown);
                        }
                    });
                }
            });
        });
    }
}); 