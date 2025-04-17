<?php
require_once __DIR__ . "/../db.php";

// Include header
include_once __DIR__ . "/includes/header.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    redirectWithMessage('login.php', 'Please login to view order details', 'error');
}

// Get order ID
$order_id = $_GET['id'] ?? 0;
if (!$order_id) {
    redirectWithMessage('orders.php', 'Invalid order ID', 'error');
}

// Get order details
try {
    // Get order information
    $stmt = $db->prepare("
        SELECT o.*, u.name, u.email, u.phone 
        FROM tbl_orders o
        JOIN tbl_users u ON o.user_id = u.id
        WHERE o.id = ? AND o.user_id = ?
    ");
    
    if (!$stmt) {
        throw new Exception("Database error: " . $db->error);
    }
    
    $stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();
    
    if (!$order) {
        redirectWithMessage('orders.php', 'Order not found', 'error');
    }
    
    // Get order items
    $stmt = $db->prepare("
        SELECT oi.*, p.p_name, p.p_featured_photo
        FROM tbl_order_items oi
        JOIN tbl_product p ON oi.product_id = p.p_id
        WHERE oi.order_id = ?
    ");
    
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
} catch (Exception $e) {
    $error_message = "Error fetching order details: " . $e->getMessage();
}
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Order Details #<?php echo str_pad($order_id, 6, '0', STR_PAD_LEFT); ?></h1>
    
    <?php if (isset($error_message)): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>
    
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Order Information -->
        <div class="md:w-2/3">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Order Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <h3 class="font-bold text-gray-700">Order Date</h3>
                        <p><?php echo date('M d, Y', strtotime($order['created_at'])); ?></p>
                    </div>
                    
                    <div>
                        <h3 class="font-bold text-gray-700">Order Status</h3>
                        <span class="px-2 py-1 rounded-full text-sm 
                            <?php 
                            switch($order['status']) {
                                case 'completed':
                                    echo 'bg-green-100 text-green-800';
                                    break;
                                case 'cancelled':
                                    echo 'bg-red-100 text-red-800';
                                    break;
                                case 'processing':
                                    echo 'bg-blue-100 text-blue-800';
                                    break;
                                default:
                                    echo 'bg-yellow-100 text-yellow-800';
                            }
                            ?>">
                            <?php echo ucfirst($order['status']); ?>
                        </span>
                    </div>
                    
                    <div>
                        <h3 class="font-bold text-gray-700">Total Amount</h3>
                        <p class="text-xl font-bold">₹<?php echo number_format($order['total_amount'], 2); ?></p>
                    </div>
                </div>
                
                <h3 class="font-bold text-gray-700 mb-2">Shipping Address</h3>
                <p class="text-gray-600"><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
            </div>
            
            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Ordered Items</h2>
                <div class="space-y-4">
                    <?php foreach ($items as $item): ?>
                        <div class="flex items-center justify-between border-b pb-4">
                            <div class="flex items-center">
                                <img src="/santoshvas/Ecommerce/admin/uploadimgs/<?php echo htmlspecialchars($item['p_featured_photo']); ?>" 
                                     alt="<?php echo htmlspecialchars($item['p_name']); ?>" 
                                     class="w-16 h-16 object-cover rounded mr-4">
                                <div>
                                    <h3 class="font-medium"><?php echo htmlspecialchars($item['p_name']); ?></h3>
                                    <p class="text-gray-600">Quantity: <?php echo $item['quantity']; ?></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-medium">₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                                <p class="text-sm text-gray-600">₹<?php echo number_format($item['price'], 2); ?> each</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <!-- Customer Information -->
        <div class="md:w-1/3">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Customer Information</h2>
                
                <div class="space-y-4">
                    <div>
                        <h3 class="font-bold text-gray-700">Name</h3>
                        <p><?php echo htmlspecialchars($order['name']); ?></p>
                    </div>
                    
                    <div>
                        <h3 class="font-bold text-gray-700">Email</h3>
                        <p><?php echo htmlspecialchars($order['email']); ?></p>
                    </div>
                    
                    <div>
                        <h3 class="font-bold text-gray-700">Phone</h3>
                        <p><?php echo htmlspecialchars($order['phone']); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . "/includes/footer.php"; ?> 