<?php
include_once '../../includes/header.php';

// Use to report the error if any
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize variables
$mcatName = '';
$tcatId = '';
$error = '';
$success = '';
$mcatId = isset($_GET['mcat_id']) ? (int)$_GET['mcat_id'] : 0;

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get category name from form
    $mcatName = trim($_POST['mcat_name']); // Fixed: removed space in field name
    $tcatId = isset($_POST['tcat_id']) ? (int)$_POST['tcat_id'] : 0;
    $mcatId = isset($_POST['mcat_id']) ? (int)$_POST['mcat_id'] : 0;
    
    // Validate form input
    if (empty($mcatName)) {
        $error = 'Mid Level Category name is required.';
    } elseif (empty($tcatId)) {
        $error = 'Top Level Category selection is required.';
    } else {
        // Escape input to prevent SQL injection
        $mcatName = mysqli_real_escape_string($db, $mcatName);
        
        // Check if category already exists (except current category)
        $checkQuery = "SELECT * FROM tbl_mid_category WHERE mcat_name = '$mcatName' AND tcat_id = $tcatId AND mcat_id != $mcatId";
        $checkResult = mysqli_query($db, $checkQuery);
        
        if (mysqli_num_rows($checkResult) > 0) {
            $error = 'This mid level category already exists under the selected top category.';
        } else {
            // Update category
            $updateQuery = "UPDATE tbl_mid_category SET mcat_name = '$mcatName', tcat_id = $tcatId WHERE mcat_id = $mcatId";
            
            if (mysqli_query($db, $updateQuery)) {
                $success = 'Mid Level Category updated successfully.';
            } else {
                $error = 'Error updating Mid Level Category: ' . mysqli_error($db);
            }
        }
    }
} else {
    // Fetch category data for editing
    if ($mcatId > 0) {
        $query = "SELECT * FROM tbl_mid_category WHERE mcat_id = $mcatId";
        $result = mysqli_query($db, $query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $categoryData = mysqli_fetch_assoc($result);
            $mcatName = $categoryData['mcat_name'];
            $tcatId = $categoryData['tcat_id'];
        } else {
            $error = 'Mid Level Category not found.';
        }
    } else {
        $error = 'Invalid Mid Level Category ID.';
    }
}

// Get top-level categories for dropdown
$topCatsQuery = "SELECT * FROM tbl_top_category ORDER BY tcat_name ASC";
$topCatsResult = mysqli_query($db, $topCatsQuery);
?>

<div class="container mx-auto p-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold flex items-center">
            <i class="fas fa-circle-dot mr-2"></i> Edit Mid Level Category
        </h1>
        <a href="mid-category.php" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">
            Back to Mid Level Categories
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
            <input type="hidden" name="mcat_id" value="<?php echo $mcatId; ?>">

            <div class="mb-4">
                <label for="tcat_id" class="block text-sm font-medium mb-1">Top Level Category</label>
                <select name="tcat_id" id="tcat_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Select Top Level Category</option>
                    <?php 
                    if ($topCatsResult && mysqli_num_rows($topCatsResult) > 0) {
                        mysqli_data_seek($topCatsResult, 0); // Reset the result pointer
                        while ($row = mysqli_fetch_assoc($topCatsResult)) {
                            $selected = ($tcatId == $row['tcat_id']) ? 'selected' : '';
                            echo '<option value="' . $row['tcat_id'] . '" ' . $selected . '>' . htmlspecialchars($row['tcat_name']) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="mcat_name" class="block text-sm font-medium mb-1">Mid Level Category Name</label>
                <input type="text" id="mcat_name" name="mcat_name" value="<?php echo htmlspecialchars($mcatName); ?>" 
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
           
            <div class="flex justify-end">
                <a href="mid-category.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded mr-2 hover:bg-gray-400">Cancel</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
            </div>
        </form>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>