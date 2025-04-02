<?php
session_start();

if (isset($_GET['id'])) {
    $product_id = (int)$_GET['id'];
    
    for ($i = 0; $i < count($_SESSION['cart_p_id']); $i++) {
        if ($_SESSION['cart_p_id'][$i] == $product_id) {
            array_splice($_SESSION['cart_p_id'], $i, 1);
            array_splice($_SESSION['cart_p_name'], $i, 1);
            array_splice($_SESSION['cart_p_current_price'], $i, 1);
            array_splice($_SESSION['cart_p_featured_photo'], $i, 1);
            array_splice($_SESSION['cart_p_qty'], $i, 1);
            break;
        }
    }
}

header('Location: cart.php');
exit;
?>