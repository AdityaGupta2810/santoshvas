<?php
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Correct path to include files
$base_path = 'C:/xampp/htdocs/santoshvas/Ecommerce/assets/class/';
require_once $base_path . 'database.class.php';
require_once $base_path . 'function.class.php';

// Initialize database if not already done
if (!isset($db)) {
    // Assuming the database class has a constructor or init method
    $db = new Database(); // Adjust based on your actual class implementation
}

// Initialize function class if not already done
if (!isset($fn)) {
    $fn = new Functions(); // Adjust based on your actual class implementation
}

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required fields
    if (empty($_POST['uemail']) || empty($_POST['upass'])) {
        $_SESSION['error'] = 'Please fill all required fields';
        header('Location: /santoshvas/Ecommerce/user/adminlogin.php');
        exit;
    }
    
    // Sanitize input
    $email = $db->real_escape_string($_POST['uemail']);
    
    // Use password_hash/password_verify instead of MD5
    // For now, use MD5 to maintain compatibility with existing database
    $password = md5($db->real_escape_string($_POST['upass']));
    
    // Query the database
    $result = $db->query("SELECT id, full_name FROM admin WHERE email_id = '$email' AND password = '$password'");
    
    // Check if query was successful
    if (!$result) {
        $_SESSION['error'] = 'Database error: ' . $db->error;
        header('Location: /santoshvas/Ecommerce/user/adminlogin.php');
        exit;
    }
    
    // Check if user exists
    $user = $result->fetch_assoc();
    if ($user) {
        // Set session variables for authentication
        $_SESSION['user'] = $user;
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_name'] = $user['full_name'];
        
        $_SESSION['success'] = 'Logged in Successfully';
        header('Location: /santoshvas/Ecommerce/admin/index.php');
        exit;
    } else {
        // Authentication failed
        $_SESSION['error'] = 'Incorrect Email or Password';
        header('Location: /santoshvas/Ecommerce/user/adminlogin.php');
        exit;
    }
} else {
    // If not a POST request, redirect to login page
    header('Location: /santoshvas/Ecommerce/user/adminlogin.php');
    exit;
}
?>