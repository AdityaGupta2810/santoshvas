<?php
require_once __DIR__ . "/../db.php";
include_once __DIR__ . "/includes/header.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$success_message = '';
$error_message = '';

// Get user data
$user_id = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT * FROM tbl_users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $address = trim($_POST['address']);
        
        $valid = true;
        
        // Validate inputs
        if (empty($name)) {
            $error_message = "Name is required";
            $valid = false;
        }
        
        if (empty($email)) {
            $error_message = "Email is required";
            $valid = false;
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = "Invalid email format";
            $valid = false;
        }
        
        // Check if email already exists (excluding current user)
        if ($valid) {
            $stmt = $db->prepare("SELECT id FROM tbl_users WHERE email = ? AND id != ?");
            $stmt->bind_param("si", $email, $user_id);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $error_message = "Email already exists";
                $valid = false;
            }
        }
        
        // Update profile if valid
        if ($valid) {
            $stmt = $db->prepare("UPDATE tbl_users SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $name, $email, $phone, $address, $user_id);
            
            if ($stmt->execute()) {
                $success_message = "Profile updated successfully!";
                // Refresh user data
                $stmt = $db->prepare("SELECT * FROM tbl_users WHERE id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $user_data = $stmt->get_result()->fetch_assoc();
            } else {
                $error_message = "Error updating profile: " . $db->error;
            }
        }
    }
    
    // Handle password update
    if (isset($_POST['update_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        $valid = true;
        
        // Verify current password
        if (!password_verify($current_password, $user_data['password'])) {
            $error_message = "Current password is incorrect";
            $valid = false;
        }
        
        // Validate new password
        if (empty($new_password)) {
            $error_message = "New password is required";
            $valid = false;
        } elseif (strlen($new_password) < 6) {
            $error_message = "Password must be at least 6 characters long";
            $valid = false;
        } elseif ($new_password !== $confirm_password) {
            $error_message = "New passwords do not match";
            $valid = false;
        }
        
        if ($valid) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE tbl_users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashed_password, $user_id);
            
            if ($stmt->execute()) {
                $success_message = "Password updated successfully!";
            } else {
                $error_message = "Error updating password: " . $db->error;
            }
        }
    }
}
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">My Profile</h1>
        
        <?php if ($success_message): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <!-- Profile Information Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Update Profile Information</h2>
            <form method="POST" class="space-y-4">
                <div>
                    <label for="name" class="block text font-medium text-gray-700">Name *</label>
                    <input type="text" name="name" id="name" 
                           value="<?php echo htmlspecialchars($user_data['name']); ?>"
                           class="mt-2 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           required>
                </div>
                
                <div>
                    <label for="email" class="block text font-medium text-gray-700">Email Address *</label>
                    <input type="email" name="email" id="email" 
                           value="<?php echo htmlspecialchars($user_data['email']); ?>"
                           class="mt-2 block w-full rounded-md p-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           required>
                </div>
                
                <div>
                    <label for="phone" class="block text font-medium text-gray-700">Phone Number</label>
                    <input type="tel" name="phone" id="phone" 
                           value="<?php echo htmlspecialchars($user_data['phone']); ?>"
                           class="mt-2 block w-full rounded-md border-gray-300 shadow-sm p-2 focus:border-blue-500 focus:ring-blue-500"
                           pattern="[0-9]{10}">
                    <p class="mt-1 text-sm text-gray-500">10 digits number</p>
                </div>
                
                <div>
                    <label for="address" class="block text font-medium text-gray-700">Address</label>
                    <textarea name="address" id="address" rows="3"
                              class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2"><?php echo htmlspecialchars($user_data['address']); ?></textarea>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" name="update_profile"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Password Update Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Change Password</h2>
            <form method="POST" id="passwordForm" class="space-y-4">
                <div>
                    <label for="current_password" class="block text font-medium text-gray-700">Current Password *</label>
                    <input type="password" name="current_password" id="current_password"
                           class="mt-2 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           required>
                </div>
                
                <div>
                    <label for="new_password" class="block text font-medium text-gray-700">New Password *</label>
                    <input type="password" name="new_password" id="new_password"
                           class="mt-2 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           required>
                    <p class="mt-1 text-sm text-gray-500">Minimum 6 characters</p>
                </div>
                
                <div>
                    <label for="confirm_password" class="block text font-medium text-gray-700">Confirm New Password *</label>
                    <input type="password" name="confirm_password" id="confirm_password"
                           class="mt-2 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           required>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" name="update_password"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Client-side validation
document.getElementById('passwordForm').addEventListener('submit', function(e) {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (newPassword.length < 6) {
        e.preventDefault();
        alert('Password must be at least 6 characters long');
        return;
    }
    
    if (newPassword !== confirmPassword) {
        e.preventDefault();
        alert('Passwords do not match');
        return;
    }
});

// Phone number validation
document.getElementById('phone').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
});
</script>

<?php include_once __DIR__ . "/includes/footer.php"; ?>