<?php
$email = $db->real_escape_string($_POST['email']);
$password = $_POST['password'];

$result = $db->query("SELECT password FROM users WHERE email_id='$email'");
$row = $result->fetch_assoc();

if ($row && password_verify($password, $row['password'])) {
    echo "Login successful!";
} else {
    echo "Invalid email or password.";
}

