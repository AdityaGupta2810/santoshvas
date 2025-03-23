<?php include_once '../includes/header.php'?>
    <?php
 
    $error_message = '';
    $success_message = '';
    
    // Process message form submission
    if(isset($_POST['form1'])) {
        $valid = 1;
        if(empty($_POST['subject_text'])) {
            $valid = 0;
            $error_message .= 'Subject can not be empty\n';
        }
        if(empty($_POST['message_text'])) {
            $valid = 0;
            $error_message .= 'Message can not be empty\n';
        }
        if($valid == 1) {
            $subject_text = strip_tags($_POST['subject_text']);
            $message_text = strip_tags($_POST['message_text']);
    
            // Getting Customer Email Address
            $query = "SELECT * FROM tbl_customer WHERE cust_id=" . $_POST['cust_id'];
            $result = mysqli_query($db, $query);
            $row = mysqli_fetch_assoc($result);
            $cust_email = $row['cust_email'];
    
            $admin_email = "pharidwar2@gmail.com";
    
            $order_detail = '';
            $query = "SELECT * FROM tbl_payment WHERE payment_id='" . $_POST['payment_id'] . "'";
            $result = mysqli_query($db, $query);
            $row = mysqli_fetch_assoc($result);
            
            if($row['payment_method'] == 'PayPal') {
                $payment_details = '
Transaction Id: '.$row['txnid'].'<br>
                ';
            } elseif($row['payment_method'] == 'Stripe') {
                $payment_details = '
Transaction Id: '.$row['txnid'].'<br>
Card number: '.$row['card_number'].'<br>
Card CVV: '.$row['card_cvv'].'<br>
Card Month: '.$row['card_month'].'<br>
Card Year: '.$row['card_year'].'<br>
                ';
            } elseif($row['payment_method'] == 'Bank Deposit') {
                $payment_details = '
Transaction Details: <br>'.$row['bank_transaction_info'];
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
Payment Id: '.$row['payment_id'].'<br>
            ';
    
            $i=0;
            $query = "SELECT * FROM tbl_order WHERE payment_id='" . $_POST['payment_id'] . "'";
            $result = mysqli_query($db, $query);
            while($row = mysqli_fetch_assoc($result)) {
                $i++;
                $order_detail .= '
<br><b><u>Product Item '.$i.'</u></b><br>
Product Name: '.$row['product_name'].'<br>
Size: '.$row['size'].'<br>
Color: '.$row['color'].'<br>
Quantity: '.$row['quantity'].'<br>
Unit Price: '.$row['unit_price'].'<br>
                ';
            }
    
            $query = "INSERT INTO tbl_customer_message (subject, message, order_detail, cust_id) VALUES (
                '" . mysqli_real_escape_string($db, $subject_text) . "',
                '" . mysqli_real_escape_string($db, $message_text) . "',
                '" . mysqli_real_escape_string($db, $order_detail) . "',
                '" . mysqli_real_escape_string($db, $_POST['cust_id']) . "'
            )";
            mysqli_query($db, $query);
    
            // sending email
            $to_customer = $cust_email;
            $message = '
<html><body>
<h3>Message: </h3>
'.$message_text.'
<h3>Order Details: </h3>
'.$order_detail.'
</body></html>
';
            $headers = 'From: ' . $admin_email . "\r\n" .
                       'Reply-To: ' . $admin_email . "\r\n" .
                       'X-Mailer: PHP/' . phpversion() . "\r\n" . 
                       "MIME-Version: 1.0\r\n" . 
                       "Content-Type: text/html; charset=ISO-8859-1\r\n";
    
            // Sending email to admin                  
            mail($to_customer, $subject_text, $message, $headers);
            
            $success_message = 'Your email to customer is sent successfully.';
        }
    }
    
    // Handle payment status toggle
    if(isset($_GET['payment_id']) && isset($_GET['payment_task'])) {
        $payment_id = $_GET['payment_id'];
        $new_status = $_GET['payment_task']; // Will be either 'Completed' or 'Pending'
        
        // Update the payment status
        $query = "UPDATE tbl_payment SET payment_status = '{$new_status}' WHERE id = " . $payment_id;
        if(mysqli_query($db, $query)) {
            $success_message = "Payment status has been updated to {$new_status}";
            // Redirect to remove GET parameters after processing
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $error_message = 'Failed to update payment status: ' . mysqli_error($db);
        }
    }
    
    // Handle shipping status toggle
    if(isset($_GET['shipping_id']) && isset($_GET['shipping_task'])) {
        $shipping_id = $_GET['shipping_id'];
        $new_status = $_GET['shipping_task']; // Will be either 'Completed' or 'Pending'
        
        // Update the shipping status
        $query = "UPDATE tbl_payment SET shipping_status = '{$new_status}' WHERE id = " . $shipping_id;
        if(mysqli_query($db, $query)) {
            $success_message = "Shipping status has been updated to {$new_status}";
            // Redirect to remove GET parameters after processing
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $error_message = 'Failed to update shipping status: ' . mysqli_error($db);
        }
    }
    
    // Handle deletion of orders
    if(isset($_GET['delete_id'])) {
        $id = $_GET['delete_id'];
        
        // First get the payment_id to delete related orders
        $query = "SELECT payment_id FROM tbl_payment WHERE id = " . $id;
        $result = mysqli_query($db, $query);
        $row = mysqli_fetch_assoc($result);
        $payment_id = $row['payment_id'];
        
        // Delete from tbl_order
        $query = "DELETE FROM tbl_order WHERE payment_id = '" . $payment_id . "'";
        mysqli_query($db, $query);
        
        // Delete from tbl_payment
        $query = "DELETE FROM tbl_payment WHERE id = " . $id;
        if(mysqli_query($db, $query)) {
            $success_message = 'Order has been deleted successfully';
            // Redirect to remove GET parameters after processing
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $error_message = 'Failed to delete order: ' . mysqli_error($db);
        }
    }
    
    // Use sessions to store messages instead of displaying them on every refresh
    // session_start();
    
    // Display messages only once if they exist in session
    if(isset($_SESSION['error_message'])) {
        echo "<script>alert('".$_SESSION['error_message']."')</script>";
        unset($_SESSION['error_message']);
    }
    if(isset($_SESSION['success_message'])) {
        echo "<script>alert('".$_SESSION['success_message']."')</script>";
        unset($_SESSION['success_message']);
    }
    
    // Store current messages in session
    if($error_message != '') {
        $_SESSION['error_message'] = $error_message;
    }
    if($success_message != '') {
        $_SESSION['success_message'] = $success_message;
    }
    ?>

    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">View Orders</h1>
        </div>

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
                    while($row = mysqli_fetch_assoc($result)) {
                        $i++;
                        $bg_class = $row['payment_status'] == 'Pending' ? 'bg-red-100' : 'bg-green-100';
                    ?>
                        <tr class="<?php echo $bg_class; ?> border-b-4 border-gray-200">
                            <td class="px-4 py-2 border-r-4"><?php echo $i; ?></td>
                            <td class="px-4 py-2 border-r-4">
                                <p class="mb-1"><span class="font-bold">Id:</span> <?php echo $row['customer_id']; ?></p>
                                <p class="mb-1"><span class="font-bold">Name:</span> <?php echo $row['customer_name']; ?></p>
                                <p class="mb-3"><span class="font-bold">Email:</span> <?php echo $row['customer_email']; ?></p>
                                
                                <button data-modal-target="model-<?php echo $i; ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-2 rounded text-sm w-full mb-1">Send Message</button>
                                
                                <!-- Modal for sending message -->
                                <div id="model-<?php echo $i; ?>" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
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
                                                    <input type="text" name="subject_text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                </div>
                                                <div class="mb-4">
                                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="message">
                                                        Message
                                                    </label>
                                                    <textarea name="message_text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="6"></textarea>
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
                                $query1 = "SELECT * FROM tbl_order WHERE payment_id='" . $row['payment_id'] . "'";
                                $result1 = mysqli_query($db, $query1);
                                while($row1 = mysqli_fetch_assoc($result1)) {
                                    echo '<p class="mb-1"><span class="font-bold">Product:</span> '.$row1['product_name'].'</p>';
                                    echo '<p class="mb-1">(<span class="font-bold">Size:</span> '.$row1['size'];
                                    echo ', <span class="font-bold">Color:</span> '.$row1['color'].')</p>';
                                    echo '<p class="mb-4">(<span class="font-bold">Quantity:</span> '.$row1['quantity'];
                                    echo ', <span class="font-bold">Unit Price:</span> '.$row1['unit_price'].')</p>';
                                }
                                ?>
                            </td>
                            <td class="px-4 py-2 border-r-4">
                                <?php if($row['payment_method'] == 'PayPal'): ?>
                                    <p class="mb-1"><span class="font-bold">Payment Method:</span> <span class="text-red-600 font-bold"><?php echo $row['payment_method']; ?></span></p>
                                    <p class="mb-1"><span class="font-bold">Payment Id:</span> <?php echo $row['payment_id']; ?></p>
                                    <p class="mb-1"><span class="font-bold">Date:</span> <?php echo $row['payment_date']; ?></p>
                                    <p class="mb-1"><span class="font-bold">Transaction Id:</span> <?php echo $row['txnid']; ?></p>
                                <?php elseif($row['payment_method'] == 'Stripe'): ?>
                                    <p class="mb-1"><span class="font-bold">Payment Method:</span> <span class="text-red-600 font-bold"><?php echo $row['payment_method']; ?></span></p>
                                    <p class="mb-1"><span class="font-bold">Payment Id:</span> <?php echo $row['payment_id']; ?></p>
                                    <p class="mb-1"><span class="font-bold">Date:</span> <?php echo $row['payment_date']; ?></p>
                                    <p class="mb-1"><span class="font-bold">Transaction Id:</span> <?php echo $row['txnid']; ?></p>
                                    <p class="mb-1"><span class="font-bold">Card Number:</span> <?php echo $row['card_number']; ?></p>
                                    <p class="mb-1"><span class="font-bold">Card CVV:</span> <?php echo $row['card_cvv']; ?></p>
                                    <p class="mb-1"><span class="font-bold">Expire Month:</span> <?php echo $row['card_month']; ?></p>
                                    <p class="mb-1"><span class="font-bold">Expire Year:</span> <?php echo $row['card_year']; ?></p>
                                <?php elseif($row['payment_method'] == 'Bank Deposit'): ?>
                                    <p class="mb-1"><span class="font-bold">Payment Method:</span> <span class="text-red-600 font-bold"><?php echo $row['payment_method']; ?></span></p>
                                    <p class="mb-1"><span class="font-bold">Payment Id:</span> <?php echo $row['payment_id']; ?></p>
                                    <p class="mb-1"><span class="font-bold">Date:</span> <?php echo $row['payment_date']; ?></p>
                                    <p class="mb-1"><span class="font-bold">Transaction Information:</span></p>
                                    <p class="mb-1"><?php echo $row['bank_transaction_info']; ?></p>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-2 border-r-4"><?php echo $row['paid_amount']; ?></td>
                            <td class="px-4 py-2 border-r-4">
                                <p class="mb-4 font-bold"><?php echo $row['payment_status']; ?></p>
                                <?php
                                if($row['payment_status'] == 'Pending') {
                                    // If status is Pending, show button to mark as Completed
                                    ?>
                                    <a href="?payment_id=<?php echo $row['id']; ?>&payment_task=Completed" class="bg-green-500 hover:bg-green-600 text-white py-1 px-2 rounded text-sm block text-center mb-1 font-medium">Mark Complete</a>
                                    <?php
                                } else {
                                    // If status is Completed, show button to mark as Pending with better contrast
                                    ?>
                                    <a href="?payment_id=<?php echo $row['id']; ?>&payment_task=Pending" class="bg-red-500 hover:bg-red-600 text-white py-1 px-2 rounded text-sm block text-center mb-1 font-medium">Mark Pending</a>
                                    <?php
                                }
                                ?>
                            </td>
                            <td class="px-4 py-2 border-r-4">
                                <p class="mb-4 font-bold"><?php echo $row['shipping_status']; ?></p>
                                <?php
                                if($row['shipping_status'] == 'Pending') {
                                    // If status is Pending, show button to mark as Completed
                                    ?>
                                    <a href="?shipping_id=<?php echo $row['id']; ?>&shipping_task=Completed" class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-2 rounded text-sm block text-center mb-1 font-medium">Mark Complete</a>
                                    <?php
                                } else {
                                    // If status is Completed, show button to mark as Pending with better contrast
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
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Confirmation</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Sure you want to delete this item?
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
                e.preventDefault(); // Prevent default button behavior
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
        
        // Close modals when clicking outside the modal content
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
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                deleteConfirm.setAttribute('href', '?delete_id=' + id);
                deleteModal.classList.remove('hidden');
            });
        });
        
        deleteCancel.addEventListener('click', function() {
            deleteModal.classList.add('hidden');
        });
    });
    </script>
<?php include_once "../includes/footer.php" ?>