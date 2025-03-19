<?php
include_once '../../includes/header.php';

// Use to report the error if any
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize variables
$ecatName = '';
$mcatId = '';
$tcatId = '';
$error = '';
$success = '';
$ecatId = isset($_GET['ecat_id']) ? (int)$_GET['ecat_id'] : 0;

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get category name from form
    $ecatName = trim($_POST['ecat_name']);
    $mcatId = isset($_POST['mcat_id']) ? (int)$_POST['mcat_id'] : 0;
    $tcatId = isset($_POST['tcat_id']) ? (int)$_POST['tcat_id'] : 0;
    $ecatId = isset($_POST['ecat_id']) ? (int)$_POST['ecat_id'] : 0;
    
    // Validate form input
    if (empty($ecatName)) {
        $error = 'End Level Category name is required.';
    } elseif (empty($mcatId)) {
        $error = 'Mid Level Category selection is required.';
    } elseif (empty($tcatId)) {
        $error = 'Top Level Category selection is required.';
    } else {
        // Escape input to prevent SQL injection
        $ecatName = mysqli_real_escape_string($db, $ecatName);
        
        // Check if category already exists (except current category)
        $checkQuery = "SELECT * FROM tbl_end_category WHERE ecat_name = '$ecatName' AND mcat_id = $mcatId AND ecat_id != $ecatId";
        $checkResult = mysqli_query($db, $checkQuery);
        
        if (mysqli_num_rows($checkResult) > 0) {
            $error = 'This end level category already exists under the selected mid category.';
        } else {
            // Update category
            $updateQuery = "UPDATE tbl_end_category SET ecat_name = '$ecatName', mcat_id = $mcatId WHERE ecat_id = $ecatId";
            
            if (mysqli_query($db, $updateQuery)) {
                $success = 'End Level Category updated successfully.';
            } else {
                $error = 'Error updating End Level Category: ' . mysqli_error($db);
            }
        }
    }
} else {
    // Fetch category data for editing
    if ($ecatId > 0) {
        $query = "SELECT e.*, m.tcat_id FROM tbl_end_category e 
                 JOIN tbl_mid_category m ON e.mcat_id = m.mcat_id 
                 WHERE e.ecat_id = $ecatId";
        $result = mysqli_query($db, $query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $categoryData = mysqli_fetch_assoc($result);
            $ecatName = $categoryData['ecat_name'];
            $mcatId = $categoryData['mcat_id'];
            $tcatId = $categoryData['tcat_id'];
        } else {
            $error = 'End Level Category not found.';
        }
    } else {
        $error = 'Invalid End Level Category ID.';
    }
}

// Get top-level categories for dropdown
$topCatsQuery = "SELECT * FROM tbl_top_category ORDER BY tcat_name ASC";
$topCatsResult = mysqli_query($db, $topCatsQuery);

// Get mid-level categories for the selected top category
$midCatsQuery = "SELECT * FROM tbl_mid_category WHERE tcat_id = $tcatId ORDER BY mcat_name ASC";
$midCatsResult = mysqli_query($db, $midCatsQuery);
?>

<div class="container mx-auto p-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold flex items-center">
            <i class="fas fa-circle-dot mr-2"></i> Edit End Level Category
        </h1>
        <a href="end-category.php" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">
            Back to End Level Categories
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
            <input type="hidden" name="ecat_id" value="<?php echo $ecatId; ?>">

            <div class="mb-4">
                <label for="tcat_id" class="block text-sm font-medium mb-1">Top Level Category Name</label>
                <select name="tcat_id" id="tcat_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required onchange="loadMidCategories(this.value)">
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
                <label for="mcat_id" class="block text-sm font-medium mb-1">Mid Level Category Name</label>
                <select name="mcat_id" id="mcat_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Select Mid Level Category</option>
                    <?php 
                    if ($midCatsResult && mysqli_num_rows($midCatsResult) > 0) {
                        while ($row = mysqli_fetch_assoc($midCatsResult)) {
                            $selected = ($mcatId == $row['mcat_id']) ? 'selected' : '';
                            echo '<option value="' . $row['mcat_id'] . '" ' . $selected . '>' . htmlspecialchars($row['mcat_name']) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="ecat_name" class="block text-sm font-medium mb-1">End Level Category Name</label>
                <input type="text" id="ecat_name" name="ecat_name" value="<?php echo htmlspecialchars($ecatName); ?>" 
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
           
            <div class="flex justify-end">
                <a href="end-category.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded mr-2 hover:bg-gray-400">Cancel</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
function loadMidCategories(tcatId) {
    if (tcatId) {
        // Make an AJAX request to get mid categories for the selected top category
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get-mid-categories.php?tcat_id=' + tcatId, true);
        xhr.onload = function() {
            if (this.status == 200) {
                document.getElementById('mcat_id').innerHTML = this.responseText;
            }
        };
        xhr.send();
    } else {
        document.getElementById('mcat_id').innerHTML = '<option value="">Select Mid Level Category</option>';
    }
}
</script>

<?php include_once '../../includes/footer.php'; ?>