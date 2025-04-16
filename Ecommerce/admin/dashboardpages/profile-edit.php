<?php
require_once __DIR__ . "/../../config.php";
include_once __DIR__ . "/../includes/header.php";

// Check if admin is logged in - no need to start session here as it's already started in header
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
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
    if (isset($_POST['update_info'])) {
        // Handle basic info update
        $full_name = trim($_POST['full_name']);
        $email_id = trim($_POST['email_id']);
        
        $valid = true;
        
        // Validate inputs
        if (empty($full_name)) {
            $error_message = "Full name is required";
            $valid = false;
        }
        
        if (empty($email_id)) {
            $error_message = "Email is required";
            $valid = false;
        } elseif (!filter_var($email_id, FILTER_VALIDATE_EMAIL)) {
            $error_message = "Invalid email format";
            $valid = false;
        }
        
        // Check if email already exists (excluding current admin)
        if ($valid) {
            $stmt = $db->prepare("SELECT id FROM admin WHERE email_id = ? AND id != ?");
            $stmt->bind_param("si", $email_id, $admin_id);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $error_message = "Email already exists";
                $valid = false;
            }
        }
        
        if ($valid) {
            $stmt = $db->prepare("UPDATE admin SET full_name = ?, email_id = ? WHERE id = ?");
            $stmt->bind_param("ssi", $full_name, $email_id, $admin_id);
            
            if ($stmt->execute()) {
                $success_message = "Profile information updated successfully!";
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
    
    // Handle password update
    if (isset($_POST['update_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        $valid = true;
        
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
        
        if ($valid) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE admin SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashed_password, $admin_id);
            
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

        <!-- Update Information Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Update Information</h2>
            <form method="POST" class="space-y-4">
                <div>
                    <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                    <input type="text" name="full_name" id="full_name" 
                           value="<?php echo htmlspecialchars($admin_data['full_name']); ?>"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           required>
            </div>

                <div>
                    <label for="email_id" class="block text-sm font-medium text-gray-700">Email Address *</label>
                    <input type="email" name="email_id" id="email_id" 
                           value="<?php echo htmlspecialchars($admin_data['email_id']); ?>"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           required>
                        </div>

                <div class="flex justify-end">
                    <button type="submit" name="update_info"
                                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Update Information
                            </button>
                        </div>
                    </form>
                </div>

        <!-- Update Password Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Update Password</h2>
            <form method="POST" class="space-y-4">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password *</label>
                    <input type="password" name="current_password" id="current_password"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           required>
                        </div>

                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700">New Password *</label>
                    <input type="password" name="new_password" id="new_password"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           required>
                </div>

                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm New Password *</label>
                    <input type="password" name="confirm_password" id="confirm_password"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
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
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        if (this.querySelector('[name="update_password"]')) {
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
        }
    });
        });
    </script>

<?php include_once __DIR__ . "/../includes/footer.php"; ?>