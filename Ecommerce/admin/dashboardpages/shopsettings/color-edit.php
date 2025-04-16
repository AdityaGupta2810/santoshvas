<?php
include_once '../../includes/header.php';


// Initialize variables
$colorName = '';
$error = '';
$success = '';
$colorId = isset($_GET['id']) ? (int)$_GET['id'] : 0;



// Check connection
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get color name from form
    $colorName = trim($_POST['color_name']);
    $colorId = (int)$_POST['id'];
    
    // Validate form input
    if (empty($colorName)) {
        $error = 'Color name is required.';
    } else {
        // Escape input to prevent SQL injection
        $colorName = mysqli_real_escape_string($db, $colorName);
        
        // Check if color already exists (except current color)

        $checkQuery = "SELECT * FROM tbl_color WHERE color_name = '$colorName' AND id != $colorId";
        $checkResult = mysqli_query($db, $checkQuery);
        
        if (mysqli_num_rows($checkResult) > 0) {
            $error = 'This color already exists.';
        } else {
            // Update color
            $updateQuery = "UPDATE tbl_color SET color_name = '$colorName' WHERE id = $colorId";
            
            if (mysqli_query($db, $updateQuery)) {
                $success = 'Color updated successfully.';
            } else {
                $error = 'Error updating color: ' . mysqli_error($db);
            }
        }
    }
} else {
    // Fetch color data for editing
    if ($colorId > 0) {
        $fetchQuery = "SELECT * FROM tbl_color WHERE id = $colorId";
        $fetchResult = mysqli_query($db, $fetchQuery);
        
        if (mysqli_num_rows($fetchResult) > 0) {
            $colorData = mysqli_fetch_assoc($fetchResult);
            $colorName = $colorData['color_name'];
        } else {
            $error = 'Color not found.';
        }
    } else {
        $error = 'Invalid color ID.';
    }
}
?>

<div class="container mx-auto p-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold flex items-center">
            <i class="fas fa-circle-dot mr-2"></i> Edit Color
        </h1>
        <a href="color.php" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">
            Back to Colors
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
    
    <!-- Edit Color Form -->
    <div class="bg-white rounded-lg shadow-md p-6 max-w-lg mx-auto">
        <form method="POST" action="">
            <input type="hidden" name="color_id" value="<?php echo $colorId; ?>">
            <div class="mb-4">
                <label for="color_name" class="block text-sm font-medium mb-1">Color Name</label>
                <input type="text" id="color_name" name="color_name" value="<?php echo htmlspecialchars($colorName); ?>" 
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="flex justify-end">
                <a href="view-colors.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded mr-2 hover:bg-gray-400">Cancel</a>
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