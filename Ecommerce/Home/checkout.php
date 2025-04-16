<?php
require_once __DIR__ . "/../config.php";

// Include header
include_once __DIR__ . "/../user/includes/header.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    redirectWithMessage('user/login.php', 'Please login to checkout', 'error');
}

// Check if cart is empty
if (empty($_SESSION['cart'])) {
    redirectWithMessage('cart.php', 'Your cart is empty', 'error');
}

// Get user details
$user_id = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT * FROM tbl_users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Start transaction
        $db->begin_transaction();
        
        // Create order
        $stmt = $db->prepare("
            INSERT INTO tbl_orders (user_id, total_amount, shipping_address, payment_status) 
            VALUES (?, ?, ?, 'pending')
        ");
        
        $total_amount = getCartTotal();
        $stmt->bind_param("ids", $user_id, $total_amount, $user['address']);
        $stmt->execute();
        $order_id = $db->insert_id;
        
        // Add order items
        $stmt = $db->prepare("
            INSERT INTO tbl_order_items (order_id, product_id, quantity, price) 
            VALUES (?, ?, ?, ?)
        ");
        
        foreach ($_SESSION['cart'] as $item) {
            $stmt->bind_param("iiid", $order_id, $item['p_id'], $item['quantity'], $item['price']);
            $stmt->execute();
            
            // Update product stock
            $update_stmt = $db->prepare("UPDATE tbl_product SET p_qty = p_qty - ? WHERE p_id = ?");
            $update_stmt->bind_param("ii", $item['quantity'], $item['p_id']);
            $update_stmt->execute();
        }
        
        // Commit transaction
        $db->commit();
        
        // Clear cart
        $_SESSION['cart'] = [];
        
        // Redirect to order confirmation
        redirectWithMessage('user/order-details.php?id=' . $order_id, 'Order placed successfully!', 'success');
        
    } catch (Exception $e) {
        // Rollback transaction
        $db->rollback();
        $error_message = "Error placing order: " . $e->getMessage();
    }
}

// Get cart total
$cart_total = getCartTotal();
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Checkout</h1>
    
    <?php if (isset($error_message)): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>
    
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Order Summary -->
        <div class="md:w-1/2">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Order Summary</h2>
                <div class="space-y-4">
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <img src="/santoshvas/Ecommerce/admin/uploadimgs/<?php echo htmlspecialchars($item['photo']); ?>" 
                                     alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                     class="w-16 h-16 object-cover rounded mr-4">
                                <div>
                                    <h3 class="font-medium"><?php echo htmlspecialchars($item['name']); ?></h3>
                                    <p class="text-gray-600">Quantity: <?php echo $item['quantity']; ?></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-medium">₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="border-t mt-4 pt-4">
                    <div class="flex justify-between items-center">
                        <span class="font-bold">Total</span>
                        <span class="text-xl font-bold">₹<?php echo number_format($cart_total, 2); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Shipping Information -->
        <div class="md:w-1/2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Shipping Information</h2>
                <form method="post">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                            Full Name
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                               id="name" type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                            Email
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                               id="email" type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="phone">
                            Phone
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                               id="phone" type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="address">
                            Shipping Address
                        </label>
                        <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                  id="address" name="address" required><?php echo htmlspecialchars($user['address']); ?></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded focus:outline-none focus:shadow-outline">
                        Place Order
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . "/../user/includes/footer.php"; ?> 