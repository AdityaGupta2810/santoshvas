



<?php 
// Start session securely
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 86400,
        'cookie_secure' => true,
        'cookie_httponly' => true,
        'cookie_samesite' => 'Strict'
    ]);
}



// Database connection
$conn = new mysqli('localhost', 'root', '', 'santoshvastralay');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get banner image
$banner_result = $conn->query("SELECT banner_cart FROM tbl_settings WHERE id=1");
$banner_row = $banner_result->fetch_assoc();
$banner_cart = $banner_row['banner_cart'] ?? 'default-banner.jpg';

// Handle form submission
$error_message = '';
if(isset($_POST['update_cart'])) {
    // Validate quantities
    $all_valid = true;
    
    foreach($_POST['product_id'] as $key => $product_id) {
        $quantity = (int)$_POST['quantity'][$key];
        $product_name = $conn->real_escape_string($_POST['product_name'][$key]);
        
        // Check stock
        $stock_result = $conn->query("SELECT p_qty FROM tbl_product WHERE p_id = " . (int)$product_id);
        if ($stock_result->num_rows > 0) {
            $stock_row = $stock_result->fetch_assoc();
            if ($quantity > $stock_row['p_qty']) {
                $error_message .= "Only {$stock_row['p_qty']} items available for $product_name. ";
                $all_valid = false;
            } else {
                // Update quantity in session
                $_SESSION['cart_p_qty'][$key] = $quantity;
            }
        }
    }
    
    if ($all_valid) {
        $success_message = "Cart updated successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <?php require_once('../user/includes/header.php'); ?>

    <!-- Banner Section -->
    <div class="relative h-64 w-full bg-gray-900 overflow-hidden">
        <img src="assets/uploads/<?php echo htmlspecialchars($banner_cart); ?>" 
             alt="Shopping Cart" 
             class="w-full h-full object-cover opacity-70">
        <div class="absolute inset-0 flex items-center justify-center">
            <h1 class="text-4xl font-bold text-white">Your Shopping Cart</h1>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <?php if(isset($error_message) && $error_message): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($success_message)): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>
 
        <?php if(!isset($_SESSION['cart_p_id']) || empty($_SESSION['cart_p_id'])): ?>
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <i class="fas fa-shopping-cart fa-4x text-gray-400 mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-700 mb-2">Your cart is empty</h2>
                <p class="text-gray-600 mb-6">Add products to your cart to view them here.</p>
                <a href="../index.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg">
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
                                <th class="py-3 px-4 text-left">Size</th>
                                <th class="py-3 px-4 text-left">Color</th>
                                <th class="py-3 px-4 text-left">Price</th>
                                <th class="py-3 px-4 text-left">Quantity</th>
                                <th class="py-3 px-4 text-right">Total</th>
                                <th class="py-3 px-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $table_total_price = 0;
                            
                            for($i = 0; $i < count($_SESSION['cart_p_id']); $i++): 
                                $product_id = $_SESSION['cart_p_id'][$i];
                                $size_id = $_SESSION['cart_size_id'][$i] ?? '';
                                $size_name = $_SESSION['cart_size_name'][$i] ?? '';
                                $color_id = $_SESSION['cart_color_id'][$i] ?? '';
                                $color_name = $_SESSION['cart_color_name'][$i] ?? '';
                                $quantity = $_SESSION['cart_p_qty'][$i];
                                $price = $_SESSION['cart_p_current_price'][$i];
                                $product_name = $_SESSION['cart_p_name'][$i];
                                $photo = $_SESSION['cart_p_featured_photo'][$i];
                                
                                $row_total = $price * $quantity;
                                $table_total_price += $row_total;
                            ?>
                                <tr class="border-t border-gray-200">
                                    <td class="py-4 px-4"><?php echo $i+1; ?></td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center">
                                            <img src="assets/uploads/<?php echo htmlspecialchars($photo); ?>" 
                                                 alt="<?php echo htmlspecialchars($product_name); ?>" 
                                                 class="w-16 h-16 object-cover rounded mr-4">
                                            <?php echo htmlspecialchars($product_name); ?>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4"><?php echo htmlspecialchars($size_name); ?></td>
                                    <td class="py-4 px-4"><?php echo htmlspecialchars($color_name); ?></td>
                                    <td class="py-4 px-4">$<?php echo number_format($price, 2); ?></td>
                                    <td class="py-4 px-4">
                                        <input type="hidden" name="product_id[]" value="<?php echo $product_id; ?>">
                                        <input type="hidden" name="product_name[]" value="<?php echo htmlspecialchars($product_name); ?>">
                                        <input type="number" 
                                               class="w-20 p-2 border rounded" 
                                               min="1" 
                                               name="quantity[]" 
                                               value="<?php echo $quantity; ?>">
                                    </td>
                                    <td class="py-4 px-4 text-right">$<?php echo number_format($row_total, 2); ?></td>
                                    <td class="py-4 px-4 text-center">
                                        <a href="cart-item-delete.php?id=<?php echo $product_id; ?>&size=<?php echo $size_id; ?>&color=<?php echo $color_id; ?>" 
                                           class="text-red-500 hover:text-red-700"
                                           onclick="return confirm('Are you sure you want to remove this item?');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endfor; ?>
                            <tr class="bg-gray-50 font-bold">
                                <td colspan="5" class="py-4 px-4">Total</td>
                                <td class="py-4 px-4"></td>
                                <td class="py-4 px-4 text-right">$<?php echo number_format($table_total_price, 2); ?></td>
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
                        <a href="index.php" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-6 rounded-lg inline-flex items-center">
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

    <?php require_once('../user/includes/footer.php'); ?>

    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to remove this item from your cart?");
        }
    </script>
</body>
</html>