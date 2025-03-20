<?php
require "C:/xampp/htdocs/santoshvas/Ecommerce/assets/class/function.class.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=@$title?></title>       <!-- we use @ if title variable is not passed then it will show not throw error  -->
    
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/santoshvas/Ecommerce/output.css"/>
</head> 
<body class="bg-gray-100 ">
    <!-- Sidebar for Mobile (Hidden on Larger Screens) -->
    <div id="sidebar" class="fixed inset-y-0 left-0  bg-gray-800 text-white w-64 transform -translate-x-full sm:hidden transition-transform duration-200 ease-in-out z-50">
        <div class="p-4">
            <h1 class="text-xl  font-bold">Santosh Vastralay</h1>
        </div>
        <nav class=" mt-4">
            <a href="/santoshvas/Ecommerce/index.php" class="block py-2 px-4 hover:bg-gray-700">Home</a>
            <a href="#" class="block py-2 px-4 hover:bg-gray-700">Women</a>
            <a href="#" class="block py-2 px-4 hover:bg-gray-700">Men</a>
            <a href="#" class="block py-2 px-4 hover:bg-gray-700">Others</a>
            <a href="#" class="block py-2 px-4 hover:bg-gray-700">Cart</a>
            <a href="/santoshvas/Ecommerce/admin/index.php" class="block py-2 px-4 hover:bg-gray-700">Admin</a>
            <div class="relative">
                <button id="userMenuButtonMobile" class="w-full text-left py-2 px-4 hover:bg-gray-700 focus:outline-none">
                    User
                </button>
                <ul id="userMenuDropdownMobile" class="bg-gray-700 text-white rounded-lg hidden">
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-600"><a href="/santoshvas/Ecommerce/user/login.php">Login</a>/<a href="/santoshvas/Ecommerce/user/reg.php">Register</a></a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-600">My Orders</a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-600">Profile</a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-600">Settings</a></li>
                    <li><a href="/santoshvas/Ecommerce/actions/logoutaction.php" class="block px-4 py-2 hover:bg-gray-600">Logout</a></li>
                </ul>
            </div>
            <!-- Search Bar for Mobile -->
            <div class="p-4">
                <input type="text" placeholder="Search..." class="w-auto p-2 bg-white rounded border border-gray-500 text-black">
                <button class="w-auto bg-gradient-to-r from-blue-500 via-cyan-600 to-purple-500 text-white p-2 rounded mt-2 hover:bg-blue-700" aria-label="Search">Search</button>
            </div>
        </nav>
    </div>


    <!-- Header Section -->
    <header class="bg-gray-400 text-gray-800 p-4 flex   justify-between items-center shrink-1 lg:shrink-0 gap-4">
        <!-- Logo and Brand Name -->
        <div class="flex items-center">
            <img src="/santoshvas/Ecommerce/Home/images/logo.png" alt="Santosh Vastralay Logo" class="h-12">
            <h1 class="text-xl md:text-2xl font-bold ml-2 ">Santosh Vastralay</h1>
        </div>
    
        <!-- Mobile Menu Button (Hamburger Icon) -->
        <button id="sidebarToggle" class="sm:hidden text-gray-800 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
            </svg>
        </button>
    
        <!-- Navigation Menu for Larger Screens -->
        <nav class="hidden sm:flex gap-5 text-xl p-4">
            <a href="#" class="hover:text-blue-600 font-semibold">Home</a>
            <a href="#" class="hover:text-blue-600 font-semibold">Women</a>
            <a href="#" class="hover:text-blue-600 font-semibold">Men</a>
            <a href="#" class="hover:text-blue-600 font-semibold">Others</a>
        </nav>
    
        <!-- Search Bar for Larger Screens -->
        <div class="hidden md:flex items-center">
            <input type="text" placeholder="Search..." class="p-2 rounded border border-gray-500">
            <button class="bg-blue-600 text-white p-2 rounded ml-2 hover:bg-blue-700" aria-label="Search">Search</button>
        </div>
    
        <!-- User Dropdown and Cart Icon for Larger Screens -->
        <div class="hidden sm:flex items-center space-x-4">
            <a href="#" class="hover:text-blue-600 font-bold" aria-label="Cart">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </a>
            <div class="relative">
                <button id="userMenuButton" class="hover:text-blue-600 text-xl font-semibold focus:outline-none">
                    User
                </button>
                <ul id="userMenuDropdown" class="absolute right-0 mt-2  bg-white p-4  text-gray-800 rounded-lg shadow-lg border border-gray-200 hidden z-10 ">
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-100"> <a href="user/login.php"> Login</a>/<a href="user/reg.php">Register</a></a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">My Orders</a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">Profile</a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">Settings</a></li>
                    <li><a href="/santoshvas/Ecommerce/actions/logoutaction.php" class="block px-4 py-2 hover:bg-gray-100">Logout</a></li>
                </ul>
            </div>
            <a href="/santoshvas/Ecommerce/admin/index.php" class="hover:text-blue-600 text-xl font-semibold">Admin</a>
        </div>
    </header>
    <div class=" bg-white p-3 shadow-md md:hidden flex justify-center px-10">
            <form action="#" class="w-full">
                <div class="form-input flex items-center h-9 w-full">
                    <input type="search" placeholder="Search..." class="flex-grow h-full px-4 bg-gray-200 rounded-l-full outline-none">
                    <button type="submit" class="w-9 h-full bg-blue-500 text-white rounded-r-full flex items-center justify-center">
                        <i class='bx bx-search'></i>
                    </button>
                </div>
            </form>
        </div>