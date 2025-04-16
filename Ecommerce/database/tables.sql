-- Create orders table
CREATE TABLE IF NOT EXISTS `tbl_orders` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `total_amount` decimal(10,2) NOT NULL,
    `shipping_address` text NOT NULL,
    `payment_status` enum('pending','completed','failed') NOT NULL DEFAULT 'pending',
    `status` enum('pending','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
    `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    CONSTRAINT `tbl_orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create order items table
CREATE TABLE IF NOT EXISTS `tbl_order_items` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `order_id` int(11) NOT NULL,
    `product_id` int(11) NOT NULL,
    `quantity` int(11) NOT NULL,
    `price` decimal(10,2) NOT NULL,
    `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `order_id` (`order_id`),
    KEY `product_id` (`product_id`),
    CONSTRAINT `tbl_order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `tbl_orders` (`id`) ON DELETE CASCADE,
    CONSTRAINT `tbl_order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `tbl_product` (`p_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; 