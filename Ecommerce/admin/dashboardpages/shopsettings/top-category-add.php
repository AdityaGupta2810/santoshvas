<?php
include_once '../../includes/header.php';
// include_once 'db_connect.php'; // Add database connection include

// Initialize variables
$tcatName = '';
$showOnMenu='';
$error = '';

$success = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get Size name from form
    $tcatName = trim($_POST['tcat_name']);
    
    // Validate form input
    if (empty($tcatName)) {
        $error = 'Top Level Catergory name is required.';
    } else {
        // Connect to database
        // $db = mysqli_connect($host, $username, $password, "santoshvastralay");
        
        // Check connection
        if (!$db) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        // Escape input to prevent SQL injection
        $tcatName = mysqli_real_escape_string($db, $tcatName);
        $showOnMenu = mysqli_real_escape_string($db, $_POST['show_on_menu']);
        // Check if Size already exists
        $checkQuery = "SELECT * FROM tbl_top_category WHERE tcat_name = '$tcatName'";
        $checkResult = mysqli_query($db, $checkQuery);
        
        if (mysqli_num_rows($checkResult) > 0) {
            $error = 'This Category already exists.';
        } else {
            // Insert new Category
           
            $insertQuery = "INSERT INTO tbl_top_category (tcat_name, show_on_menu) VALUES ('$tcatName', '$showOnMenu')";
            if (mysqli_query($db, $insertQuery)) {
                $success = 'Category added successfully.';
                $tcatName = ''; // Clear form
            } else {
                $error = 'Error adding Category: ' . mysqli_error($db);
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
            <i class="fas fa-circle-dot mr-2"></i> Add New Top Level Category
        </h1>
        <a href="top-category.php" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">
            Back to Categories
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
                <label for="tcat_name" class="block text-sm font-medium mb-1">Top Level Category Name</label>
                <input type="text" id="tcat_name" name="tcat_name" value="<?php echo htmlspecialchars($tcatName); ?>" 
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="show_on_menu" class="block text-sm font-medium mb-1">Show on menu</label>
                <select name="show_on_menu" id="show_on_menu"  class="border rounded px-3  py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required >
    <option value="1">Yes</option>
      <option value="0">No</option>
   
               </select>
            </div>
            <div class="flex justify-end">
                <a href="top-category.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded mr-2 hover:bg-gray-400">Cancel</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>