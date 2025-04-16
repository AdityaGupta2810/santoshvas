
<?php
// Start output buffering and session
ob_start();
session_start();

// Use absolute path for includes
require_once 'C:/xampp/htdocs/santoshvas/Ecommerce/actions/function.class.php';
require_once 'C:/xampp/htdocs/santoshvas/Ecommerce/config.php';

// Initialize database and function classes if needed
if (!isset($db)) {
    $db = new Database();
}
if (!isset($fn)) {
    $fn = new Functions();
}

// Error handling
$error_message = isset($_SESSION['error']) ? $_SESSION['error'] : '';
$success_message = isset($_SESSION['success']) ? $_SESSION['success'] : '';

// Clear flash messages
unset($_SESSION['error']);
unset($_SESSION['success']);

// Authentication check
if (!isset($_SESSION['user'])) {
    header('Location: /santoshvas/Ecommerce/user/adminlogin.php');
    exit;
}

// Logout functionality
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    // Use a more secure session destruction method
    $_SESSION = array();
    
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    session_destroy();
    header('Location: /santoshvas/Ecommerce/user/adminlogin.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdminHub Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Existing HTML structure remains the same -->
      <!-- SIDEBAR -->

    <section id="sidebar" class="fixed top-0 left-0 h-full bg-white shadow-md z-50 transition-all duration-300 w-72 ">
        <a href="#" class="brand flex items-center h-16 px-4 text-blue-500 text-2xl font-bold">
            <i class='bx bxs-smile'></i>
            <span class="text ml-2 sidebar-text">AdminHub</span>
        </a>
        <ul class="side-menu top mt-8">
            <li class="bg-blue-50 border-r-4 border-blue-500">
                <a href="/santoshvas/Ecommerce/admin/index.php" class="flex items-center h-12 px-4 text-blue-500">
                    <i class='bx bxs-dashboard text-xl'></i>
                    <span class="text ml-3 sidebar-text">Dashboard</span>
                </a>
            </li>
            <li class="dropdown " >
                <a href="#" class="dropdown-toggle flex items-center justify-between h-12 px-4 text-gray-700 hover:text-blue-500">
                    <div class="flex items-center">
                        <!-- <i class='bx bxs-shopping-bag-alt text-xl'></i> -->
                        <i class="bx bxs-cog text-xl"></i>
                        <span class="text ml-3 sidebar-text">Shop Settings</span>
                    </div>
                    <i class='bx bx-chevron-right transition-transform duration-300'></i>
                </a>
                <ul class="submenu hidden  bg-gray-50 shadow-inner">
                    <li>
                        <a href="/santoshvas/Ecommerce/admin/dashboardpages/shopsettings/size.php" class="flex items-center h-10 pl-5 text-gray-600     hover:text-blue-600 hover:bg-gray-100">
                            <span><i class='bx bxs-circle  hover:text-blue-700  bx-rotate-90 mr-5' ></i>Size</span>
                        </a>
                    </li>
                    <li>
                        <a href="/santoshvas/Ecommerce/admin/dashboardpages/shopsettings/color.php" class="flex items-center h-10 pl-5 text-gray-600   hover:text-blue-600 hover:bg-gray-100">
                            <span><i class='bx bxs-circle  hover:text-blue-700  bx-rotate-90 mr-5' ></i>Color</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center h-10 pl-5 text-gray-600   hover:text-blue-600 hover:bg-gray-100">
                            <span><i class='bx bxs-circle  hover:text-blue-700  bx-rotate-90 mr-5' ></i>Shipping Cost</span>
                        </a>
                    </li>
                    <li>
                        <a href="/santoshvas/Ecommerce/admin/dashboardpages/shopsettings/top-category.php" class="flex items-center h-10 pl-5 text-gray-600   hover:text-blue-600 hover:bg-gray-100">
                            <span><i class='bx bxs-circle  hover:text-blue-700  bx-rotate-90 mr-5' ></i>Top Level Catergory</span>
                        </a>
                    </li>

                    <li>
                        <a href="/santoshvas/Ecommerce/admin/dashboardpages/shopsettings/mid-category.php" class="flex items-center h-10 pl-5 text-gray-600   hover:text-blue-600 hover:bg-gray-100">
                            <span><i class='bx bxs-circle  hover:text-blue-700  bx-rotate-90 mr-5' ></i>Mid Level Category</span>
                        </a>
                    </li>
                    <li >
                        <a href="/santoshvas/Ecommerce/admin/dashboardpages/shopsettings/end-category.php" class="flex items-center h-10 pl-5 text-gray-600   hover:text-blue-600 hover:bg-gray-100">
                            <span><i class='bx bxs-circle  hover:text-blue-700  bx-rotate-90 mr-5' ></i>End Level Catergory</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="/santoshvas/Ecommerce/admin/dashboardpages/productmanagement/products.php" class="flex items-center h-12 px-4 text-gray-700 hover:text-blue-500">
                    <i class='bx bxs-doughnut-chart text-xl'></i>
                    <span class="text ml-3 sidebar-text ">Product Management</span>
                </a>
            </li>
            <li>
                <a href="/santoshvas/Ecommerce/admin/dashboardpages/order.php" class="flex items-center h-12 px-4 text-gray-700 hover:text-blue-500">
                    <i class='bx bxs-doughnut-chart text-xl'></i>
                    <span class="text ml-3 sidebar-text ">Order Management</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center h-12 px-4 text-gray-700 hover:text-blue-500">
                    <i class='bx bxs-message-dots text-xl'></i>
                    <span class="text ml-3 sidebar-text">Messages</span>
                </a>
            </li>
            <!-- <li>
                <a href="#" class="flex items-center h-12 px-4 text-gray-700 hover:text-blue-500">
                    <i class='bx bxs-group text-xl'></i>
                    <span class="text ml-3 sidebar-text">Team</span>
                </a>
            </li> -->
        </ul>
        <ul class="side-menu mt-6">
            <!-- <li>
                <a href="#" class="flex items-center h-12 px-4 text-gray-700 hover:text-blue-500">
                    <i class='bx bxs-cog text-xl'></i>
                    <span class="text ml-3 sidebar-text">Settings</span>
                </a>
            </li> -->
            <li>
                <a href="?logout=true" class="logout flex items-center h-12 px-4 text-red-500 hover:text-red-700">
                    <i class='bx bxs-log-out-circle text-xl'></i>
                    <span class="text ml-3 sidebar-text">Logout</span>
                </a>
            </li>
        </ul>
    </section>








    <!-- CONTENT -->
    <section id="content" class="ml-72 transition-all duration-300">
        <!-- NAVBAR -->

        <!-- 1. First, fix the search toggle icon in the navbar -->
<nav class="h-16 bg-white px-6 flex items-center justify-evenly  sticky top-0 z-40 shadow-sm">
    <i class='bx bx-menu text-2xl text-gray-700 cursor-pointer' id="menu-btn"></i>
    <a href="../../index.php" class="nav-link ml-4 text-gray-700 hover:text-blue-500">Home</a>
    
    <!-- Modified search toggle: hidden on md screens and larger -->
    <!-- <i class='bx bx-search text-2xl text-gray-700 cursor-pointer ml-auto hidden md:hidden lg:hidden xl:hidden' id="search-toggle"></i> -->
    
    <!-- Search form: visible only on md screens and larger -->
    <form action="#" class="search-form hidden md:flex ml-auto transition-all duration-300">
        <div class="form-input flex items-center h-9">
            <input type="search" placeholder="Search..." class="w-max-64 h-full px-4 bg-gray-200 rounded-l-full outline-none">
            <button type="submit" class="w-9 h-full bg-blue-500 text-white rounded-r-full flex items-center justify-center">
                <i class='bx bx-search'></i>
            </button>
           </div>
               </form>
            <!-- <div class="ml-4 flex items-center">
                <input type="checkbox" id="switch-mode" hidden>
                <label for="switch-mode" class="bg-gray-300 w-12 h-6 rounded-full relative cursor-pointer flex items-center p-1">
                    <i class='bx bx-sun text-yellow-500 dark-icon hidden text-sm'></i>
                    <span class="block w-4 h-4 bg-white rounded-full transform transition-transform duration-300" id="toggle-switch"></span>
                    <i class='bx bx-moon text-blue-800 light-icon text-sm ml-auto'></i>
                </label>
            </div> -->
            <a href="#" class="notification mx-4 text-gray-700 relative">
                <i class='bx bxs-bell text-2xl'></i>
                <span class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">8</span>
            </a>
           <div class="relative">
            <a href="#" id="profile-toggle" class="profile relative">
                <img src="https://via.placeholder.com/36" class="w-9 h-9 rounded-full object-cover border-2 border-blue-500 cursor-pointer">
            </a>
            
            <!-- Profile Dropdown -->
            <div id="profile-dropdown" class="profile-dropdown absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg hidden">
                <ul class="py-1">
                    <li>
                        <a href="/santoshvas/Ecommerce/admin/dashboardpages/profile-edit.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100 flex items-center">
                            <i class='bx bxs-user mr-2'></i> Edit Profile
                        </a>
                    </li>
                    <li>
                        <a href="?logout=true" class="block px-4 py-2 text-red-600 hover:bg-gray-100 flex items-center">
                            <i class='bx bxs-log-out-circle mr-2'></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        </nav>
        <!-- NAVBAR -->

   

        <!-- Mobile Search - Expandable -->
        <div class="mobile-search  bg-white p-3 shadow-md md:hidden">
            <form action="#" class="w-full">
                <div class="form-input flex items-center h-9 w-full">
                    <input type="search" placeholder="Search..." class="flex-grow h-full px-4 bg-gray-200 rounded-l-full outline-none">
                    <button type="submit" class="w-9 h-full bg-blue-500 text-white rounded-r-full flex items-center justify-center">
                        <i class='bx bx-search'></i>
                    </button>
                </div>
            </form>
        </div>
    
    <script>
   document.addEventListener('DOMContentLoaded', function() {
    const profileToggle = document.getElementById('profile-toggle');
    const profileDropdown = document.getElementById('profile-dropdown');

    // Toggle dropdown function
    function toggleDropdown(event) {
        event.stopPropagation();
        profileDropdown.classList.toggle('hidden');
    }

    // Close dropdown when clicking outside
    function closeDropdown() {
        profileDropdown.classList.add('hidden');
    }

    // Initial state: hide dropdown
    profileDropdown.classList.add('hidden');

    // Add click event listener to profile toggle
    profileToggle.addEventListener('click', toggleDropdown);

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!profileToggle.contains(event.target) && !profileDropdown.contains(event.target)) {
            closeDropdown();
        }
    });

    // Close dropdown on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDropdown();
        }
    });
});
    </script>
 <main class="p-6">









