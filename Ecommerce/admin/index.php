<?php include_once('./includes/header.php');  ?>

<?php
$statement = $db->prepare("SELECT * FROM tbl_top_category");
$statement->execute();
$statement->store_result();
$total_top_category = $statement->num_rows;

$statement = $db->prepare("SELECT * FROM tbl_mid_category");
$statement->execute();
$statement->store_result();
$total_mid_category = $statement->num_rows;

$statement = $db->prepare("SELECT * FROM tbl_end_category");
$statement->execute();
$statement->store_result();
$total_end_category = $statement->num_rows;

$statement = $db->prepare("SELECT * FROM tbl_product");
$statement->execute();
$statement->store_result();
$total_product = $statement->num_rows;

$stmt = $db->prepare("SELECT 
    COUNT(CASE WHEN status = 'pending' OR status = 'processing' THEN 1 END) as pending_orders,
    COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed_orders
    FROM tbl_orders");
$stmt->execute();
$result = $stmt->get_result();
$order_stats = $result->fetch_assoc();
$pending_orders = $order_stats['pending_orders'];
$completed_orders = $order_stats['completed_orders'];


// Handle todo list actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add new task
    if (isset($_POST['add_task']) && !empty($_POST['task'])) {
        $task = trim($_POST['task']);
        $admin_id = $_SESSION['admin_id'];
        
        $stmt = $db->prepare("INSERT INTO tbl_todo (task, admin_id) VALUES (?, ?)");
        if ($stmt) {
            $stmt->bind_param("si", $task, $admin_id);
            $stmt->execute();
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
    
    // Toggle task status
    if (isset($_POST['toggle_task']) && isset($_POST['task_id'])) {
        $task_id = (int)$_POST['task_id'];
        $admin_id = $_SESSION['admin_id'];
        
        $stmt = $db->prepare("UPDATE tbl_todo SET status = CASE WHEN status = 'pending' THEN 'completed' ELSE 'pending' END WHERE id = ? AND admin_id = ?");
        if ($stmt) {
            $stmt->bind_param("ii", $task_id, $admin_id);
            $stmt->execute();
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
    
    // Delete task
    if (isset($_POST['delete_task']) && isset($_POST['task_id'])) {
        $task_id = (int)$_POST['task_id'];
        $admin_id = $_SESSION['admin_id'];
        
        $stmt = $db->prepare("DELETE FROM tbl_todo WHERE id = ? AND admin_id = ?");
        if ($stmt) {
            $stmt->bind_param("ii", $task_id, $admin_id);
            $stmt->execute();
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
}

// Get total pending orders count
$stmt = $db->prepare("SELECT COUNT(*) as count FROM tbl_orders WHERE status != 'completed'");
$stmt->execute();
$result = $stmt->get_result();
$pending_orders = $result->fetch_assoc()['count'];
?>
  
            <div class="head-title flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <div class="left">
                    <h1 class="text-3xl font-semibold text-gray-700">Dashboard</h1>
                    <ul class="breadcrumb flex items-center mt-2">
                        <li><a href="#" class="text-gray-500">Dashboard</a></li>
                        <li class="mx-2 text-gray-500"><i class='bx bx-chevron-right'></i></li>
                        <li><a href="../index.php" class="text-blue-500">Home</a></li>
                    </ul>
                </div>
                <!-- <a href="#" class="btn-download h-10 px-4 bg-blue-500 text-white rounded-full flex items-center mt-4 md:mt-0 w-max">
                    <i class='bx bxs-cloud-download mr-2'></i>
                    <span>Download PDF</span>
                </a> -->
            </div>

             <ul class="box-info grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <li class="p-6 bg-white rounded-xl flex items-center shadow-sm">
                    <i class='bx bx-loader-circle text-4xl text-blue-500'></i>
                    <div class="ml-4">
                        <h3 class="text-2xl font-semibold text-gray-700"><?php echo $pending_orders; ?></h3>
                        <p class="text-gray-500">Pending Orders</p>
                    </div>
                </li>

                <li class="p-6 bg-white rounded-xl flex items-center shadow-sm">
                    <i class='bx  bxs-calendar-check text-4xl text-rose-500'></i>
                    <div class="ml-4">
                        <h3 class="text-2xl font-semibold text-gray-700"><?php echo $completed_orders; ?></h3>
                        <p class="text-gray-500">Total Orders</p>
                    </div>
                </li>

                <li class="p-6 bg-white rounded-xl flex items-center shadow-sm">
                <i class='bx bxs-package text-4xl text-blue-500' ></i>
                    <div class="ml-4">
                        <h3 class="text-2xl font-semibold text-gray-700"><?php echo $total_product; ?></h3>
                        <p class="text-gray-500">Total Products</p>
                    </div>
                </li>
                <!-- <li class="p-6 bg-white rounded-xl flex items-center shadow-sm">
                    <i class='bx bxs-group text-4xl text-yellow-500'></i>
                    <div class="ml-4">
                        <h3 class="text-2xl font-semibold text-gray-700">4</h3>
                        <p class="text-gray-500">Visitors</p>
                    </div>
                </li>
                <li class="p-6 bg-white rounded-xl flex items-center shadow-sm">
                    <i class='bx bxs-dollar-circle text-4xl text-green-500'></i>
                    <div class="ml-4">
                        <h3 class="text-2xl font-semibold text-gray-700">$2,543</h3>
                        <p class="text-gray-500">Total Sales</p>
                    </div>
                </li> -->
                <li class="p-6 bg-white rounded-xl flex items-center shadow-sm">
                    <i class='bx bxs-category text-4xl text-green-500'></i>
                    <div class="ml-4">
                        <h3 class="text-2xl font-semibold text-gray-700"><?php echo $total_top_category; ?></h3>
                        <p class="text-gray-500">Total Top Level Categories</p>
                    </div>
                </li>
                <li class="p-6 bg-white rounded-xl flex items-center shadow-sm">
                    <i class='bx bxs-category-alt text-4xl text-blue-500'></i>
                    <div class="ml-4">
                        <h3 class="text-2xl font-semibold text-gray-700"><?php echo $total_mid_category; ?></h3>
                        <p class="text-gray-500">Total Mid Level Categories</p>
                    </div>
                </li>

                <li class="p-6 bg-white rounded-xl flex items-center shadow-sm">
                    <i class='bx bxs-category text-4xl text-red-500'></i>
                    <div class="ml-4">
                        <h3 class="text-2xl font-semibold text-gray-700"><?php echo $total_end_category; ?></h3>
                        <p class="text-gray-500">Total End Level Categories</p>
                    </div>
                </li>
            </ul>

            <div class="table-data grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="order bg-white rounded-xl p-6 shadow-sm">
                    <div class="head flex items-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-700 mr-auto">Recent Orders</h3>
                        <a href="dashboardpages/ordermanagement.php" class="text-blue-500 hover:text-blue-600">
                            View All
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="pb-3 text-left text-gray-500">Customer</th>
                                    <th class="pb-3 text-left text-gray-500">Order Date</th>
                                    <th class="pb-3 text-left text-gray-500">Amount</th>
                                    <th class="pb-3 text-left text-gray-500">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch recent orders that are not completed
                                $stmt = $db->prepare("
                                    SELECT o.*, u.name as customer_name 
                                    FROM tbl_orders o 
                                    JOIN tbl_users u ON o.user_id = u.id 
                                    WHERE o.status != 'completed' 
                                    ORDER BY o.created_at DESC 
                                    LIMIT 5
                                ");
                                $stmt->execute();
                                $recent_orders = $stmt->get_result();

                                while ($order = $recent_orders->fetch_assoc()):
                                    $status_class = '';
                                    switch($order['status']) {
                                        case 'pending':
                                            $status_class = 'bg-yellow-100 text-yellow-800';
                                            break;
                                        case 'processing':
                                            $status_class = 'bg-blue-100 text-blue-800';
                                            break;
                                        case 'cancelled':
                                            $status_class = 'bg-red-100 text-red-800';
                                            break;
                                    }
                                ?>
                                    <tr class="hover:bg-gray-100">
                                        <td class="py-3 flex items-center">
                                            <div class="w-9 h-9 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                                <?php echo strtoupper(substr($order['customer_name'], 0, 1)); ?>
                                            </div>
                                            <p class="ml-2"><?php echo htmlspecialchars($order['customer_name']); ?></p>
                                        </td>
                                        <td class="py-3"><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                                        <td class="py-3">₹<?php echo number_format($order['total_amount'], 2); ?></td>
                                        <td class="py-3">
                                            <span class="px-2 py-1 <?php echo $status_class; ?> rounded-full text-xs font-medium">
                                                <?php echo ucfirst($order['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>

                                <?php if ($recent_orders->num_rows === 0): ?>
                                    <tr>
                                        <td colspan="4" class="py-4 text-center text-gray-500">
                                            No pending orders found
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="todo bg-white rounded-xl p-6 shadow-sm">
                    <div class="head flex items-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-700 mr-auto">To-Do List</h3>
                        <button onclick="toggleAddTask()" class="text-blue-500 hover:text-blue-600">
                            <i class='bx bx-plus-circle text-2xl'></i>
                        </button>
                    </div>

                    <!-- Add Task Form -->
                    <div id="addTaskForm" class="hidden mb-4">
                        <form method="POST" class="flex items-center gap-2">
                            <input type="text" name="task" 
                                   class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2"
                                   placeholder="Enter new task" required>
                            <button type="submit" name="add_task" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                                Add
                            </button>
                        </form>
                    </div>

                    <!-- Tasks List -->
                    <div class="overflow-y-auto max-h-[300px]">
                        <?php
                        // Fetch tasks for the current admin
                        $admin_id = $_SESSION['admin_id'];
                        $stmt = $db->prepare("SELECT * FROM tbl_todo WHERE admin_id = ? ORDER BY created_at DESC");
                        $stmt->bind_param("i", $admin_id);
                        $stmt->execute();
                        $tasks = $stmt->get_result();
                        ?>
                        
                        <div class="space-y-2">
                            <?php while ($task = $tasks->fetch_assoc()): ?>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition duration-200">
                                    <div class="flex items-center gap-3">
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                            <button type="submit" name="toggle_task" 
                                                    class="text-2xl text-gray-400 hover:text-green-500 transition duration-200">
                                                <!-- <?php if ($task['status'] === 'completed'): ?>
                                                    <i class='bx bx-check-circle text-green-500'></i>
                                                <?php else: ?>
                                                    <i class='bx bx-circle'></i>
                                                <?php endif; ?> -->
                                            </button>
                                        </form>
                                        <!-- <span class="<?php echo $task['status'] === 'completed' ? 'line-through text-gray-400' : 'text-gray-700'; ?>"> -->
                                            <?php echo htmlspecialchars($task['task']); ?>
                                        </span>
                                    </div>
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                        <button type="submit" name="delete_task" 
                                                class="text-red-400 hover:text-red-500 transition duration-200">
                                            <i class='bx bx-trash text-xl'></i>
                                        </button>
                                    </form>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    
    </section>

    <script>
  


// Functions to toggle dropdown menus
function toggleDropdown(element) {
    // Close all other dropdowns
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        if (menu !== element.nextElementSibling) {
            menu.classList.add('hidden');
        }
    });
    
    // Toggle this dropdown
    const dropdown = element.nextElementSibling;
    dropdown.classList.toggle('hidden');
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function closeDropdown(e) {
        if (!element.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
            document.removeEventListener('click', closeDropdown);
        }
    });
}


// Close all dropdowns when clicking outside
document.addEventListener('click', function(e) {
    const dropdowns = document.querySelectorAll('.dropdown-menu');
    dropdowns.forEach(dropdown => {
        if (!dropdown.previousElementSibling.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
});

function toggleAddTask() {
    const form = document.getElementById('addTaskForm');
    form.classList.toggle('hidden');
    if (!form.classList.contains('hidden')) {
        form.querySelector('input').focus();
    }
}

</script>
   
    <?php include_once('./includes/footer.php'); ?>
  
