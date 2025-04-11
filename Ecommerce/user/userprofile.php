<?php
session_start();
// Include header
include "C:/xampp/htdocs/santoshvas/Ecommerce/header.php";
// Check if user is logged in

if (!isset($_SESSION['user_id'])) {
    header("Location: /santoshvas/Ecommerce/user/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$title = "User Profile | Santosh Vastralay";

// Initialize database connection
$db = new Database();
$conn = $db->getConnection();

// Get user information
$stmt = $conn->prepare("SELECT id, full_name, email_id FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Get user addresses
$addressStmt = $conn->prepare("SELECT * FROM user_addresses WHERE user_id = ?");
$addressStmt->bind_param("i", $user_id);
$addressStmt->execute();
$addressResult = $addressStmt->get_result();
$addresses = [];
while ($row = $addressResult->fetch_assoc()) {
    $addresses[] = $row;
}

// Update Profile
$updateMessage = '';
if (isset($_POST['update_profile'])) {
    $full_name = $_POST['full_name'];
    $email_id = $_POST['email_id'];
    
    // Check if email already exists but not for current user
    $emailCheckStmt = $conn->prepare("SELECT id FROM users WHERE email_id = ? AND id != ?");
    $emailCheckStmt->bind_param("si", $email_id, $user_id);
    $emailCheckStmt->execute();
    $emailCheckResult = $emailCheckStmt->get_result();
    
    if ($emailCheckResult->num_rows > 0) {
        $updateMessage = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">Email already exists.</div>';
    } else {
        $updateStmt = $conn->prepare("UPDATE users SET full_name = ?, email_id = ? WHERE id = ?");
        $updateStmt->bind_param("ssi", $full_name, $email_id, $user_id);
        
        if ($updateStmt->execute()) {
            $updateMessage = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">Profile updated successfully!</div>';
            // Update session variable
            $_SESSION['full_name'] = $full_name;
            
            // Refresh user data
            $user['full_name'] = $full_name;
            $user['email_id'] = $email_id;
        } else {
            $updateMessage = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">Error updating profile.</div>';
        }
    }
}

// Change Password
$passwordMessage = '';
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Get current password from database
    $passwordStmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $passwordStmt->bind_param("i", $user_id);
    $passwordStmt->execute();
    $passwordResult = $passwordStmt->get_result();
    $userData = $passwordResult->fetch_assoc();
    
    // Verify current password
    if (password_verify($current_password, $userData['password']) || $current_password === $userData['password']) {
        // Check if new password and confirm password match
        if ($new_password === $confirm_password) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            // Update password in database
            $updatePasswordStmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $updatePasswordStmt->bind_param("si", $hashed_password, $user_id);
            
            if ($updatePasswordStmt->execute()) {
                $passwordMessage = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">Password changed successfully!</div>';
            } else {
                $passwordMessage = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">Error changing password.</div>';
            }
        } else {
            $passwordMessage = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">New passwords do not match.</div>';
        }
    } else {
        $passwordMessage = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">Current password is incorrect.</div>';
    }
}

// Add new address
$addressMessage = '';
if (isset($_POST['add_address'])) {
    $street_address = $_POST['street_address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postal_code = $_POST['postal_code'];
    $phone = $_POST['phone'];
    $is_default = isset($_POST['is_default']) ? 1 : 0;
    
    // If this is the default address, unset other default addresses
    if ($is_default) {
        $unsetDefaultStmt = $conn->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = ?");
        $unsetDefaultStmt->bind_param("i", $user_id);
        $unsetDefaultStmt->execute();
    }
    
    // Insert new address
    $addAddressStmt = $conn->prepare("INSERT INTO user_addresses (user_id, street_address, city, state, postal_code, phone, is_default) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $addAddressStmt->bind_param("isssssi", $user_id, $street_address, $city, $state, $postal_code, $phone, $is_default);
    
    if ($addAddressStmt->execute()) {
        $addressMessage = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">Address added successfully!</div>';
        
        // Refresh addresses list
        $addressStmt->execute();
        $addressResult = $addressStmt->get_result();
        $addresses = [];
        while ($row = $addressResult->fetch_assoc()) {
            $addresses[] = $row;
        }
    } else {
        $addressMessage = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">Error adding address.</div>';
    }
}

// Delete address
if (isset($_POST['delete_address'])) {
    $address_id = $_POST['address_id'];
    
    $deleteAddressStmt = $conn->prepare("DELETE FROM user_addresses WHERE id = ? AND user_id = ?");
    $deleteAddressStmt->bind_param("ii", $address_id, $user_id);
    
    if ($deleteAddressStmt->execute()) {
        $addressMessage = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">Address deleted successfully!</div>';
        
        // Refresh addresses list
        $addressStmt->execute();
        $addressResult = $addressStmt->get_result();
        $addresses = [];
        while ($row = $addressResult->fetch_assoc()) {
            $addresses[] = $row;
        }
    } else {
        $addressMessage = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">Error deleting address.</div>';
    }
}

// Set address as default
if (isset($_POST['set_default'])) {
    $address_id = $_POST['address_id'];
    
    // First unset all default addresses for this user
    $unsetAllDefaultStmt = $conn->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = ?");
    $unsetAllDefaultStmt->bind_param("i", $user_id);
    $unsetAllDefaultStmt->execute();
    
    // Then set the selected address as default
    $setDefaultStmt = $conn->prepare("UPDATE user_addresses SET is_default = 1 WHERE id = ? AND user_id = ?");
    $setDefaultStmt->bind_param("ii", $address_id, $user_id);
    
    if ($setDefaultStmt->execute()) {
        $addressMessage = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">Default address updated successfully!</div>';
        
        // Refresh addresses list
        $addressStmt->execute();
        $addressResult = $addressStmt->get_result();
        $addresses = [];
        while ($row = $addressResult->fetch_assoc()) {
            $addresses[] = $row;
        }
    } else {
        $addressMessage = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">Error updating default address.</div>';
    }
}


?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-center">User Profile</h1>
    
    <div class="flex flex-col md:flex-row gap-6">
        <!-- Left Sidebar: Navigation -->
        <div class="md:w-1/4">
            <div class="bg-white rounded-lg shadow-md p-4">
                <h2 class="text-xl font-semibold mb-4">Navigation</h2>
                <ul class="space-y-2">
                    <li>
                        <a href="#profile" class="profile-tab block p-2 rounded-md hover:bg-indigo-100 text-indigo-700 font-medium">
                            <i class="fas fa-user mr-2"></i> Profile Information
                        </a>
                    </li>
                    <li>
                        <a href="#address" class="address-tab block p-2 rounded-md hover:bg-indigo-100 text-gray-700">
                            <i class="fas fa-map-marker-alt mr-2"></i> Manage Addresses
                        </a>
                    </li>
                    <li>
                        <a href="#orders" class="orders-tab block p-2 rounded-md hover:bg-indigo-100 text-gray-700">
                            <i class="fas fa-box mr-2"></i> Order History
                        </a>
                    </li>
                    <li>
                        <a href="#security" class="security-tab block p-2 rounded-md hover:bg-indigo-100 text-gray-700">
                            <i class="fas fa-lock mr-2"></i> Security
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Right Content: Actual forms and content -->
        <div class="md:w-3/4">
            <!-- Profile Section -->
            <div id="profile-section" class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-2xl font-semibold mb-4">Profile Information</h2>
                <?php echo $updateMessage; ?>
                <form method="POST" action="">
                    <div class="mb-4">
                        <label for="full_name" class="block text-gray-700 font-medium mb-2">Full Name</label>
                        <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label for="email_id" class="block text-gray-700 font-medium mb-2">Email Address</label>
                        <input type="email" id="email_id" name="email_id" value="<?php echo htmlspecialchars($user['email_id']); ?>" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <button type="submit" name="update_profile" 
                    class="bg-indigo-600 text-white font-medium py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors">
                        Update Profile
                    </button>
                </form>
            </div>
            
            <!-- Addresses Section -->
            <div id="address-section" class="bg-white rounded-lg shadow-md p-6 mb-6 hidden">
                <h2 class="text-2xl font-semibold mb-4">Manage Addresses</h2>
                <?php echo $addressMessage; ?>
                
                <!-- Address List -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium mb-3">Your Addresses</h3>
                    
                    <?php if (empty($addresses)): ?>
                        <p class="text-gray-600">You don't have any saved addresses yet.</p>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($addresses as $address): ?>
                                <div class="border rounded-lg p-4 <?php echo $address['is_default'] ? 'border-indigo-500 bg-indigo-50' : 'border-gray-300'; ?>">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-medium">
                                                <?php echo htmlspecialchars($address['street_address']); ?>
                                                <?php if ($address['is_default']): ?>
                                                    <span class="ml-2 bg-indigo-600 text-white text-xs px-2 py-0.5 rounded-full">Default</span>
                                                <?php endif; ?>
                                            </p>
                                            <p class="text-gray-600">
                                                <?php echo htmlspecialchars($address['city']) . ', ' . htmlspecialchars($address['state']) . ' ' . htmlspecialchars($address['postal_code']); ?>
                                            </p>
                                            <p class="text-gray-600">
                                                <i class="fas fa-phone-alt mr-1"></i> <?php echo htmlspecialchars($address['phone']); ?>
                                            </p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <?php if (!$address['is_default']): ?>
                                                <form method="POST" action="">
                                                    <input type="hidden" name="address_id" value="<?php echo $address['id']; ?>">
                                                    <button type="submit" name="set_default" class="text-indigo-600 hover:text-indigo-800">
                                                        <i class="fas fa-check-circle"></i> Set as Default
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this address?');">
                                                <input type="hidden" name="address_id" value="<?php echo $address['id']; ?>">
                                                <button type="submit" name="delete_address" class="text-red-600 hover:text-red-800">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Add New Address Form -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-medium mb-3">Add New Address</h3>
                    <form method="POST" action="">
                        <div class="mb-4">
                            <label for="street_address" class="block text-gray-700 font-medium mb-2">Street Address</label>
                            <input type="text" id="street_address" name="street_address" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="city" class="block text-gray-700 font-medium mb-2">City</label>
                                <input type="text" id="city" name="city" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label for="state" class="block text-gray-700 font-medium mb-2">State</label>
                                <input type="text" id="state" name="state" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="postal_code" class="block text-gray-700 font-medium mb-2">Postal Code</label>
                                <input type="text" id="postal_code" name="postal_code" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label for="phone" class="block text-gray-700 font-medium mb-2">Phone Number</label>
                                <input type="tel" id="phone" name="phone" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_default" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <span class="ml-2 text-gray-700">Set as default address</span>
                            </label>
                        </div>
                        
                        <button type="submit" name="add_address" 
                        class="bg-indigo-600 text-white font-medium py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors">
                            Add Address
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Orders Section -->
            <div id="orders-section" class="bg-white rounded-lg shadow-md p-6 mb-6 hidden">
                <h2 class="text-2xl font-semibold mb-4">Order History</h2>
                
                <!-- Sample Order History - This would be populated from database -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-3 px-4 bg-gray-100 text-left text-gray-600 font-medium">Order ID</th>
                                <th class="py-3 px-4 bg-gray-100 text-left text-gray-600 font-medium">Date</th>
                                <th class="py-3 px-4 bg-gray-100 text-left text-gray-600 font-medium">Total</th>
                                <th class="py-3 px-4 bg-gray-100 text-left text-gray-600 font-medium">Status</th>
                                <th class="py-3 px-4 bg-gray-100 text-center text-gray-600 font-medium">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="py-3 px-4 border-b">
                                    <span class="font-medium">#ORD-2023001</span>
                                </td>
                                <td class="py-3 px-4 border-b">Apr 5, 2023</td>
                                <td class="py-3 px-4 border-b">₹1,250.00</td>
                                <td class="py-3 px-4 border-b">
                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Delivered</span>
                                </td>
                                <td class="py-3 px-4 border-b text-center">
                                    <a href="#" class="text-indigo-600 hover:text-indigo-800">View Details</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4 border-b">
                                    <span class="font-medium">#ORD-2023002</span>
                                </td>
                                <td class="py-3 px-4 border-b">Mar 22, 2023</td>
                                <td class="py-3 px-4 border-b">₹2,480.00</td>
                                <td class="py-3 px-4 border-b">
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">Shipped</span>
                                </td>
                                <td class="py-3 px-4 border-b text-center">
                                    <a href="#" class="text-indigo-600 hover:text-indigo-800">View Details</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4 border-b">
                                    <span class="font-medium">#ORD-2023003</span>
                                </td>
                                <td class="py-3 px-4 border-b">Mar 10, 2023</td>
                                <td class="py-3 px-4 border-b">₹980.00</td>
                                <td class="py-3 px-4 border-b">
                                    <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full">Cancelled</span>
                                </td>
                                <td class="py-3 px-4 border-b text-center">
                                    <a href="#" class="text-indigo-600 hover:text-indigo-800">View Details</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Security Section -->
            <div id="security-section" class="bg-white rounded-lg shadow-md p-6 mb-6 hidden">
                <h2 class="text-2xl font-semibold mb-4">Security</h2>
                <?php echo $passwordMessage; ?>
                
                <!-- Change Password Form -->
                <form method="POST" action="">
                    <div class="mb-4">
                        <label for="current_password" class="block text-gray-700 font-medium mb-2">Current Password</label>
                        <input type="password" id="current_password" name="current_password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label for="new_password" class="block text-gray-700 font-medium mb-2">New Password</label>
                        <input type="password" id="new_password" name="new_password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label for="confirm_password" class="block text-gray-700 font-medium mb-2">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <button type="submit" name="change_password" 
                    class="bg-indigo-600 text-white font-medium py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors">
                        Change Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Tab navigation
    const tabs = {
        'profile': document.querySelector('#profile-section'),
        'address': document.querySelector('#address-section'),
        'orders': document.querySelector('#orders-section'),
        'security': document.querySelector('#security-section')
    };
    
    const tabLinks = document.querySelectorAll('.profile-tab, .address-tab, .orders-tab, .security-tab');
    
    function showTab(tabName) {
        // Hide all tab contents
        Object.values(tabs).forEach(tab => {
            tab.classList.add('hidden');
        });
        
        // Remove active class from all tab links
        tabLinks.forEach(link => {
            link.classList.remove('text-indigo-700', 'bg-indigo-100');
            link.classList.add('text-gray-700');
        });
        
        // Show the selected tab
        tabs[tabName].classList.remove('hidden');
        
        // Set active class on the clicked tab link
        document.querySelector('.' + tabName + '-tab').classList.add('text-indigo-700', 'bg-indigo-100');
        document.querySelector('.' + tabName + '-tab').classList.remove('text-gray-700');
    }
    
    // Add click event listeners to tab links
    document.querySelector('.profile-tab').addEventListener('click', function(e) {
        e.preventDefault();
        showTab('profile');
    });
    
    document.querySelector('.address-tab').addEventListener('click', function(e) {
        e.preventDefault();
        showTab('address');
    });
    
    document.querySelector('.orders-tab').addEventListener('click', function(e) {
        e.preventDefault();
        showTab('orders');
    });
    
    document.querySelector('.security-tab').addEventListener('click', function(e) {
        e.preventDefault();
        showTab('security');
    });
    
    // Show profile tab by default
    showTab('profile');
    
    // Handle hash in URL
    if (window.location.hash) {
        const hash = window.location.hash.substring(1);
        if (tabs[hash]) {
            showTab(hash);
        }
    }
</script>

<?php include "C:/xampp/htdocs/santoshvas/Ecommerce/footer.php"; ?>