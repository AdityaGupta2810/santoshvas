<script>
        // Select DOM elements
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');
        const menuBtn = document.getElementById('menu-btn');
        const switchMode = document.getElementById('switch-mode');
        const toggleSwitch = document.getElementById('toggle-switch');
        const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li');
        const sidebarText = document.querySelectorAll('.sidebar-text');
        // const searchToggle = document.getElementById('search-toggle');
        // const mobileSearch = document.querySelector('.mobile-search');
        // const darkIcon = document.querySelector('.dark-icon');
        // const lightIcon = document.querySelector('.light-icon');

        // Toggle sidebar visibility
        menuBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            
            if (sidebar.classList.contains('collapsed')) {
                sidebar.classList.remove('w-72');
                sidebar.classList.add('w-16');
                content.classList.remove('ml-72');
                content.classList.add('ml-16');
                
                // Hide text, keep icons
                sidebarText.forEach(text => {
                    text.classList.add('hidden');
                });
                
                // Center icons
                document.querySelectorAll('#sidebar a').forEach(item => {
                    item.classList.remove('px-6');
                    item.classList.add('px-3', 'justify-center');
                });
            } else {
                sidebar.classList.remove('w-16');
                sidebar.classList.add('w-72');
                content.classList.add('ml-72');
                content.classList.remove('ml-16');
                
                // Show text
                sidebarText.forEach(text => {
                    text.classList.remove('hidden');
                });
                
                // Reset icon alignment
                document.querySelectorAll('#sidebar a').forEach(item => {
                    item.classList.add('px-6');
                    item.classList.remove('px-3', 'justify-center');
                });
            }
        });

       

        // Active state for menu items
        allSideMenu.forEach(item => {
            item.addEventListener('click', function() {
                allSideMenu.forEach(i => {
                    i.classList.remove('bg-blue-50', 'bg-blue-900', 'bg-opacity-20');
                    i.classList.remove('border-r-4');
                    i.classList.remove('border-blue-500');
                    i.querySelector('a').classList.remove('text-blue-500');
                    
                    // Check if dark mode is active
                    if (document.body.classList.contains('bg-gray-900')) {
                        i.querySelector('a').classList.add('text-gray-200');
                    } else {
                        i.querySelector('a').classList.add('text-gray-700');
                    }
                });
                
                this.classList.add('border-r-4');
                this.classList.add('border-blue-500');
                
                // Apply different bg based on theme
                if (document.body.classList.contains('bg-gray-900')) {
                    this.classList.add('bg-blue-900', 'bg-opacity-20');
                } else {
                    this.classList.add('bg-blue-50');
                }
                
                this.querySelector('a').classList.add('text-blue-500');
                
                if (document.body.classList.contains('bg-gray-900')) {
                    this.querySelector('a').classList.remove('text-gray-200');
                } else {
                    this.querySelector('a').classList.remove('text-gray-700');
                }
            });
        });

        // Responsive adjustments

       // Add this to your existing script section or modify the handleResize function

function handleResize() {
    if (window.innerWidth < 768) {
        // For small screens
        sidebar.classList.add('collapsed');
        sidebar.classList.remove('w-72');
        sidebar.classList.add('w-16');
        content.classList.remove('ml-72');
        content.classList.add('ml-16');
        
        // Hide text, keep icons
        sidebarText.forEach(text => {
            text.classList.add('hidden');
        });
        
        // Center icons
        document.querySelectorAll('#sidebar a').forEach(item => {
            item.classList.remove('px-6');
            item.classList.add('px-3', 'justify-center');
        });

        // Show the search toggle icon for small screens only
        // document.getElementById('search-toggle').classList.remove('hidden');
        
        // Hide desktop search form
        // document.querySelector('.search-form').classList.add('hidden');
        
    } else if (!sidebar.classList.contains('collapsed')) {
        // For medium and larger screens
        sidebar.classList.remove('collapsed');
        sidebar.classList.add('w-72');
        sidebar.classList.remove('w-16');
        content.classList.add('ml-72');
        content.classList.remove('ml-16');
        
        // Show text
        sidebarText.forEach(text => {
            text.classList.remove('hidden');
        });
        
        // Reset icon alignment
        document.querySelectorAll('#sidebar a').forEach(item => {
            item.classList.add('px-6');
            item.classList.remove('px-3', 'justify-center');
        });
        
        // Hide the search toggle icon on larger screens
        document.getElementById('search-toggle').classList.add('hidden');
        
        // Show desktop search form
        // document.querySelector('.search-form').classList.remove('hidden');
        
        // Ensure mobile search is hidden
        // mobileSearch.classList.add('hidden');
    }
}

// Make sure this function runs on load and resize
window.addEventListener('resize', handleResize);
handleResize();


    </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all dropdown toggles
        const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
        
        // Add click event listener to each dropdown toggle
        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault(); // Prevent the default anchor behavior
                
                // Get the submenu
                const submenu = this.nextElementSibling;
                
                // Get the chevron icon
                const chevron = this.querySelector('.bx-chevron-right');
                
                // Toggle the submenu visibility
                if (submenu.classList.contains('hidden')) {
                    submenu.classList.remove('hidden');
                    chevron.style.transform = 'rotate(90deg)';
                } else {
                    submenu.classList.add('hidden');
                    chevron.style.transform = 'rotate(0)';
                }
                
                // Close other open submenus
                dropdownToggles.forEach(otherToggle => {
                    if (otherToggle !== toggle) {
                        const otherSubmenu = otherToggle.nextElementSibling;
                        const otherChevron = otherToggle.querySelector('.bx-chevron-right');
                        
                        if (otherSubmenu && !otherSubmenu.classList.contains('hidden')) {
                            otherSubmenu.classList.add('hidden');
                            if (otherChevron) {
                                otherChevron.style.transform = 'rotate(0)';
                            }
                        }
                    }
                });
            });
        });
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown')) {
                const submenus = document.querySelectorAll('.submenu');
                const chevrons = document.querySelectorAll('.bx-chevron-right');
                
                submenus.forEach(submenu => {
                    submenu.classList.add('hidden');
                });
                
                chevrons.forEach(chevron => {
                    chevron.style.transform = 'rotate(0)';
                });
            }
        });
    });
</script>
</body>
</html>