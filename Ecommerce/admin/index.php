<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdminHub Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- SIDEBAR -->
    <section id="sidebar" class="fixed top-0 left-0 h-full bg-white shadow-md z-50 transition-all duration-300 w-72">
        <a href="#" class="brand flex items-center h-16 px-6 text-blue-500 text-2xl font-bold">
            <i class='bx bxs-smile'></i>
            <span class="text ml-2 sidebar-text">AdminHub</span>
        </a>
        <ul class="side-menu top mt-8">
            <li class="bg-blue-50 border-r-4 border-blue-500">
                <a href="#" class="flex items-center h-12 px-6 text-blue-500">
                    <i class='bx bxs-dashboard text-xl'></i>
                    <span class="text ml-3 sidebar-text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center h-12 px-6 text-gray-700 hover:text-blue-500">
                    <i class='bx bxs-shopping-bag-alt text-xl'></i>
                    <span class="text ml-3 sidebar-text">My Store</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center h-12 px-6 text-gray-700 hover:text-blue-500">
                    <i class='bx bxs-doughnut-chart text-xl'></i>
                    <span class="text ml-3 sidebar-text">Analytics</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center h-12 px-6 text-gray-700 hover:text-blue-500">
                    <i class='bx bxs-message-dots text-xl'></i>
                    <span class="text ml-3 sidebar-text">Messages</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center h-12 px-6 text-gray-700 hover:text-blue-500">
                    <i class='bx bxs-group text-xl'></i>
                    <span class="text ml-3 sidebar-text">Team</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu mt-6">
            <li>
                <a href="#" class="flex items-center h-12 px-6 text-gray-700 hover:text-blue-500">
                    <i class='bx bxs-cog text-xl'></i>
                    <span class="text ml-3 sidebar-text">Settings</span>
                </a>
            </li>
            <li>
                <a href="#" class="logout flex items-center h-12 px-6 text-red-500 hover:text-red-700">
                    <i class='bx bxs-log-out-circle text-xl'></i>
                    <span class="text ml-3 sidebar-text">Logout</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- SIDEBAR -->

    <!-- CONTENT -->
    <section id="content" class="ml-72 transition-all duration-300">
        <!-- NAVBAR -->
        <nav class="h-16 bg-white px-6 flex items-center sticky top-0 z-40 shadow-sm">
            <i class='bx bx-menu text-2xl text-gray-700 cursor-pointer' id="menu-btn"></i>
            <a href="#" class="nav-link ml-4 text-gray-700 hover:text-blue-500">Categories</a>
            
            <!-- Search toggle for small screens -->
            <i class='bx bx-search text-2xl text-gray-700 cursor-pointer ml-auto hidden lg:hidden' id="search-toggle"></i>
            
            <!-- Search form - hidden on small screens by default -->
            <form action="#" class="search-form hidden md:flex ml-auto transition-all duration-300">
                <div class="form-input flex items-center h-9">
                    <input type="search" placeholder="Search..." class="w-64 h-full px-4 bg-gray-200 rounded-l-full outline-none">
                    <button type="submit" class="w-9 h-full bg-blue-500 text-white rounded-r-full flex items-center justify-center">
                        <i class='bx bx-search'></i>
                    </button>
                </div>
            </form>
            
            <div class="ml-4 flex items-center">
                <input type="checkbox" id="switch-mode" hidden>
                <label for="switch-mode" class="bg-gray-300 w-12 h-6 rounded-full relative cursor-pointer flex items-center p-1">
                    <i class='bx bx-sun text-yellow-500 dark-icon hidden text-sm'></i>
                    <span class="block w-4 h-4 bg-white rounded-full transform transition-transform duration-300" id="toggle-switch"></span>
                    <i class='bx bx-moon text-blue-800 light-icon text-sm ml-auto'></i>
                </label>
            </div>
            <a href="#" class="notification ml-4 text-gray-700 relative">
                <i class='bx bxs-bell text-2xl'></i>
                <span class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">8</span>
            </a>
            <a href="#" class="profile ml-4">
                <img src="https://via.placeholder.com/36" class="w-9 h-9 rounded-full object-cover border-2 border-blue-500">
            </a>
        </nav>
        <!-- NAVBAR -->

        <!-- Mobile Search - Expandable -->
        <div class="mobile-search hidden bg-white p-3 shadow-md md:hidden">
            <form action="#" class="w-full">
                <div class="form-input flex items-center h-9 w-full">
                    <input type="search" placeholder="Search..." class="flex-grow h-full px-4 bg-gray-200 rounded-l-full outline-none">
                    <button type="submit" class="w-9 h-full bg-blue-500 text-white rounded-r-full flex items-center justify-center">
                        <i class='bx bx-search'></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- MAIN -->
        <main class="p-6">
            <div class="head-title flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <div class="left">
                    <h1 class="text-3xl font-semibold text-gray-700">Dashboard</h1>
                    <ul class="breadcrumb flex items-center mt-2">
                        <li><a href="#" class="text-gray-500">Dashboard</a></li>
                        <li class="mx-2 text-gray-500"><i class='bx bx-chevron-right'></i></li>
                        <li><a href="#" class="text-blue-500">Home</a></li>
                    </ul>
                </div>
                <a href="#" class="btn-download h-10 px-4 bg-blue-500 text-white rounded-full flex items-center mt-4 md:mt-0 w-max">
                    <i class='bx bxs-cloud-download mr-2'></i>
                    <span>Download PDF</span>
                </a>
            </div>

            <ul class="box-info grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <li class="p-6 bg-white rounded-xl flex items-center shadow-sm">
                    <i class='bx bxs-calendar-check text-4xl text-blue-500'></i>
                    <div class="ml-4">
                        <h3 class="text-2xl font-semibold text-gray-700">1,020</h3>
                        <p class="text-gray-500">New Orders</p>
                    </div>
                </li>
                <li class="p-6 bg-white rounded-xl flex items-center shadow-sm">
                    <i class='bx bxs-group text-4xl text-yellow-500'></i>
                    <div class="ml-4">
                        <h3 class="text-2xl font-semibold text-gray-700">2,834</h3>
                        <p class="text-gray-500">Visitors</p>
                    </div>
                </li>
                <li class="p-6 bg-white rounded-xl flex items-center shadow-sm">
                    <i class='bx bxs-dollar-circle text-4xl text-green-500'></i>
                    <div class="ml-4">
                        <h3 class="text-2xl font-semibold text-gray-700">$2,543</h3>
                        <p class="text-gray-500">Total Sales</p>
                    </div>
                </li>
            </ul>

            <div class="table-data grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="order bg-white rounded-xl p-6 shadow-sm">
                    <div class="head flex items-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-700 mr-auto">Recent Orders</h3>
                        <i class='bx bx-search text-xl text-gray-700 cursor-pointer'></i>
                        <i class='bx bx-filter text-xl text-gray-700 cursor-pointer ml-4'></i>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="pb-3 text-left text-gray-500">User</th>
                                    <th class="pb-3 text-left text-gray-500">Date Order</th>
                                    <th class="pb-3 text-left text-gray-500">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="hover:bg-gray-100">
                                    <td class="py-3 flex items-center">
                                        <img src="https://via.placeholder.com/36" class="w-9 h-9 rounded-full object-cover">
                                        <p class="ml-2">John Doe</p>
                                    </td>
                                    <td class="py-3">01-10-2024</td>
                                    <td class="py-3">
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Completed</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-100">
                                    <td class="py-3 flex items-center">
                                        <img src="https://via.placeholder.com/36" class="w-9 h-9 rounded-full object-cover">
                                        <p class="ml-2">Jane Smith</p>
                                    </td>
                                    <td class="py-3">01-15-2024</td>
                                    <td class="py-3">
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">Pending</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-100">
                                    <td class="py-3 flex items-center">
                                        <img src="https://via.placeholder.com/36" class="w-9 h-9 rounded-full object-cover">
                                        <p class="ml-2">Robert Johnson</p>
                                    </td>
                                    <td class="py-3">01-18-2024</td>
                                    <td class="py-3">
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">Processing</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="todo bg-white rounded-xl p-6 shadow-sm">
                    <div class="head flex items-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-700 mr-auto">Todos</h3>
                        <i class='bx bx-plus text-xl text-gray-700 cursor-pointer'></i>
                        <i class='bx bx-filter text-xl text-gray-700 cursor-pointer ml-4'></i>
                    </div>
                    <ul class="todo-list">
                        <li class="mb-4 bg-gray-100 rounded-lg p-4 flex items-center justify-between border-l-4 border-blue-500">
                            <p>Update product pricing</p>
                            <i class='bx bx-dots-vertical-rounded text-xl text-gray-700 cursor-pointer'></i>
                        </li>
                        <li class="mb-4 bg-gray-100 rounded-lg p-4 flex items-center justify-between border-l-4 border-yellow-500">
                            <p>Respond to customer inquiries</p>
                            <i class='bx bx-dots-vertical-rounded text-xl text-gray-700 cursor-pointer'></i>
                        </li>
                        <li class="mb-4 bg-gray-100 rounded-lg p-4 flex items-center justify-between border-l-4 border-green-500">
                            <p>Prepare monthly sales report</p>
                            <i class='bx bx-dots-vertical-rounded text-xl text-gray-700 cursor-pointer'></i>
                        </li>
                        <li class="mb-4 bg-gray-100 rounded-lg p-4 flex items-center justify-between border-l-4 border-red-500">
                            <p>Review inventory levels</p>
                            <i class='bx bx-dots-vertical-rounded text-xl text-gray-700 cursor-pointer'></i>
                        </li>
                    </ul>
                </div>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <script>
        // Select DOM elements
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');
        const menuBtn = document.getElementById('menu-btn');
        const switchMode = document.getElementById('switch-mode');
        const toggleSwitch = document.getElementById('toggle-switch');
        const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li');
        const sidebarText = document.querySelectorAll('.sidebar-text');
        const searchToggle = document.getElementById('search-toggle');
        const mobileSearch = document.querySelector('.mobile-search');
        const darkIcon = document.querySelector('.dark-icon');
        const lightIcon = document.querySelector('.light-icon');

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
        searchToggle.addEventListener('click', function() {
            mobileSearch.classList.toggle('hidden');
        });

        // Toggle dark mode
        switchMode.addEventListener('change', function() {
            if (this.checked) {
                document.body.classList.add('bg-gray-900');
                document.body.classList.remove('bg-gray-100');
                toggleSwitch.classList.add('translate-x-6');
                
                // Toggle theme icons
                darkIcon.classList.remove('hidden');
                lightIcon.classList.add('hidden');
                
                // Apply dark mode to elements
                document.querySelectorAll('.bg-white').forEach(el => {
                    el.classList.remove('bg-white');
                    el.classList.add('bg-gray-800');
                });
                
                document.querySelectorAll('.text-gray-700').forEach(el => {
                    el.classList.remove('text-gray-700');
                    el.classList.add('text-gray-200');
                });
                
                document.querySelectorAll('.text-gray-500').forEach(el => {
                    el.classList.remove('text-gray-500');
                    el.classList.add('text-gray-400');
                });
                
                document.querySelectorAll('.bg-gray-100').forEach(el => {
                    el.classList.remove('bg-gray-100');
                    el.classList.add('bg-gray-700');
                });
                
                // Status badges - adjust for dark mode
                document.querySelectorAll('.bg-green-100').forEach(el => {
                    el.classList.remove('bg-green-100');
                    el.classList.add('bg-green-900', 'bg-opacity-30');
                });
                
                document.querySelectorAll('.bg-yellow-100').forEach(el => {
                    el.classList.remove('bg-yellow-100');
                    el.classList.add('bg-yellow-900', 'bg-opacity-30');
                });
                
                document.querySelectorAll('.bg-blue-100').forEach(el => {
                    el.classList.remove('bg-blue-100');
                    el.classList.add('bg-blue-900', 'bg-opacity-30');
                });
                
                document.querySelectorAll('.bg-red-100').forEach(el => {
                    el.classList.remove('bg-red-100');
                    el.classList.add('bg-red-900', 'bg-opacity-30');
                });
                
                // Button and active state colors
                document.querySelector('.btn-download').classList.add('bg-blue-600');
                
                // Input fields
                document.querySelectorAll('input[type="search"]').forEach(el => {
                    el.classList.remove('bg-gray-200');
                    el.classList.add('bg-gray-700');
                });
                
                // Sidebar
                sidebar.classList.remove('bg-white');
                sidebar.classList.add('bg-gray-800', 'border-r', 'border-gray-700');
                
                // Adjust active state in sidebar
                document.querySelector('#sidebar li.bg-blue-50').classList.remove('bg-blue-50');
                document.querySelector('#sidebar li.bg-blue-50, #sidebar li.border-blue-500').classList.add('bg-blue-900', 'bg-opacity-20');
                
            } else {
                document.body.classList.remove('bg-gray-900');
                document.body.classList.add('bg-gray-100');
                toggleSwitch.classList.remove('translate-x-6');
                
                // Toggle theme icons
                darkIcon.classList.add('hidden');
                lightIcon.classList.remove('hidden');
                
                // Remove dark mode from elements
                document.querySelectorAll('.bg-gray-800').forEach(el => {
                    el.classList.add('bg-white');
                    el.classList.remove('bg-gray-800');
                });
                
                document.querySelectorAll('.text-gray-200').forEach(el => {
                    el.classList.add('text-gray-700');
                    el.classList.remove('text-gray-200');
                });
                
                document.querySelectorAll('.text-gray-400').forEach(el => {
                    el.classList.add('text-gray-500');
                    el.classList.remove('text-gray-400');
                });
                
                document.querySelectorAll('.bg-gray-700').forEach(el => {
                    el.classList.add('bg-gray-100');
                    el.classList.remove('bg-gray-700');
                });
                
                // Status badges - revert to light mode
                document.querySelectorAll('.bg-green-900.bg-opacity-30').forEach(el => {
                    el.classList.add('bg-green-100');
                    el.classList.remove('bg-green-900', 'bg-opacity-30');
                });
                
                document.querySelectorAll('.bg-yellow-900.bg-opacity-30').forEach(el => {
                    el.classList.add('bg-yellow-100');
                    el.classList.remove('bg-yellow-900', 'bg-opacity-30');
                });
                
                document.querySelectorAll('.bg-blue-900.bg-opacity-30').forEach(el => {
                    el.classList.add('bg-blue-100');
                    el.classList.remove('bg-blue-900', 'bg-opacity-30');
                });
                
                document.querySelectorAll('.bg-red-900.bg-opacity-30').forEach(el => {
                    el.classList.add('bg-red-100');
                    el.classList.remove('bg-red-900', 'bg-opacity-30');
                });
                
                // Button colors
                document.querySelector('.btn-download').classList.remove('bg-blue-600');
                
                // Input fields
                document.querySelectorAll('input[type="search"]').forEach(el => {
                    el.classList.add('bg-gray-200');
                    el.classList.remove('bg-gray-700');
                });
                
                // Sidebar
                sidebar.classList.add('bg-white');
                sidebar.classList.remove('bg-gray-800', 'border-r', 'border-gray-700');
                
                // Adjust active state in sidebar
                document.querySelector('#sidebar li.bg-blue-900.bg-opacity-20')?.classList.remove('bg-blue-900', 'bg-opacity-20');
                document.querySelector('#sidebar li.border-blue-500').classList.add('bg-blue-50');
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
        function handleResize() {
            if (window.innerWidth < 768) {
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
                
                // Hide desktop search, ensure toggle is visible
                document.querySelector('.search-form').classList.add('hidden');
                document.getElementById('search-toggle').classList.remove('hidden');
                
            } else if (!sidebar.classList.contains('collapsed')) {
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
                
                // Show desktop search
                document.querySelector('.search-form').classList.remove('hidden');
                
                // Ensure mobile search is hidden
                mobileSearch.classList.add('hidden');
            }
        }

        // Check on load and resize
        window.addEventListener('resize', handleResize);
        
        // Initial check
        handleResize();
    </script>
</body>
</html>