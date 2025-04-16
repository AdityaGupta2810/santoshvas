<?php
// Start session
session_start();

// Set page title
$title = "Shopping Cart - Santosh Vastralay";

// Include header (assumes $db is defined here)
include_once "../user/includes/header.php";

// Include cart functions
include_once "cart-functions.php";

// Check database connection
if (!isset($db) || !$db) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Get banner image
$banner_result = $db->query("SELECT banner_cart FROM tbl_settings WHERE id=1");
$banner_row = $banner_result->fetch_assoc();
$banner_cart = $banner_row['banner_cart'] ?? 'default-banner.jpg';

// Handle form submission
$error_message = '';
$success_message = '';

if (isset($_POST['update_cart'])) {
    $all_valid = true;
    $error_messages = [];
    
    foreach ($_SESSION['cart'] as $key => $item) {
        $quantity = (int)$_POST['quantity'][$key];
        $result = updateCartQuantity($db, $item['p_id'], $quantity);
        
        if (!$result['success']) {
            $all_valid = false;
            $error_messages[] = $result['message'];
        }
    }
    
    if ($all_valid) {
        $success_message = "Cart updated successfully!";
    } else {
        $error_message = implode("<br>", $error_messages);
    }
}

// Handle remove item
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $result = removeFromCart((int)$_GET['remove']);
    if ($result['success']) {
        $success_message = $result['message'];
    } else {
        $error_message = $result['message'];
    }
}
?>

<div class="relative h-64 w-full bg-gray-900 overflow-hidden">
    <img src="/santoshvas/Ecommerce/admin/assets/uploads/<?php echo htmlspecialchars($banner_cart); ?>" 
         alt="Shopping Cart" 
         class="w-full h-full object-cover opacity-70">
    <div class="absolute inset-0 flex items-center justify-center">
        <h1 class="text-4xl font-bold text-white z-10">Your Shopping Cart</h1>
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    <?php if(isset($error_message) && $error_message): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
    
    <?php if(isset($success_message) && $success_message): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>

    <?php if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])): ?>
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-shopping-cart fa-4x text-gray-400 mb-4"></i>
            <h2 class="text-2xl font-bold text-gray-700 mb-2">Your cart is empty</h2>
            <p class="text-gray-600 mb-6">Add products to your cart to view them here.</p>
            <a href="landingpage.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg">
                Continue Shopping
            </a>
        </div>
    <?php else: ?>
        <form method="post" class="mb-8">
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-3 px-4 text-left">#</th>
                            <th class="py-3 px-4 text-left">Product</th>
                            <th class="py-3 px-4 text-left">Price</th>
                            <th class="py-3 px-4 text-left">Quantity</th>
                            <th class="py-3 px-4 text-right">Total</th>
                            <th class="py-3 px-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $table_total_price = getCartTotal();
                        foreach ($_SESSION['cart'] as $key => $item): 
                            $product_id = $item['p_id'];
                            $quantity = $item['quantity'];
                            $price = $item['price'];
                            $product_name = $item['name'];
                            $photo = $item['photo'];
                            
                            $row_total = $price * $quantity;
                        ?>
                            <tr class="border-t border-gray-200">
                                <td class="py-4 px-4"><?php echo $key+1; ?></td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center">
                                        <img src="/santoshvas/Ecommerce/admin/uploadimgs/<?php echo htmlspecialchars($photo); ?>" 
                                             alt="<?php echo htmlspecialchars($product_name); ?>" 
                                             class="w-16 h-16 object-cover rounded mr-4">
                                        <?php echo htmlspecialchars($product_name); ?>
                                    </div>
                                </td>
                                <td class="py-4 px-4">₹<?php echo number_format($price, 2); ?></td>
                                <td class="py-4 px-4">
                                    <input type="number" 
                                           class="w-20 p-2 border rounded" 
                                           min="1" 
                                           name="quantity[]" 
                                           value="<?php echo $quantity; ?>">
                                </td>
                                <td class="py-4 px-4 text-right">₹<?php echo number_format($row_total, 2); ?></td>
                                <td class="py-4 px-4 text-center">
                                    <a href="cart.php?remove=<?php echo $product_id; ?>" 
                                       class="text-red-500 hover:text-red-700"
                                       onclick="return confirm('Are you sure you want to remove this item?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="bg-gray-50 font-bold">
                            <td colspan="4" class="py-4 px-4">Total</td>
                            <td class="py-4 px-4 text-right">₹<?php echo number_format($table_total_price, 2); ?></td>
                            <td class="py-4 px-4"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex flex-wrap justify-between gap-4">
                <div class="flex gap-2">
                    <button type="submit" name="update_cart" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg">
                        Update Cart
                    </button>
                    <a href="landingpage.php" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-6 rounded-lg inline-flex items-center">
                        Continue Shopping
                    </a>
                </div>
                <a href="checkout.php" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg inline-flex items-center">
                    Proceed to Checkout <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php include_once "../user/includes/footer.php"; ?>

<script>
    function confirmDelete() {
        return confirm("Are you sure you want to remove this item from your cart?");
    }
</script>