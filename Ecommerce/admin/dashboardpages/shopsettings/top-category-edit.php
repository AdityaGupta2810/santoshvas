<?php
include_once '../../includes/header.php';

// use to report the error if any
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize variables
$tcatName = '';
$showOnMenu = '';
$error = '';
$success = '';
$tcatId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get category name from form
    $tcatName = trim($_POST['tcat_name']);
    $tcatId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $showOnMenu = isset($_POST['show_on_menu']) ? $_POST['show_on_menu'] : '0';
    
    // Validate form input
    if (empty($tcatName)) {
        $error = 'Category name is required.';
    } else {
        // Escape input to prevent SQL injection
        $tcatName = mysqli_real_escape_string($db, $tcatName);
        $showOnMenu = mysqli_real_escape_string($db, $showOnMenu);
        
        // Check if category already exists (except current category)
        $checkQuery = "SELECT * FROM tbl_top_category WHERE tcat_name = '$tcatName' AND id != $tcatId";
        $checkResult = mysqli_query($db, $checkQuery);
        
        if (mysqli_num_rows($checkResult) > 0) {
            $error = 'This category already exists.';
        } else {
            // Update category
            $updateQuery = "UPDATE tbl_top_category SET tcat_name = '$tcatName', show_on_menu = '$showOnMenu' WHERE id = $tcatId";
            
            if (mysqli_query($db, $updateQuery)) {
                $success = 'Top Level Category updated successfully.';
            } else {
                $error = 'Error updating Top Level Category: ' . mysqli_error($db);
            }
        }
    }
} else {
    // Fetch category data for editing
    if ($tcatId > 0) {
        $fetchQuery = "SELECT * FROM tbl_top_category WHERE id = $tcatId";
        $fetchResult = mysqli_query($db, $fetchQuery);
        
        if (mysqli_num_rows($fetchResult) > 0) {
            $categoryData = mysqli_fetch_assoc($fetchResult);
            $tcatName = $categoryData['tcat_name'];
            $showOnMenu = $categoryData['show_on_menu']; // Added this line
        } else {
            $error = 'Category not found.';
        }
    } else {
        $error = 'Invalid Category ID.';
    }
}
?>

<div class="container mx-auto p-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold flex items-center">
            <i class="fas fa-circle-dot mr-2"></i> Edit Top Level Category
        </h1>
        <a href="top-category.php" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">
            Back to Top Level Categories
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
    
    <!-- Edit Category Form -->
    <div class="bg-white rounded-lg shadow-md p-6 max-w-lg mx-auto">
        <form method="POST" action="">
            <input type="hidden" name="id" value="<?php echo $tcatId; ?>">

            <div class="mb-4">
                <label for="tcat_name" class="block text-sm font-medium mb-1">Top Level Category Name</label>
                <input type="text" id="tcat_name" name="tcat_name" value="<?php echo htmlspecialchars($tcatName); ?>" 
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="show_on_menu" class="block text-sm font-medium mb-1">Show on menu</label>
                <select name="show_on_menu" id="show_on_menu" class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="1" <?php echo ($showOnMenu == "1") ? "selected" : ""; ?>>Yes</option>
                    <option value="0" <?php echo ($showOnMenu == "0") ? "selected" : ""; ?>>No</option>
                </select>
            </div>
            <div class="flex justify-end">
                <a href="top-category.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded mr-2 hover:bg-gray-400">Cancel</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
            </div>
        </form>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>