<?php
include_once '../../includes/header.php';
// include_once 'db_connect.php'; // Add database connection include

// Initialize variables
$sizeName = '';
$error = '';
$success = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get Size name from form
    $sizeName = trim($_POST['size_name']);
    
    // Validate form input
    if (empty($sizeName)) {
        $error = 'Size name is required.';
    } else {
        // Connect to database
        // $db = mysqli_connect($host, $username, $password, "santoshvastralay");
        
        // Check connection
        if (!$db) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        // Escape input to prevent SQL injection
        $sizeName = mysqli_real_escape_string($db, $sizeName);
        
        // Check if Size already exists
        $checkQuery = "SELECT * FROM tbl_size WHERE Size_name = '$sizeName'";
        $checkResult = mysqli_query($db, $checkQuery);
        
        if (mysqli_num_rows($checkResult) > 0) {
            $error = 'This Size already exists.';
        } else {
            // Insert new Size
            $insertQuery = "INSERT INTO tbl_size (size_name) VALUES ('$sizeName')";
            
            if (mysqli_query($db, $insertQuery)) {
                $success = 'Size added successfully.';
                $sizeName = ''; // Clear form
            } else {
                $error = 'Error adding Size: ' . mysqli_error($db);
            }
        }
        
        // Close connection
        mysqli_close($db);
    }
}
?>

<div class="container mx-auto p-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold flex items-center">
            <i class="fas fa-circle-dot mr-2"></i> Add New Size
        </h1>
        <a href="size.php" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">
            Back to Sizes
        </a>
    </div>
    
    <div class="border-t border-b border-gray-300 my-4"></div>
    
    <!-- Success/Error Messages -->
    <?php if (!empty($success)): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
        <p><?php echo $success; ?></p>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($error)): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
        <p><?php echo $error; ?></p>
    </div>
    <?php endif; ?>
    
    <!-- Add Size Form -->
    <div class="bg-white rounded-lg shadow-md p-6 max-w-lg mx-auto">
        <form method="POST" action="">
            <div class="mb-4">
                <label for="size_name" class="block text-sm font-medium mb-1">Size Name</label>
                <input type="text" id="size_name" name="size_name" value="<?php echo htmlspecialchars($sizeName); ?>" 
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="flex justify-end">
                <a href="size.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded mr-2 hover:bg-gray-400">Cancel</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>