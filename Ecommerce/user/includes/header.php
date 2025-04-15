<?php
require "C:/xampp/htdocs/santoshvas/Ecommerce/actions/function.class.php";
include_once "C:/xampp/htdocs/santoshvas/Ecommerce/db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=@$title?></title>
    
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <style>
        .fancy-header {
            background: linear-gradient(45deg, #1a202c, #2d3748);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .nav-item:hover {
            color: #4a90e2;
            transform: translateX(5px);
            transition: all 0.3s ease;
        }
        .search-bar {
            background: white;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .search-button {
            background: linear-gradient(45deg, #4a90e2, #63b3ed);
            transition: all 0.3s ease;
        }
        .search-button:hover {
            background: linear-gradient(45deg, #357abd, #4a90e2);
        }
    </style>
</head> 
<body class="bg-gray-100 font-sans">
    <!-- Sidebar for Mobile (Hidden on Larger Screens) -->
    <div id="sidebar" class="fixed inset-y-0 left-0 bg-gray-900 text-white w-64 transform -translate-x-full sm:hidden transition-transform duration-300 ease-in-out z-50 shadow-lg">
        <div class="p-6 border-b border-gray-700">
            <h1 class="text-2xl font-bold text-indigo-400">Santosh Vastralay</h1>
        </div>
        <nav class="mt-6">
            <a href="/santoshvas/Ecommerce/index.php" class="block py-3 px-6 hover:bg-gray-800 hover:text-indigo-300 transition-colors duration-200">Home</a>
            <a href="/santoshvas/Ecommerce/Home/landingpage.php" class="block py-3 px-6 hover:bg-gray-800 hover:text-indigo-300 transition-colors duration-200">Shop</a>
            <a href="/santoshvas/Ecommerce/Home/about.html" class="block py-3 px-6 hover:bg-gray-800 hover:text-indigo-300 transition-colors duration-200">About us</a>
            <a href="/santoshvas/Ecommerce/Home/contact.php" class="block py-3 px-6 hover:bg-gray-800 hover:text-indigo-300 transition-colors duration-200">Contact</a>
            <a href="/santoshvas/Ecommerce/Home/cart.php" class="block py-3 px-6 hover:bg-gray-800 hover:text-indigo-300 transition-colors duration-200">Cart</a>
            <a href="/santoshvas/Ecommerce/user/adminlogin.php" class="block py-3 px-6 hover:bg-gray-800 hover:text-indigo-300 transition-colors duration-200">Admin</a>
            <div class="relative">
                <button id="userMenuButtonMobile" class="w-full text-left py-3 px-6 hover:bg-gray-800 focus:outline-none">
                    <span class="flex items-center"><i class='bx bx-user mr-2'></i> User</span>
                </button>
                <ul id="userMenuDropdownMobile" class="bg-gray-800 text-white rounded-lg shadow-lg hidden mt-1">
                    <li><a href="/santoshvas/Ecommerce/user/login.php" class="block px-6 py-2 hover:bg-gray-700"><i class='bx bx-log-in mr-2'></i> Login</a></li>
                    <li><a href="/santoshvas/Ecommerce/user/reg.php" class="block px-6 py-2 hover:bg-gray-700"><i class='bx bx-user-plus mr-2'></i> Register</a></li>
                    <li><a href="#" class="block px-6 py-2 hover:bg-gray-700"><i class='bx bx-box mr-2'></i> My Orders</a></li>
                    <li><a href="/santoshvas/Ecommerce/user/userprofile.php" class="block px-6 py-2 hover:bg-gray-700"><i class='bx bx-user-circle mr-2'></i> Profile</a></li>                 
                    <li><a href="/santoshvas/Ecommerce/actions/logoutaction.php" class="block px-6 py-2 hover:bg-gray-700"><i class='bx bx-log-out mr-2'></i> Logout</a></li>
                </ul>
            </div>
            <!-- Search Bar for Mobile -->
            <!-- <div class="p-6">
                <div class="search-bar">
                    <input type="text" placeholder="Search..." class="w-full p-3 bg-gray-800 text-white border-none focus:outline-none">
                    <button class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-3 rounded mt-2 hover:from-indigo-600 hover:to-purple-700 transition-all duration-300" aria-label="Search">Search</button>
                </div>
            </div> -->
        </nav>
    </div>

    <!-- Header Section -->
    <header class="fancy-header text-white p-4 flex justify-between items-center shrink-0">
        <!-- Logo and Brand Name -->
        <div class="flex items-center">
            <img src="/santoshvas/Ecommerce/Home/images/logo.png" alt="Santosh Vastralay Logo" class="h-12 rounded-full shadow-md">
          
        </div>

        <nav class="flex sm:hidden items-center gap-6 text-lg">
            <a href="/santoshvas/Ecommerce/index.php" class="nav-item font-semibold">Home</a>
            <a href="/santoshvas/Ecommerce/Home/landingpage.php" class="nav-item font-semibold">Shop</a>
            <a href="/santoshvas/Ecommerce/Home/about.html" class="nav-item font-semibold">About us</a>
            <a href="/santoshvas/Ecommerce/Home/contact.php" class="nav-item font-semibold">Contact</a>
        </nav>
    
        <!-- Mobile Menu Button (Hamburger Icon) -->
        <button id="sidebarToggle" class="sm:hidden text-white focus:outline-none p-2 hover:bg-gray-700 rounded">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
            </svg>
        </button>
    
        <!-- Navigation Menu for Larger Screens -->
        <nav class="hidden sm:flex items-center gap-6 text-lg">
            <a href="/santoshvas/Ecommerce/index.php" class="nav-item  font-semibold  ">Home</a>
            <a href="/santoshvas/Ecommerce/Home/landingpage.php" class="nav-item font-semibold">Shop</a>
            <a href="/santoshvas/Ecommerce/Home/about.html" class="nav-item font-semibold">About us</a>
            <a href="/santoshvas/Ecommerce/Home/contact.php" class="nav-item font-semibold">Contact</a>
        </nav>
    
        <!-- Search Bar for Larger Screens -->
        <!-- <div class="hidden md:flex items-center">
            <div class="search-bar flex">
                <input type="text" placeholder="Search..." class="p-3 rounded-l-full border-none focus:outline-none">
                <button class="search-button p-3 rounded-r-full" aria-label="Search">Search</button>
            </div>
        </div> -->
    
        <!-- User Dropdown and Cart Icon for Larger Screens -->
        <div class="hidden sm:flex items-center space-x-8">
          
        <a href="/santoshvas/Ecommerce/Home/cart.php" class="text-white transition relative">
                    <i class="fas fa-shopping-cart nav-item"></i>
                    <span class="absolute -top-2 -right-2 bg-indigo-600  text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                </a> 
            <div class="relative">
                <button id="userMenuButton" class=" font-semibold focus:outline-none flex items-center">
                <a href="#" class="text-white nav-item transition"><i class="fas fa-user"></i></a>
                </button>
                <ul id="userMenuDropdown" class="absolute right-0 mt-2 bg-white text-gray-800 rounded-lg shadow-lg border border-gray-200 hidden z-10">
                    <li><a href="/santoshvas/Ecommerce/user/login.php" class="block px-6 py-2 hover:bg-gray-100 flex items-center"><i class='bx bx-log-in mr-2'></i> Login</a></li>
                    <li><a href="/santoshvas/Ecommerce/user/reg.php" class="block px-6 py-2 hover:bg-gray-100 flex items-center"><i class='bx bx-user-plus mr-2'></i> Register</a></li>
                    <li><a href="#" class="block px-6 py-2 hover:bg-gray-100 flex items-center"><i class='bx bx-box mr-2'></i> My Orders</a></li>
                    <li><a href="/santoshvas/Ecommerce/user/userprofile.php" class="block px-6 py-2 hover:bg-gray-100 flex items-center"><i class='bx bx-user-circle mr-2'></i> Profile</a></li>
                    <li><a href="/santoshvas/Ecommerce/actions/logoutaction.php" class="block px-6 py-2 hover:bg-gray-100 flex items-center"><i class='bx bx-log-out mr-2'></i> Logout</a></li>
                </ul>
            </div>
            <a href="/santoshvas/Ecommerce/user/adminlogin.php" class="nav-item font-semibold">Admin</a>
        </div>
    </header>

    <!-- <div class="bg-white p-4 shadow-md md:hidden flex justify-center px-6">
        <form action="#" class="w-full max-w-md">
            <div class="form-input flex items-center h-12 w-full">
                <input type="search" placeholder="Search..." class="flex-grow h-full px-4 bg-gray-200 rounded-l-full outline-none">
                <button type="submit" class="w-12 h-full bg-indigo-500 text-white rounded-r-full flex items-center justify-center hover:bg-indigo-600 transition-colors duration-300">
                    <i class='bx bx-search'></i>
                </button>
            </div>
        </form>
    </div> -->

    <script>
        // Toggle sidebar for small screens
        const sidebar = document.getElementById("sidebar");
        const sidebarToggle = document.getElementById("sidebarToggle");

        sidebarToggle.addEventListener("click", () => {
            sidebar.classList.toggle("-translate-x-full");
        });

        // Toggle user dropdown on hover for larger screens
        const userMenuButton = document.getElementById("userMenuButton");
        const userMenuDropdown = document.getElementById("userMenuDropdown");

        userMenuButton.addEventListener("mouseenter", () => {
            userMenuDropdown.classList.remove("hidden");
        });

        userMenuDropdown.addEventListener("mouseenter", () => {
            userMenuDropdown.classList.remove("hidden");
        });

        userMenuButton.addEventListener("mouseleave", () => {
            setTimeout(() => {
                if (!userMenuDropdown.matches(":hover")) {
                    userMenuDropdown.classList.add("hidden");
                }
            }, 200);
        });

        userMenuDropdown.addEventListener("mouseleave", () => {
            userMenuDropdown.classList.add("hidden");
        });

        // Toggle user dropdown on click for mobile
        const userMenuButtonMobile = document.getElementById("userMenuButtonMobile");
        const userMenuDropdownMobile = document.getElementById("userMenuDropdownMobile");

        userMenuButtonMobile.addEventListener("click", () => {
            userMenuDropdownMobile.classList.toggle("hidden");
        });
    </script>