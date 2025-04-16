<?php
require_once __DIR__ . "/../config.php";

// Include header
include_once __DIR__ . "/includes/header.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    redirectWithMessage('login.php', 'Please login to update your profile', 'error');
}

// Get user details
$user_id = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT * FROM tbl_users WHERE id = ?");
if (!$stmt) {
    die("Error preparing statement: " . $db->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    
    // Validate inputs
    $errors = [];
    if (empty($name)) {
        $errors['name'] = 'Name is required';
    }
    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }
    if (empty($phone)) {
        $errors['phone'] = 'Phone number is required';
    }
    if (empty($address)) {
        $errors['address'] = 'Address is required';
    }
    
    // Check if email is already taken by another user
    if (empty($errors['email'])) {
        $stmt = $db->prepare("SELECT id FROM tbl_users WHERE email = ? AND id != ?");
        if (!$stmt) {
            die("Error preparing statement: " . $db->error);
        }
        $stmt->bind_param("si", $email, $user_id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors['email'] = 'Email is already taken';
        }
    }
    
    // Update user if no errors
    if (empty($errors)) {
        $stmt = $db->prepare("UPDATE tbl_users SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
        if (!$stmt) {
            die("Error preparing statement: " . $db->error);
        }
        $stmt->bind_param("ssssi", $name, $email, $phone, $address, $user_id);
        
        if ($stmt->execute()) {
            redirectWithMessage('userprofile.php', 'Profile updated successfully', 'success');
        } else {
            $errors['general'] = 'Error updating profile: ' . $db->error;
        }
    }
}
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Update Profile</h1>
    
    <?php if (isset($errors['general'])): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
            <?php echo htmlspecialchars($errors['general']); ?>
        </div>
    <?php endif; ?>
    
    <div class="max-w-lg mx-auto">
        <form method="post" class="bg-white rounded-lg shadow-md p-6">
                    <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    Full Name
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       id="name" type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                <?php if (isset($errors['name'])): ?>
                    <p class="text-red-500 text-xs italic"><?php echo htmlspecialchars($errors['name']); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Email
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       id="email" type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                <?php if (isset($errors['email'])): ?>
                    <p class="text-red-500 text-xs italic"><?php echo htmlspecialchars($errors['email']); ?></p>
                    <?php endif; ?>
                </div>
                
                        <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="phone">
                    Phone
                            </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       id="phone" type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                <?php if (isset($errors['phone'])): ?>
                    <p class="text-red-500 text-xs italic"><?php echo htmlspecialchars($errors['phone']); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="address">
                    Address
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                          id="address" name="address" rows="4" required><?php echo htmlspecialchars($user['address']); ?></textarea>
                <?php if (isset($errors['address'])): ?>
                    <p class="text-red-500 text-xs italic"><?php echo htmlspecialchars($errors['address']); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update Profile
                    </button>
                <a href="orders.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    View Orders
                </a>
            </div>
        </form>
    </div>
</div>

<?php include_once __DIR__ . "/includes/footer.php"; ?>