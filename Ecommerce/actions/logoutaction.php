<?php
require '../assets/class/database.class.php';
require '../assets/class/function.class.php';

session_start();

// Set the logged-out message in the session

// $_SESSION['logout_message'] = 'Logged out Successfully';
$fn->setAlert('Logged out Successfully');
// Destroy the session
session_destroy();

// Redirect to the login page

// header('Location: ../user/login.php');
$fn->redirect('../user/login.php');
exit();




