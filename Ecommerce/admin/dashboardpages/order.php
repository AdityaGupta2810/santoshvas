<?php include_once '../includes/header.php'?>
<?php

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize message variables
$error_message = '';
$success_message = '';

// Load PHPMailer
require __DIR__ . '/phpmailer/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Process message form submission
if(isset($_POST['form1'])) {
    $valid = 1;
    if(empty($_POST['subject_text'])) {
        $valid = 0;
        $error_message .= 'Subject cannot be empty<br>';
    }
    if(empty($_POST['message_text'])) {
        $valid = 0;
        $error_message .= 'Message cannot be empty<br>';
    }
    if(empty($_POST['cust_id']) || empty($_POST['payment_id'])) {
        $valid = 0;
        $error_message .= 'Missing customer or payment information<br>';
    }
    
    if($valid == 1) {
        $subject_text = strip_tags($_POST['subject_text']);
        $message_text = strip_tags($_POST['message_text']);
        $cust_id = isset($_POST['cust_id']) ? intval($_POST['cust_id']) : 0;
        $payment_id = isset($_POST['payment_id']) ? $_POST['payment_id'] : '';

        // Getting Customer Email Address
        $query = "SELECT * FROM tbl_customer WHERE cust_id=" . $cust_id;
        $result = mysqli_query($db, $query);
        
        if(!$result || mysqli_num_rows($result) == 0) {
            $error_message .= 'Customer information not found<br>';
            $valid = 0;
        } else {
            $row = mysqli_fetch_assoc($result);
            $cust_email = $row['cust_email'];

            // Prepare order details
            $order_detail = '';
            $query = "SELECT * FROM tbl_payment WHERE payment_id='" . mysqli_real_escape_string($db, $payment_id) . "'";
            $result = mysqli_query($db, $query);
            
            if(!$result || mysqli_num_rows($result) == 0) {
                $error_message .= 'Payment information not found<br>';
                $valid = 0;
            } else {
                $row = mysqli_fetch_assoc($result);
                
                $payment_details = '';
                if($row['payment_method'] == 'PayPal') {
                    $payment_details = 'Transaction Id: '.$row['txnid'].'<br>';
                } elseif($row['payment_method'] == 'Stripe') {
                    $payment_details = 'Transaction Id: '.$row['txnid'].'<br>Card number: '.$row['card_number'].'<br>';
                } elseif($row['payment_method'] == 'Bank Deposit') {
                    $payment_details = 'Transaction Details: <br>'.$row['bank_transaction_info'];
                }

                $order_detail .= '
                    Customer Name: '.$row['customer_name'].'<br>
                    Customer Email: '.$row['customer_email'].'<br>
                    Payment Method: '.$row['payment_method'].'<br>
                    Payment Date: '.$row['payment_date'].'<br>
                    Payment Details: <br>'.$payment_details.'<br>
                    Paid Amount: '.$row['paid_amount'].'<br>
                    Payment Status: '.$row['payment_status'].'<br>
                    Shipping Status: '.$row['shipping_status'].'<br>
                    Payment Id: '.$row['payment_id'].'<br>';

                $i=0;
                $query = "SELECT * FROM tbl_order WHERE payment_id='" . mysqli_real_escape_string($db, $payment_id) . "'";
                $result = mysqli_query($db, $query);
                if($result && mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        $i++;
                        $order_detail .= '
                            <br><b><u>Product Item '.$i.'</u></b><br>
                            Product Name: '.$row['product_name'].'<br>
                            Size: '.$row['size'].'<br>
                            Color: '.$row['color'].'<br>
                            Quantity: '.$row['quantity'].'<br>
                            Unit Price: '.$row['unit_price'].'<br>';
                    }
                } else {
                    $error_message .= 'No order details found for this payment<br>';
                    $valid = 0;
                }
            }
        }
        
        if($valid == 1) {
            // Prepare email content
            $message_body = '
                <html><body>
                <h3>Message: </h3>
                '.$message_text.'
                <h3>Order Details: </h3>
                '.$order_detail.'
                </body></html>';

            // Create PHPMailer instance
            $mail = new PHPMailer(true);
            $mail_sent = false; // Track if email was actually sent
            $db_insert_success = false; // Track if DB insert was successful

            try {
                // Server settings
                $mail->SMTPDebug  = SMTP::DEBUG_OFF;
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'adityagupta112040@gmail.com';
                $mail->Password   = 'qioy kvmi hjbp edqk';  // Consider using environment variables for credentials
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;
                
                // Timeout and SSL settings
                $mail->Timeout = 60; // Increased timeout to 60 seconds
                $mail->SMTPOptions = [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ]
                ];

                // Recipients
                $mail->setFrom('adityagupta112040@gmail.com', 'Santosh Vastralay');
                $mail->addAddress($cust_email);
                $mail->addReplyTo('adityagupta112040@gmail.com', 'Santosh Vastralay');

                // Content
                $mail->isHTML(true);
                $mail->Subject = $subject_text;
                $mail->Body    = $message_body;
                $mail->AltBody = strip_tags($message_text);

                // Send the email
                $mail->send();
                $mail_sent = true;
                
                // Only insert into database if email was sent successfully
                if ($mail_sent) {
                    // Insert into database
                    $query = "INSERT INTO tbl_customer_message (subject, message, order_detail, cust_id) VALUES (
                        '" . mysqli_real_escape_string($db, $subject_text) . "',
                        '" . mysqli_real_escape_string($db, $message_text) . "',
                        '" . mysqli_real_escape_string($db, $order_detail) . "',
                        " . intval($cust_id) . "
                    )";
                    
                    if (mysqli_query($db, $query)) {
                        $db_insert_success = true;
                    } else {
                        $error_message = "Database error: " . mysqli_error($db);
                    }
                }
                
                if ($mail_sent && $db_insert_success) {
                    $_SESSION['success_message'] = 'Email sent successfully!';
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                }
                
            } catch (Exception $e) {
                $error_message = "Email could not be sent. Error: " . $e->getMessage();
                error_log("PHPMailer Error: " . $e->getMessage());
            }
        }
    }
}

// Handle payment status toggle
if(isset($_GET['payment_id']) && isset($_GET['payment_task'])) {
    $payment_id = intval($_GET['payment_id']);
    $new_status = $_GET['payment_task'];
    
    if($new_status === 'Completed' || $new_status === 'Pending') {
        $query = "UPDATE tbl_payment SET payment_status = '" . mysqli_real_escape_string($db, $new_status) . "' WHERE id = " . $payment_id;
        if(mysqli_query($db, $query)) {
            $_SESSION['success_message'] = "Payment status updated to {$new_status}";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $_SESSION['error_message'] = 'Failed to update payment status: ' . mysqli_error($db);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    } else {
        $_SESSION['error_message'] = 'Invalid status value';
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Handle shipping status toggle
if(isset($_GET['shipping_id']) && isset($_GET['shipping_task'])) {
    $shipping_id = intval($_GET['shipping_id']);
    $new_status = $_GET['shipping_task'];
    
    if($new_status === 'Completed' || $new_status === 'Pending') {
        $query = "UPDATE tbl_payment SET shipping_status = '" . mysqli_real_escape_string($db, $new_status) . "' WHERE id = " . $shipping_id;
        if(mysqli_query($db, $query)) {
            $_SESSION['success_message'] = "Shipping status updated to {$new_status}";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $_SESSION['error_message'] = 'Failed to update shipping status: ' . mysqli_error($db);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    } else {
        $_SESSION['error_message'] = 'Invalid status value';
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Handle deletion of orders
if(isset($_GET['delete_id']) && isset($_SESSION['csrf_token']) && isset($_GET['token'])) {
    if($_SESSION['csrf_token'] === $_GET['token']) {
        $id = intval($_GET['delete_id']);
        
        // First get the payment_id to delete related orders
        $query = "SELECT payment_id FROM tbl_payment WHERE id = " . $id;
        $result = mysqli_query($db, $query);
        
        if($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $payment_id = $row['payment_id'];
            
            // Delete from tbl_order
            $query = "DELETE FROM tbl_order WHERE payment_id = '" . mysqli_real_escape_string($db, $payment_id) . "'";
            mysqli_query($db, $query);
            
            // Delete from tbl_payment
            $query = "DELETE FROM tbl_payment WHERE id = " . $id;
            if(mysqli_query($db, $query)) {
                $_SESSION['success_message'] = 'Order deleted successfully';
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                $_SESSION['error_message'] = 'Failed to delete order: ' . mysqli_error($db);
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
        } else {
            $_SESSION['error_message'] = 'Order not found';
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    } else {
        $_SESSION['error_message'] = 'Invalid security token';
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Generate CSRF token
if(!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check for session messages and display them
if(isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']); // Clear the message after retrieving it
}

if(isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Clear the message after retrieving it
}
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">View Orders</h1>
    </div>

    <?php if($error_message): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
    
    <?php if($success_message): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="px-4 py-2 text-left">#</th>
                        <th class="px-4 py-2 text-left">Customer</th>
                        <th class="px-4 py-2 text-left">Product Details</th>
                        <th class="px-4 py-2 text-left">Payment Information</th>
                        <th class="px-4 py-2 text-left">Paid Amount</th>
                        <th class="px-4 py-2 text-left">Payment Status</th>
                        <th class="px-4 py-2 text-left">Shipping Status</th>
                        <th class="px-4 py-2 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $i=0;
                $query = "SELECT * FROM tbl_payment ORDER by id DESC";
                $result = mysqli_query($db, $query);
                if($result && mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        $i++;
                        $bg_class = $row['payment_status'] == 'Pending' ? 'bg-red-100' : 'bg-green-100';
                ?>
                    <tr class="<?php echo $bg_class; ?> border-b-4 border-gray-200">
                        <td class="px-4 py-2 border-r-4"><?php echo $i; ?></td>
                        <td class="px-4 py-2 border-r-4">
                            <p class="mb-1"><span class="font-bold">Id:</span> <?php echo htmlspecialchars($row['customer_id']); ?></p>
                            <p class="mb-1"><span class="font-bold">Name:</span> <?php echo htmlspecialchars($row['customer_name']); ?></p>
                            <p class="mb-3"><span class="font-bold">Email:</span> <?php echo htmlspecialchars($row['customer_email']); ?></p>
                            
                            <button data-modal-target="model-<?php echo $i; ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-2 rounded text-sm w-full mb-1">Send Message</button>
                            
                            <!-- Modal for sending message -->
                            <div id="model-<?php echo $i; ?>" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                    <div class="mt-3">
                                        <div class="flex justify-between items-center pb-3">
                                            <h3 class="text-lg font-medium text-gray-900">Send Message</h3>
                                            <button type="button" class="modal-close text-gray-400 hover:text-gray-500">
                                                <span class="text-2xl">&times;</span>
                                            </button>
                                        </div>
                                        <form action="" method="post">
                                            <input type="hidden" name="cust_id" value="<?php echo $row['customer_id']; ?>">
                                            <input type="hidden" name="payment_id" value="<?php echo $row['payment_id']; ?>">
                                            <div class="mb-4">
                                                <label class="block text-gray-700 text-sm font-bold mb-2" for="subject">
                                                    Subject
                                                </label>
                                                <input type="text" name="subject_text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                            </div>
                                            <div class="mb-4">
                                                <label class="block text-gray-700 text-sm font-bold mb-2" for="message">
                                                    Message
                                                </label>
                                                <textarea name="message_text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="6" required></textarea>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <button type="submit" name="form1" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                                    Send Message
                                                </button>
                                                <button type="button" class="modal-close bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                                    Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-2 border-r-4">
                            <?php
                            $query1 = "SELECT * FROM tbl_order WHERE payment_id='" . mysqli_real_escape_string($db, $row['payment_id']) . "'";
                            $result1 = mysqli_query($db, $query1);
                            if($result1 && mysqli_num_rows($result1) > 0) {
                                while($row1 = mysqli_fetch_assoc($result1)) {
                                    echo '<p class="mb-1"><span class="font-bold">Product:</span> '.htmlspecialchars($row1['product_name']).'</p>';
                                    echo '<p class="mb-1">(<span class="font-bold">Size:</span> '.htmlspecialchars($row1['size']);
                                    echo ', <span class="font-bold">Color:</span> '.htmlspecialchars($row1['color']).')</p>';
                                    echo '<p class="mb-4">(<span class="font-bold">Quantity:</span> '.htmlspecialchars($row1['quantity']);
                                    echo ', <span class="font-bold">Unit Price:</span> '.htmlspecialchars($row1['unit_price']).')</p>';
                                }
                            } else {
                                echo '<p class="mb-1">No product details available</p>';
                            }
                            ?>
                        </td>
                        <td class="px-4 py-2 border-r-4">
                            <?php if($row['payment_method'] == 'PayPal'): ?>
                                <p class="mb-1"><span class="font-bold">Payment Method:</span> <span class="text-red-600 font-bold"><?php echo htmlspecialchars($row['payment_method']); ?></span></p>
                                <p class="mb-1"><span class="font-bold">Payment Id:</span> <?php echo htmlspecialchars($row['payment_id']); ?></p>
                                <p class="mb-1"><span class="font-bold">Date:</span> <?php echo htmlspecialchars($row['payment_date']); ?></p>
                                <p class="mb-1"><span class="font-bold">Transaction Id:</span> <?php echo htmlspecialchars($row['txnid']); ?></p>
                            <?php elseif($row['payment_method'] == 'Stripe'): ?>
                                <p class="mb-1"><span class="font-bold">Payment Method:</span> <span class="text-red-600 font-bold"><?php echo htmlspecialchars($row['payment_method']); ?></span></p>
                                <p class="mb-1"><span class="font-bold">Payment Id:</span> <?php echo htmlspecialchars($row['payment_id']); ?></p>
                                <p class="mb-1"><span class="font-bold">Date:</span> <?php echo htmlspecialchars($row['payment_date']); ?></p>
                                <p class="mb-1"><span class="font-bold">Transaction Id:</span> <?php echo htmlspecialchars($row['txnid']); ?></p>
                                <p class="mb-1"><span class="font-bold">Card Number:</span> <?php echo htmlspecialchars($row['card_number']); ?></p>
                                <p class="mb-1"><span class="font-bold">Card CVV:</span> <?php echo htmlspecialchars($row['card_cvv']); ?></p>
                                <p class="mb-1"><span class="font-bold">Expire Month:</span> <?php echo htmlspecialchars($row['card_month']); ?></p>
                                <p class="mb-1"><span class="font-bold">Expire Year:</span> <?php echo htmlspecialchars($row['card_year']); ?></p>
                            <?php elseif($row['payment_method'] == 'Bank Deposit'): ?>
                                <p class="mb-1"><span class="font-bold">Payment Method:</span> <span class="text-red-600 font-bold"><?php echo htmlspecialchars($row['payment_method']); ?></span></p>
                                <p class="mb-1"><span class="font-bold">Payment Id:</span> <?php echo htmlspecialchars($row['payment_id']); ?></p>
                                <p class="mb-1"><span class="font-bold">Date:</span> <?php echo htmlspecialchars($row['payment_date']); ?></p>
                                <p class="mb-1"><span class="font-bold">Transaction Information:</span></p>
                                <p class="mb-1"><?php echo nl2br(htmlspecialchars($row['bank_transaction_info'])); ?></p>
                            <?php else: ?>
                                <p class="mb-1">Payment method: <?php echo htmlspecialchars($row['payment_method'] ?? 'Unknown'); ?></p>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-2 border-r-4"><?php echo htmlspecialchars($row['paid_amount']); ?></td>
                        <td class="px-4 py-2 border-r-4">
                            <p class="mb-4 font-bold"><?php echo htmlspecialchars($row['payment_status']); ?></p>
                            <?php
                            if($row['payment_status'] == 'Pending') {
                                ?>
                                <a href="?payment_id=<?php echo $row['id']; ?>&payment_task=Completed" class="bg-green-500 hover:bg-green-600 text-white py-1 px-2 rounded text-sm block text-center mb-1 font-medium">Mark Complete</a>
                                <?php
                            } else {
                                ?>
                                <a href="?payment_id=<?php echo $row['id']; ?>&payment_task=Pending" class="bg-red-500 hover:bg-red-600 text-white py-1 px-2 rounded text-sm block text-center mb-1 font-medium">Mark Pending</a>
                                <?php
                            }
                            ?>
                        </td>
                        <td class="px-4 py-2 border-r-4">
                            <p class="mb-4 font-bold"><?php echo htmlspecialchars($row['shipping_status']); ?></p>
                            <?php
                            if($row['shipping_status'] == 'Pending') {
                                ?>
                                <a href="?shipping_id=<?php echo $row['id']; ?>&shipping_task=Completed" class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-2 rounded text-sm block text-center mb-1 font-medium">Mark Complete</a>
                                <?php
                            } else {
                                ?>
                                <a href="?shipping_id=<?php echo $row['id']; ?>&shipping_task=Pending" class="bg-purple-500 hover:bg-purple-600 text-white py-1 px-2 rounded text-sm block text-center mb-1 font-medium border border-purple-600">Mark Pending</a>
                                <?php
                            }
                            ?>
                        </td>
                        <td class="px-4 py-2 border-r-4">
                            <button data-id="<?php echo $row['id']; ?>" class="delete-btn bg-red-500 hover:bg-red-600 text-white py-1 px-2 rounded text-sm block text-center w-full">Delete</button>
                        </td>
                    </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="8" class="px-4 py-2 text-center">No orders found</td></tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Confirmation</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Are you sure you want to delete this order? This action cannot be undone.
                </p>
            </div>
            <div class="flex justify-center gap-4 mt-3 pb-3">
                <button id="delete-cancel" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Cancel
                </button>
                <a id="delete-confirm" href="#" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                    Delete
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Modal functionality
document.addEventListener('DOMContentLoaded', function() {
    // Send message modal
    const modalButtons = document.querySelectorAll('[data-modal-target]');
    const modalCloses = document.querySelectorAll('.modal-close');
    
    modalButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const modalId = this.getAttribute('data-modal-target');
            document.getElementById(modalId).classList.remove('hidden');
        });
    });
    
    modalCloses.forEach(close => {
        close.addEventListener('click', function() {
            const modal = this.closest('[id^="model-"]');
            if (modal) {
                modal.classList.add('hidden');
            }
        });
    });
    
    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        document.querySelectorAll('[id^="model-"]').forEach(modal => {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
        
        if (event.target === document.getElementById('delete-modal')) {
            document.getElementById('delete-modal').classList.add('hidden');
        }
    });
        
    // Delete confirmation modal
    const deleteButtons = document.querySelectorAll('.delete-btn');
    const deleteModal = document.getElementById('delete-modal');
    const deleteCancel = document.getElementById('delete-cancel');
    const deleteConfirm = document.getElementById('delete-confirm');
    const csrfToken = '<?php echo $_SESSION['csrf_token']; ?>';
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            deleteConfirm.setAttribute('href', '?delete_id=' + id + '&token=' + csrfToken);
            deleteModal.classList.remove('hidden');
        });
    });
    
    deleteCancel.addEventListener('click', function() {
        deleteModal.classList.add('hidden');
    });
});
</script>
<?php include_once "../includes/footer.php" ?>