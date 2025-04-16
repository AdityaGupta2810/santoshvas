<?php
include_once '../../includes/header.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check database connection
if (!isset($db) || !$db) {
    die("Database connection failed. Please check your configuration.");
}

// Initialize variables
$midCatName = '';
$topCatId = '';
$error = '';
$success = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get mid-category name and top category ID from form
    $midCatName = trim($_POST['mcat_name']);
    $topCatId = isset($_POST['tcat_id']) ? (int)$_POST['tcat_id'] : 0;
    
    // Validate form input
    if (empty($midCatName)) {
        $error = 'Mid Level Category name is required.';
    } elseif (empty($topCatId)) {
        $error = 'Please select a Top Level Category.';
    } else {
        // Check connection
        if (!$db) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        // Escape input to prevent SQL injection
        $midCatName = mysqli_real_escape_string($db, $midCatName);
        
        // Check if mid-category already exists
        $checkQuery = "SELECT * FROM tbl_mid_category WHERE mcat_name = '$midCatName' AND tcat_id = $topCatId";
        $checkResult = mysqli_query($db, $checkQuery);
        
        if (mysqli_num_rows($checkResult) > 0) {
            $error = 'This Mid Level Category already exists under the selected Top Level Category.';
        } else {
            // Insert new mid-category
            $insertQuery = "INSERT INTO tbl_mid_category (mcat_name, tcat_id) VALUES ('$midCatName', $topCatId)";
            if (mysqli_query($db, $insertQuery)) {
                $success = 'Mid Level Category added successfully.';
                $midCatName = ''; // Clear form
                $topCatId = '';
            } else {
                $error = 'Error adding Mid Level Category: ' . mysqli_error($db);
            }
        }
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
            <i class="fas fa-circle-dot mr-2"></i> Add New Mid Level Category
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
    
    <!-- Add Mid Category Form -->
    <div class="bg-white rounded-lg shadow-md p-6 max-w-lg mx-auto">
        <form method="POST" action="">
            <div class="mb-4">
                <label for="tcat_id" class="block text-sm font-medium mb-1">Top Level Category</label>
                <select id="tcat_id" name="tcat_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Select Top Level Category</option>
                    <?php 
                    if ($topCatsResult && mysqli_num_rows($topCatsResult) > 0) {
                        while ($row = mysqli_fetch_assoc($topCatsResult)) {
                            $selected = ($topCatId == $row['tcat_id']) ? 'selected' : '';
                            echo '<option value="' . $row['tcat_id'] . '" ' . $selected . '>' . htmlspecialchars($row['tcat_name']) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="mcat_name" class="block text-sm font-medium mb-1">Mid Level Category</label>
                <input type="text" id="mcat_name" name="mcat_name" value="<?php echo htmlspecialchars($midCatName); ?>" 
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            
            <div class="flex justify-end">
                <a href="mid-category.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded mr-2 hover:bg-gray-400">Cancel</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>