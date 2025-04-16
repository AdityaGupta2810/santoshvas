<?php
// Cart functions to be included in both product.php and cart.php

function addToCart($db, $product_id, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Get product details
    $stmt = $db->prepare("SELECT p_name, p_current_price, p_featured_photo, p_qty FROM tbl_product WHERE p_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return ['success' => false, 'message' => 'Product not found'];
    }
    
    $product = $result->fetch_assoc();
    
    // Check if product already in cart
    $product_exists = false;
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['p_id'] == $product_id) {
            $new_quantity = min($item['quantity'] + $quantity, $product['p_qty']);
            if ($new_quantity > $product['p_qty']) {
                return ['success' => false, 'message' => "Only {$product['p_qty']} items available"];
            }
            $_SESSION['cart'][$key]['quantity'] = $new_quantity;
            $product_exists = true;
            break;
        }
    }
    
    if (!$product_exists) {
        if ($quantity > $product['p_qty']) {
            return ['success' => false, 'message' => "Only {$product['p_qty']} items available"];
        }
        
        $_SESSION['cart'][] = [
            'p_id' => $product_id,
            'name' => $product['p_name'],
            'price' => $product['p_current_price'],
            'photo' => $product['p_featured_photo'],
            'quantity' => $quantity
        ];
    }
    
    return ['success' => true, 'message' => 'Product added to cart successfully'];
}

function updateCartQuantity($db, $product_id, $quantity) {
    if (!isset($_SESSION['cart'])) {
        return ['success' => false, 'message' => 'Cart is empty'];
    }
    
    // Get product stock
    $stmt = $db->prepare("SELECT p_qty FROM tbl_product WHERE p_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return ['success' => false, 'message' => 'Product not found'];
    }
    
    $stock = $result->fetch_assoc()['p_qty'];
    
    if ($quantity > $stock) {
        return ['success' => false, 'message' => "Only $stock items available"];
    }
    
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['p_id'] == $product_id) {
            $_SESSION['cart'][$key]['quantity'] = $quantity;
            return ['success' => true, 'message' => 'Cart updated successfully'];
        }
    }
    
    return ['success' => false, 'message' => 'Product not found in cart'];
}

function removeFromCart($product_id) {
    if (!isset($_SESSION['cart'])) {
        return ['success' => false, 'message' => 'Cart is empty'];
    }
    
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['p_id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex array
            return ['success' => true, 'message' => 'Item removed from cart'];
        }
    }
    
    return ['success' => false, 'message' => 'Product not found in cart'];
}

function getCartTotal() {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return 0;
    }
    
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    
    return $total;
}

function getCartItemCount() {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return 0;
    }
    
    $count = 0;
    foreach ($_SESSION['cart'] as $item) {
        $count += $item['quantity'];
    }
    
    return $count;
}
?> 