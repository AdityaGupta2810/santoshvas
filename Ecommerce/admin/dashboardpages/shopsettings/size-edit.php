<?php
include_once '../../includes/header.php';


// Initialize variables
$sizeName = '';
$error = '';
$success = '';
$sizeId = isset($_GET['id']) ? (int)$_GET['id'] : 0;



// Check connection
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get size name from form
    $sizeName = trim($_POST['size_name']);
    $sizeId = (int)$_POST['id'];
    
    // Validate form input
    if (empty($sizeName)) {
        $error = 'size name is required.';
    } else {
        // Escape input to prevent SQL injection
        $sizeName = mysqli_real_escape_string($db, $sizeName);
        
        // Check if size already exists (except current size)

        $checkQuery = "SELECT * FROM tbl_size WHERE size_name = '$sizeName' AND id != $sizeId";
        $checkResult = mysqli_query($db, $checkQuery);
        
        if (mysqli_num_rows($checkResult) > 0) {
            $error = 'This size already exists.';
        } else {
            // Update size
            $updateQuery = "UPDATE tbl_size SET size_name = '$sizeName' WHERE id = $sizeId";
            
            if (mysqli_query($db, $updateQuery)) {
                $success = 'size updated successfully.';
            } else {
                $error = 'Error updating size: ' . mysqli_error($db);
            }
        }
    }
} else {
    // Fetch size data for editing
    if ($sizeId > 0) {
        $fetchQuery = "SELECT * FROM tbl_size WHERE id = $SizeId";
        $fetchResult = mysqli_query($db, $fetchQuery);
        
        if (mysqli_num_rows($fetchResult) > 0) {
            $sizeData = mysqli_fetch_assoc($fetchResult);
            $sizeName = $sizeData['size_name'];
        } else {
            $error = 'size not found.';
        }
    } else {
        $error = 'Invalid size ID.';
    }
}
?>

<div class="container mx-auto p-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold flex items-center">
            <i class="fas fa-circle-dot mr-2"></i> Edit size
        </h1>
        <a href="size.php" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">
            Back to sizes
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
    
    <!-- Edit size Form -->
    <div class="bg-white rounded-lg shadow-md p-6 max-w-lg mx-auto">
        <form method="POST" action="">
            <input type="hidden" name="size_id" value="<?php echo $sizeId; ?>">
            <div class="mb-4">
                <label for="size_name" class="block text-sm font-medium mb-1">size Name</label>
                <input type="text" id="size_name" name="size_name" value="<?php echo htmlspecialchars($sizeName); ?>" 
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="flex justify-end">
                <a href="view-sizes.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded mr-2 hover:bg-gray-400">Cancel</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
            </div>
        </form>
    </div>
</div>

<?php
// Close the database connection
mysqli_close($db);
include_once '../../includes/footer.php';
?>