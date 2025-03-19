<?php
include_once '../../includes/header.php';

// Initialize variables
$endCatName = '';
$midCatId = '';
$topCatId = '';
$error = '';
$success = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get end-category name and parent category IDs from form
    $endCatName = trim($_POST['ecat_name']);
    $midCatId = isset($_POST['mcat_id']) ? (int)$_POST['mcat_id'] : 0;
    $topCatId = isset($_POST['tcat_id']) ? (int)$_POST['tcat_id'] : 0;
    
    // Validate form input
    if (empty($endCatName)) {
        $error = 'End Level Category name is required.';
    } elseif (empty($topCatId)) {
        $error = 'Please select a Top Level Category.';
    } elseif (empty($midCatId)) {
        $error = 'Please select a Mid Level Category.';
    }
    else {
        // Check connection
        if (!isset($db) || !$db) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        // Escape input to prevent SQL injection
        $endCatName = mysqli_real_escape_string($db, $endCatName);
        
        // Check if end-category already exists
        $checkQuery = "SELECT * FROM tbl_end_category e 
        JOIN tbl_mid_category m ON e.mcat_id = m.mcat_id 
        WHERE e.ecat_name = '$endCatName' AND e.mcat_id = '$midCatId' AND m.tcat_id = '$topCatId'";
        $checkResult = mysqli_query($db, $checkQuery);
        
        if (mysqli_num_rows($checkResult) > 0) {
            $error = 'This End Level Category already exists under the selected Mid Level Category.';
        } else {
            // Insert new end-category
            $insertQuery = "INSERT INTO tbl_end_category (ecat_name, mcat_id) VALUES ('$endCatName', $midCatId)";
            if (mysqli_query($db, $insertQuery)) {
                $success = 'End Level Category added successfully.';
                $endCatName = ''; // Clear form
                $midCatId = '';
                $topCatId = '';
            } else {
                $error = 'Error adding End Level Category: ' . mysqli_error($db);
            }
        }
    }
}

// Get top-level categories for dropdown
$topCatsQuery = "SELECT * FROM tbl_top_category ORDER BY tcat_name ASC";
$topCatsResult = mysqli_query($db, $topCatsQuery);

// We'll only load mid-categories based on the selected top category via AJAX
$midCatsOptions = '<option value="">Select Mid Level Category</option>';
if (!empty($topCatId)) {
    $midCatsQuery = "SELECT * FROM tbl_mid_category WHERE tcat_id = $topCatId ORDER BY mcat_name ASC";
    $midCatsResult = mysqli_query($db, $midCatsQuery);
    
    if ($midCatsResult && mysqli_num_rows($midCatsResult) > 0) {
        while ($row = mysqli_fetch_assoc($midCatsResult)) {
            $selected = ($midCatId == $row['mcat_id']) ? 'selected' : '';
            $midCatsOptions .= '<option value="' . $row['mcat_id'] . '" ' . $selected . '>' . htmlspecialchars($row['mcat_name']) . '</option>';
        }
    }
}
?>

<div class="container mx-auto p-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold flex items-center">
            <i class="fas fa-circle-dot mr-2"></i> Add New End Level Category
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
    
    <!-- Add End Category Form -->
    <div class="bg-white rounded-lg shadow-md p-6 max-w-lg mx-auto">
        <form method="POST" action="">
            <div class="mb-4">
                <label for="tcat_id" class="block text-sm font-medium mb-1">Top Level Category</label>
                <select id="tcat_id" name="tcat_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        onchange="loadMidCategories(this.value)" required>
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
                <label for="mcat_id" class="block text-sm font-medium mb-1">Mid Level Category</label>
                <select id="mcat_id" name="mcat_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <?php echo $midCatsOptions; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for='ecat_name' class="block text-sm font-medium mb-1">End Level Category</label>
                <input type="text" id="ecat_name" name='ecat_name' value="<?php echo htmlspecialchars($endCatName); ?>" 
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            
            <div class="flex justify-end">
                <a href="end-category.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded mr-2 hover:bg-gray-400">Cancel</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
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

// If top category is already selected on page load, load its mid categories
document.addEventListener('DOMContentLoaded', function() {
    var topCatSelect = document.getElementById('tcat_id');
    if (topCatSelect.value) {
        loadMidCategories(topCatSelect.value);
    }
});
</script>
<?php include_once '../../includes/footer.php'; ?>