<?php
include_once '../../includes/header.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize variables
$ecatName = '';
$mcatId = '';
$tcatId = '';
$error = '';
$success = '';
$ecatId = isset($_GET['ecat_id']) ? (int)$_GET['ecat_id'] : 0;
$formSubmitted = false;

// Check if this is a top category selection change
if (isset($_POST['update_mcat']) && isset($_POST['tcat_id'])) {
    $tcatId = (int)$_POST['tcat_id'];
    $ecatId = isset($_POST['ecat_id']) ? (int)$_POST['ecat_id'] : 0;
    $ecatName = isset($_POST['ecat_name']) ? $_POST['ecat_name'] : '';
    // Don't process as a form submission to update the database
    $formSubmitted = false;
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Regular form submission to update the database
    $formSubmitted = true;
    // Get form data
    $ecatName = trim($_POST['ecat_name']);
    $tcatId = isset($_POST['tcat_id']) ? (int)$_POST['tcat_id'] : 0;
    $mcatId = isset($_POST['mcat_id']) ? (int)$_POST['mcat_id'] : 0;
    $ecatId = isset($_POST['ecat_id']) ? (int)$_POST['ecat_id'] : 0;

    // Validate input
    if (empty($ecatName)) {
        $error = 'End Level Category name is required.';
    } elseif ($tcatId == 0) {
        $error = 'Top Level Category selection is required.';
    } elseif ($mcatId == 0) {
        $error = 'Mid Level Category selection is required.';
    } else {
        // Escape input
        $ecatName = mysqli_real_escape_string($db, $ecatName);

        // Check if category already exists
        $checkQuery = "SELECT * FROM tbl_end_category WHERE ecat_name = '$ecatName' AND tcat_id = $tcatId AND mcat_id = $mcatId AND ecat_id != $ecatId";
        $checkResult = mysqli_query($db, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            $error = 'This End Level Category already exists under the selected categories.';
        } else {
            // Update category
            $updateQuery = "UPDATE tbl_end_category SET ecat_name = '$ecatName', tcat_id = $tcatId, mcat_id = $mcatId WHERE ecat_id = $ecatId";

            if (mysqli_query($db, $updateQuery)) {
                $success = 'End Level Category updated successfully.';
            } else {
                $error = 'Error updating End Level Category: ' . mysqli_error($db);
            }
        }
    }
} else {
    // Initial page load - Fetch category data for editing
    if ($ecatId > 0) {
        $query = "SELECT * FROM tbl_end_category WHERE ecat_id = $ecatId";
        $result = mysqli_query($db, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $categoryData = mysqli_fetch_assoc($result);
            $ecatName = $categoryData['ecat_name'];
            $tcatId = $categoryData['tcat_id'];
            $mcatId = $categoryData['mcat_id'];
        } else {
            $error = 'End Level Category not found.';
        }
    } else {
        $error = 'Invalid End Level Category ID.';
    }
}

// Get top categories
$topCatsQuery = "SELECT * FROM tbl_top_category ORDER BY tcat_name ASC";
$topCatsResult = mysqli_query($db, $topCatsQuery);

// Get mid categories filtered by top category
if ($tcatId > 0) {
    $midCatsQuery = "SELECT * FROM tbl_mid_category WHERE tcat_id = $tcatId ORDER BY mcat_name ASC";
    $midCatsResult = mysqli_query($db, $midCatsQuery);
} else {
    $midCatsResult = mysqli_query($db, "SELECT * FROM tbl_mid_category WHERE 1=0"); // Empty result
}
?>

<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold flex items-center">
            <i class="fas fa-circle-dot mr-2"></i> Edit End Level Category
        </h1>
        <a href="end-category.php" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">Back</a>
    </div>

    <div class="border-t border-b border-gray-300 my-4"></div>

    <?php if (!empty($success)): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
        <p><?php echo $success; ?></p>
    </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
        <p><?php echo $error; ?></p>
    </div>
    <?php endif; ?>

    <div class="bg-white rounded-lg shadow-md p-6 max-w-lg mx-auto">
        <form method="POST" action="" id="categoryForm">
            <input type="hidden" name="ecat_id" value="<?php echo $ecatId; ?>">

            <div class="mb-4">
                <label for="tcat_id" class="block text-sm font-medium mb-1">Top Level Category</label>
                <select name="tcat_id" id="tcat_id" class="w-full border rounded px-3 py-2" required onchange="this.form.update_mcat.value='1'; this.form.submit();">
                    <option value="">Select</option>
                    <?php 
                    // Reset the result pointer
                    mysqli_data_seek($topCatsResult, 0);
                    while ($row = mysqli_fetch_assoc($topCatsResult)) : 
                    ?>
                        <option value="<?= $row['tcat_id']; ?>" <?= ($tcatId == $row['tcat_id']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($row['tcat_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <input type="hidden" name="update_mcat" value="0">
            </div>

            <div class="mb-4">
                <label for="mcat_id" class="block text-sm font-medium mb-1">Mid Level Category</label>
                <select name="mcat_id" id="mcat_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select</option>
                    <?php while ($row = mysqli_fetch_assoc($midCatsResult)) : ?>
                        <option value="<?= $row['mcat_id']; ?>" <?= ($mcatId == $row['mcat_id']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($row['mcat_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="ecat_name" class="block text-sm font-medium mb-1">Category Name</label>
                <input type="text" id="ecat_name" name="ecat_name" value="<?= htmlspecialchars($ecatName); ?>" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="flex justify-end">
                <a href="end-category.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded mr-2">Cancel</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
            </div>
        </form>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>