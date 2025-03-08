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

        // Toggle search on mobile
        // searchToggle.addEventListener('click', function() {
        //     mobileSearch.classList.toggle('hidden');
        // });

        // // Toggle dark mode
        // switchMode.addEventListener('change', function() {
        //     if (this.checked) {
        //         document.body.classList.add('bg-gray-900');
        //         document.body.classList.remove('bg-gray-100');
        //         toggleSwitch.classList.add('translate-x-6');
                
        //         // Toggle theme icons
        //         darkIcon.classList.remove('hidden');
        //         lightIcon.classList.add('hidden');
                
        //         // Apply dark mode to elements
        //         document.querySelectorAll('.bg-white').forEach(el => {
        //             el.classList.remove('bg-white');
        //             el.classList.add('bg-gray-800');
        //         });
                
        //         document.querySelectorAll('.text-gray-700').forEach(el => {
        //             el.classList.remove('text-gray-700');
        //             el.classList.add('text-gray-200');
        //         });
                
        //         document.querySelectorAll('.text-gray-500').forEach(el => {
        //             el.classList.remove('text-gray-500');
        //             el.classList.add('text-gray-400');
        //         });


        //         // ////////#

        //         // document.querySelectorAll('.container').forEach(el => {
        //         //     // el.classList.remove('text-gray-500');
        //         //     el.classList.add('text-white');
        //         // });


               
                
        //         document.querySelectorAll('.bg-gray-100').forEach(el => {
        //             el.classList.remove('bg-gray-100');
        //             el.classList.add('bg-gray-900');
        //         });
                
        //         // Status badges - adjust for dark mode
        //         document.querySelectorAll('.bg-green-100').forEach(el => {
        //             el.classList.remove('bg-green-100');
        //             el.classList.add('bg-green-900', 'bg-opacity-30');
        //         });
                
        //         document.querySelectorAll('.bg-yellow-100').forEach(el => {
        //             el.classList.remove('bg-yellow-100');
        //             el.classList.add('bg-yellow-900', 'bg-opacity-30');
        //         });
                
        //         document.querySelectorAll('.bg-blue-100').forEach(el => {
        //             el.classList.remove('bg-blue-100');
        //             el.classList.add('bg-blue-900', 'bg-opacity-30');
        //         });
                
        //         document.querySelectorAll('.bg-red-100').forEach(el => {
        //             el.classList.remove('bg-red-100');
        //             el.classList.add('bg-red-900', 'bg-opacity-30');
        //         });
                
        //         // Button and active state colors
        //         document.querySelector('.btn-download').classList.add('bg-blue-600');
                
        //         // Input fields
        //         document.querySelectorAll('input[type="search"]').forEach(el => {
        //             el.classList.remove('bg-gray-200');
        //             el.classList.add('bg-gray-700');
        //         });
                
        //         // Sidebar
        //         sidebar.classList.remove('bg-white');
        //         sidebar.classList.add('bg-gray-800', 'border-r', 'border-gray-700');
                
        //         // Adjust active state in sidebar
        //         document.querySelector('#sidebar li.bg-blue-50').classList.remove('bg-blue-50');
        //         document.querySelector('#sidebar li.bg-blue-50, #sidebar li.border-blue-500').classList.add('bg-blue-900', 'bg-opacity-20');
                
        //     } else {
        //         document.body.classList.remove('bg-gray-900');
        //         document.body.classList.add('bg-gray-100');
        //         toggleSwitch.classList.remove('translate-x-6');
                
        //         // Toggle theme icons
        //         darkIcon.classList.add('hidden');
        //         lightIcon.classList.remove('hidden');
                
        //         // Remove dark mode from elements
        //         document.querySelectorAll('.bg-gray-800').forEach(el => {
        //             el.classList.add('bg-white');
        //             el.classList.remove('bg-gray-800');
        //         });
                
        //         document.querySelectorAll('.text-gray-200').forEach(el => {
        //             el.classList.add('text-gray-700');
        //             el.classList.remove('text-gray-200');
        //         });
                
        //         document.querySelectorAll('.text-gray-400').forEach(el => {
        //             el.classList.add('text-gray-500');
        //             el.classList.remove('text-gray-400');
        //         });

        //         // ////////////
        //         // document.querySelectorAll('.container').forEach(el => {
        //         //     // el.classList.remove('text-gray-500');
        //         //     el.classList.remove('text-white');
        //         // });
                
        //         document.querySelectorAll('.bg-gray-700').forEach(el => {
        //             el.classList.add('bg-gray-100');
        //             el.classList.remove('bg-gray-700');
        //         });
                
        //         // Status badges - revert to light mode
        //         document.querySelectorAll('.bg-green-900.bg-opacity-30').forEach(el => {
        //             el.classList.add('bg-green-100');
        //             el.classList.remove('bg-green-900', 'bg-opacity-30');
        //         });
                
        //         document.querySelectorAll('.bg-yellow-900.bg-opacity-30').forEach(el => {
        //             el.classList.add('bg-yellow-100');
        //             el.classList.remove('bg-yellow-900', 'bg-opacity-30');
        //         });
                
        //         document.querySelectorAll('.bg-blue-900.bg-opacity-30').forEach(el => {
        //             el.classList.add('bg-blue-100');
        //             el.classList.remove('bg-blue-900', 'bg-opacity-30');
        //         });
                
        //         document.querySelectorAll('.bg-red-900.bg-opacity-30').forEach(el => {
        //             el.classList.add('bg-red-100');
        //             el.classList.remove('bg-red-900', 'bg-opacity-30');
        //         });
                
        //         // Button colors
        //         document.querySelector('.btn-download').classList.remove('bg-blue-600');
                
        //         // Input fields
        //         document.querySelectorAll('input[type="search"]').forEach(el => {
        //             el.classList.add('bg-gray-200');
        //             el.classList.remove('bg-gray-700');
        //         });
                
        //         // Sidebar
        //         sidebar.classList.add('bg-white');
        //         sidebar.classList.remove('bg-gray-800', 'border-r', 'border-gray-700');
                
        //         // Adjust active state in sidebar
        //         document.querySelector('#sidebar li.bg-blue-900.bg-opacity-20')?.classList.remove('bg-blue-900', 'bg-opacity-20');
        //         document.querySelector('#sidebar li.border-blue-500').classList.add('bg-blue-50');
        //     }
        // });

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