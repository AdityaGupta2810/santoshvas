<?php
require_once __DIR__ . "/../config.php";

// Initialize variables
$name = $email = $phone = $address = $password = $confirm_password = '';
$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate form data
    if (empty($name)) {
        $errors['name'] = 'Name is required';
    }

    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    } else {
        // Check if email already exists
        $stmt = $db->prepare("SELECT id FROM tbl_users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors['email'] = 'Email already exists';
        }
    }

    if (empty($phone)) {
        $errors['phone'] = 'Phone number is required';
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $errors['phone'] = 'Invalid phone number format';
    }

    if (empty($address)) {
        $errors['address'] = 'Address is required';
    }

    if (empty($password)) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors['password'] = 'Password must be at least 6 characters long';
    }

    if ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Passwords do not match';
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $stmt = $db->prepare("INSERT INTO tbl_users (name, email, password, phone, address) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $hashed_password, $phone, $address);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'Registration successful! Please login.';
            header('Location: login.php');
            exit();
        } else {
            $errors['general'] = 'Registration failed. Please try again.';
        }
    }
}

// Set page title
$title = "Register - Santosh Vastralay";

// Include header
include_once "includes/header.php";
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Create Account</h1>

            <?php if (isset($errors['general'])): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                    <?php echo $errors['general']; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div>
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Full Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php echo isset($errors['name']) ? 'border-red-500' : ''; ?>">
                    <?php if (isset($errors['name'])): ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo $errors['name']; ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php echo isset($errors['email']) ? 'border-red-500' : ''; ?>">
                    <?php if (isset($errors['email'])): ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo $errors['email']; ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php echo isset($errors['phone']) ? 'border-red-500' : ''; ?>">
                    <?php if (isset($errors['phone'])): ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo $errors['phone']; ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Address</label>
                    <textarea id="address" name="address" rows="3"
                              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php echo isset($errors['address']) ? 'border-red-500' : ''; ?>"><?php echo htmlspecialchars($address); ?></textarea>
                    <?php if (isset($errors['address'])): ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo $errors['address']; ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" id="password" name="password"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php echo isset($errors['password']) ? 'border-red-500' : ''; ?>">
                    <?php if (isset($errors['password'])): ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo $errors['password']; ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="confirm_password" class="block text-gray-700 text-sm font-bold mb-2">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php echo isset($errors['confirm_password']) ? 'border-red-500' : ''; ?>">
                    <?php if (isset($errors['confirm_password'])): ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo $errors['confirm_password']; ?></p>
                    <?php endif; ?>
                </div>

                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Register
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600">Already have an account? 
                    <a href="login.php" class="text-blue-600 hover:text-blue-800">Login here</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>