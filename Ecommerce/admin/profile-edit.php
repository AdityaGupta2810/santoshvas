<?php
require_once __DIR__ . "/config.php";
include_once __DIR__ . "/includes/header.php";

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$success_message = '';
$error_message = '';

// Get admin data
$admin_id = $_SESSION['admin_id'];
$stmt = $db->prepare("SELECT * FROM admin WHERE id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin_data = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email_id']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    $valid = true;
    
    // Validate inputs
    if (empty($full_name)) {
        $error_message = "Full name is required";
        $valid = false;
    }
    
    if (empty($email)) {
        $error_message = "Email is required";
        $valid = false;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format";
        $valid = false;
    }
    
    // Check if email already exists (excluding current admin)
    if ($valid) {
        $stmt = $db->prepare("SELECT id FROM admin WHERE email_id = ? AND id != ?");
        $stmt->bind_param("si", $email, $admin_id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error_message = "Email already exists";
            $valid = false;
        }
    }
    
    // Handle password change if requested
    if (!empty($current_password)) {
        // Verify current password
        if (!password_verify($current_password, $admin_data['password'])) {
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
    }
    
    // Update profile if valid
    if ($valid) {
        if (!empty($new_password)) {
            // Update with new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE admin SET full_name = ?, email_id = ?, password = ? WHERE id = ?");
            $stmt->bind_param("sssi", $full_name, $email, $hashed_password, $admin_id);
        } else {
            // Update without changing password
            $stmt = $db->prepare("UPDATE admin SET full_name = ?, email_id = ? WHERE id = ?");
            $stmt->bind_param("ssi", $full_name, $email, $admin_id);
        }
        
        if ($stmt->execute()) {
            $success_message = "Profile updated successfully!";
            // Refresh admin data
            $stmt = $db->prepare("SELECT * FROM admin WHERE id = ?");
            $stmt->bind_param("i", $admin_id);
            $stmt->execute();
            $admin_data = $stmt->get_result()->fetch_assoc();
        } else {
            $error_message = "Error updating profile: " . $db->error;
        }
    }
}
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6">Edit Profile</h1>
        
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
        
        <form method="POST" class="space-y-6">
            <!-- Full Name -->
            <div>
                <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                <input type="text" name="full_name" id="full_name" 
                       value="<?php echo htmlspecialchars($admin_data['full_name']); ?>"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <!-- Email -->
            <div>
                <label for="email_id" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input type="email" name="email_id" id="email_id" 
                       value="<?php echo htmlspecialchars($admin_data['email_id']); ?>"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <!-- Password Change Section -->
            <div class="border-t pt-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Change Password</h2>
                
                <div class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                        <input type="password" name="current_password" id="current_password"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" name="new_password" id="new_password"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                        <input type="password" name="confirm_password" id="confirm_password"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </div>
            
            <div class="flex items-center justify-end space-x-4">
                <a href="dashboard.php" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update Profile
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Add client-side validation
document.querySelector('form').addEventListener('submit', function(e) {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const currentPassword = document.getElementById('current_password').value;
    
    // Reset any previous error messages
    document.querySelectorAll('.error-message').forEach(el => el.remove());
    
    let isValid = true;
    
    // Validate passwords if changing
    if (currentPassword || newPassword || confirmPassword) {
        if (!currentPassword) {
            showError('current_password', 'Current password is required to change password');
            isValid = false;
        }
        
        if (newPassword && newPassword.length < 6) {
            showError('new_password', 'Password must be at least 6 characters long');
            isValid = false;
        }
        
        if (newPassword !== confirmPassword) {
            showError('confirm_password', 'Passwords do not match');
            isValid = false;
        }
    }
    
    if (!isValid) {
        e.preventDefault();
    }
});

function showError(fieldId, message) {
    const field = document.getElementById(fieldId);
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message text-red-500 text-sm mt-1';
    errorDiv.textContent = message;
    field.parentNode.appendChild(errorDiv);
}
</script>

<?php include_once __DIR__ . "/includes/footer.php"; ?> 