<?php 
include_once '../../includes/header.php';

// Initialize variables to store form data for persistence
$p_name = '';
$p_old_price = '';
$p_current_price = '';
$p_qty = '';
$p_sizes = []; // Changed to array
$p_colors = []; // Changed to array
$p_description = '';
$p_short_description = '';
$p_is_active = 1; // Default to active
$featured_photo = '';

// Check if product ID is provided
if(!isset($_REQUEST['p_id'])) {
    header('location: products.php');
    exit;
}

$product_id = intval($_REQUEST['p_id']);

// Fetch existing product data
try {
    // Get product basic info
    $stmt_product = $db->prepare("SELECT * FROM tbl_product WHERE p_id = ?");
    if (!$stmt_product) {
        throw new Exception("Prepare failed: " . $db->error);
    }
    
    $stmt_product->bind_param("i", $product_id);
    $stmt_product->execute();
    $result_product = $stmt_product->get_result();
    
    if($result_product->num_rows == 0) {
        header('location: products.php');
        exit;
    }
    
    $product_data = $result_product->fetch_assoc();
    
    // Populate form data from database
    $p_name = $product_data['p_name'];
    $p_old_price = $product_data['p_old_price'];
    $p_current_price = $product_data['p_current_price'];
    $p_qty = $product_data['p_qty'];
    $p_description = $product_data['p_description'];
    $p_short_description = $product_data['p_short_description'];
    $p_is_active = $product_data['p_is_active'];
    $ecat_id = $product_data['ecat_id'];
    $featured_photo = $product_data['p_featured_photo'];
    
    // Get existing sizes
    $stmt_sizes = $db->prepare("SELECT size_id FROM tbl_product_size WHERE p_id = ?");
    if (!$stmt_sizes) {
        throw new Exception("Prepare failed: " . $db->error);
    }
    
    $stmt_sizes->bind_param("i", $product_id);
    $stmt_sizes->execute();
    $result_sizes = $stmt_sizes->get_result();
    
    while($row = $result_sizes->fetch_assoc()) {
        $p_sizes[] = $row['size_id'];
    }
    
    // Get existing colors
    $stmt_colors = $db->prepare("SELECT color_id FROM tbl_product_color WHERE p_id = ?");
    if (!$stmt_colors) {
        throw new Exception("Prepare failed: " . $db->error);
    }
    
    $stmt_colors->bind_param("i", $product_id);
    $stmt_colors->execute();
    $result_colors = $stmt_colors->get_result();
    
    while($row = $result_colors->fetch_assoc()) {
        $p_colors[] = $row['color_id'];
    }
    
    // Get existing photos
    $stmt_photos = $db->prepare("SELECT * FROM tbl_product_photo WHERE p_id = ?");
    if (!$stmt_photos) {
        throw new Exception("Prepare failed: " . $db->error);
    }
    
    $stmt_photos->bind_param("i", $product_id);
    $stmt_photos->execute();
    $result_photos = $stmt_photos->get_result();
    
    $product_photos = [];
    while($row = $result_photos->fetch_assoc()) {
        $product_photos[] = $row;
    }
    
} catch (Exception $e) {
    $error_message = 'Database error: ' . $e->getMessage();
}

// Check if form is submitted
if(isset($_POST['form1'])) {
    // Collect and sanitize form data
    $p_name = mysqli_real_escape_string($db, $_POST['p_name']);
    $p_old_price = isset($_POST['p_old_price']) && !empty($_POST['p_old_price']) ? (float)$_POST['p_old_price'] : null;
    $p_current_price = (float)$_POST['p_current_price'];
    $p_qty = (int)$_POST['p_qty'];
    $p_sizes = isset($_POST['p_size']) ? $_POST['p_size'] : []; // Changed to handle array
    $p_colors = isset($_POST['p_color']) ? $_POST['p_color'] : []; // Changed to handle array
    $p_description = mysqli_real_escape_string($db, $_POST['p_description']);
    $p_short_description = mysqli_real_escape_string($db, $_POST['p_short_description']);
    $p_is_active = isset($_POST['p_is_active']) ? 1 : 0;
    $ecat_id = (int)$_POST['ecat_id'];
    
    // Validation
    $valid = true;
    $error_message = '';
    
    if(empty($p_name)) {
        $valid = false;
        $error_message .= 'Product name cannot be empty<br>';
    }
    
    if(empty($p_current_price)) {
        $valid = false;
        $error_message .= 'Current price cannot be empty<br>';
    }
    
    if(empty($p_qty)) {
        $valid = false;
        $error_message .= 'Quantity cannot be empty<br>';
    }
    
    if(empty($ecat_id)) {
        $valid = false;
        $error_message .= 'Please select a category<br>';
    }
    
    // Handle featured photo upload if a new one is provided
    if(isset($_FILES['p_featured_photo']['name']) && !empty($_FILES['p_featured_photo']['name'])) {
        $file_name = $_FILES['p_featured_photo']['name'];
        $file_temp = $_FILES['p_featured_photo']['tmp_name'];
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $allowed_exts = array('jpg', 'jpeg', 'png', 'gif');
        
        if(in_array(strtolower($ext), $allowed_exts)) {
            // Remove old featured photo if exists
            if(!empty($featured_photo) && file_exists('../../uploadimgs/'.$featured_photo)) {
                unlink('../../uploadimgs/'.$featured_photo);
            }
            
            $featured_photo = 'product_'.time().'.'.$ext;
            move_uploaded_file($file_temp, '../../uploadimgs/'.$featured_photo);
        } else {
            $valid = false;
            $error_message .= 'Featured photo must be jpg, jpeg, png or gif file<br>';
        }
    }
    
    // Process multiple photos if needed
    $photos = array();
    if($valid && isset($_FILES['p_photos']['name']) && !empty($_FILES['p_photos']['name'][0])) {
        $total_files = count($_FILES['p_photos']['name']);
        
        for($i=0; $i<$total_files; $i++) {
            $file_name = $_FILES['p_photos']['name'][$i];
            $file_temp = $_FILES['p_photos']['tmp_name'][$i];
            $ext = pathinfo($file_name, PATHINFO_EXTENSION);
            
            if(in_array(strtolower($ext), $allowed_exts)) {
                $photo_name = 'product_'.time().'_'.$i.'.'.$ext;
                move_uploaded_file($file_temp, '../../uploadimgs/'.$photo_name);
                $photos[] = $photo_name;
            } else {
                $valid = false;
                $error_message .= 'Photo '.$i.' must be jpg, jpeg, png or gif file<br>';
                break;
            }
        }
    }
    
    // Update data if valid
    if($valid) {
        try {
            // Start transaction
            $db->begin_transaction();
            
            // Update product
            $sql = "UPDATE tbl_product SET 
                    p_name = ?, 
                    p_old_price = ?, 
                    p_current_price = ?, 
                    p_qty = ?, 
                    p_description = ?, 
                    p_short_description = ?, 
                    p_is_active = ?, 
                    ecat_id = ?";
            
            // Only update featured photo if a new one is provided
            if(!empty($_FILES['p_featured_photo']['name'])) {
                $sql .= ", p_featured_photo = ?";
            }
            
            $sql .= " WHERE p_id = ?";
            
            $statement = $db->prepare($sql);
            
            if (!$statement) {
                throw new Exception("Prepare failed: " . $db->error);
            }
            
            if(!empty($_FILES['p_featured_photo']['name'])) {
                // With featured photo update
                $statement->bind_param(
                    "sddiissisi", 
                    $p_name, $p_old_price, $p_current_price, $p_qty,
                    $p_description, $p_short_description, 
                    $p_is_active, $ecat_id, $featured_photo, $product_id
                );
            } else {
                // Without featured photo update
                $statement->bind_param(
                    "sddiisiii", 
                    $p_name, $p_old_price, $p_current_price, $p_qty,
                    $p_description, $p_short_description, 
                    $p_is_active, $ecat_id, $product_id
                );
            }
            
            if (!$statement->execute()) {
                throw new Exception("Execute failed: " . $statement->error);
            }
            
            // Handle sizes - delete existing and insert new
            $db->query("DELETE FROM tbl_product_size WHERE p_id = $product_id");
            
            if(!empty($p_sizes)) {
                $stmt_size = $db->prepare("INSERT INTO tbl_product_size (p_id, size_id) VALUES (?, ?)");
                if (!$stmt_size) {
                    throw new Exception("Prepare size failed: " . $db->error);
                }
                
                foreach($p_sizes as $size_id) {
                    $size_id = (int)$size_id; // Ensure it's an integer
                    $stmt_size->bind_param("ii", $product_id, $size_id);
                    if (!$stmt_size->execute()) {
                        throw new Exception("Execute size failed: " . $stmt_size->error);
                    }
                }
            }
            
            // Handle colors - delete existing and insert new
            $db->query("DELETE FROM tbl_product_color WHERE p_id = $product_id");
            
            if(!empty($p_colors)) {
                $stmt_color = $db->prepare("INSERT INTO tbl_product_color (p_id, color_id) VALUES (?, ?)");
                if (!$stmt_color) {
                    throw new Exception("Prepare color failed: " . $db->error);
                }
                
                foreach($p_colors as $color_id) {
                    $color_id = (int)$color_id; // Ensure it's an integer
                    $stmt_color->bind_param("ii", $product_id, $color_id);
                    if (!$stmt_color->execute()) {
                        throw new Exception("Execute color failed: " . $stmt_color->error);
                    }
                }
            }
            
            // Insert additional photos if any
            if(!empty($photos)) {
                foreach($photos as $photo) {
                    $statement = $db->prepare("INSERT INTO tbl_product_photo (p_id, photo) VALUES (?, ?)");
                    
                    if (!$statement) {
                        throw new Exception("Prepare failed: " . $db->error);
                    }
                    
                    $statement->bind_param("is", $product_id, $photo);
                    
                    if (!$statement->execute()) {
                        throw new Exception("Execute failed: " . $statement->error);
                    }
                }
            }
            
            // Commit transaction
            $db->commit();
            
            $success_message = 'Product has been updated successfully!';
            
            // Refresh product data after update
            $stmt_product = $db->prepare("SELECT * FROM tbl_product WHERE p_id = ?");
            $stmt_product->bind_param("i", $product_id);
            $stmt_product->execute();
            $result_product = $stmt_product->get_result();
            $product_data = $result_product->fetch_assoc();
            
            // Refresh photos
            $stmt_photos = $db->prepare("SELECT * FROM tbl_product_photo WHERE p_id = ?");
            $stmt_photos->bind_param("i", $product_id);
            $stmt_photos->execute();
            $result_photos = $stmt_photos->get_result();
            
            $product_photos = [];
            while($row = $result_photos->fetch_assoc()) {
                $product_photos[] = $row;
            }
            
        } catch (Exception $e) {
            // Rollback transaction on error
            $db->rollback();
            $error_message = 'Database error: ' . $e->getMessage();
        }
    }
}

// Handle photo deletion
if(isset($_REQUEST['delete_photo']) && isset($_REQUEST['photo_id'])) {
    $photo_id = intval($_REQUEST['photo_id']);
    
    try {
        // Get the photo name first
        $stmt = $db->prepare("SELECT photo FROM tbl_product_photo WHERE pp_id = ?");
        $stmt->bind_param("i", $photo_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $photo = $row['photo'];
            
            // Delete the file
            if(file_exists('../../uploadimgs/'.$photo)) {
                unlink('../../uploadimgs/'.$photo);
            }
            
            // Delete the record
            $stmt = $db->prepare("DELETE FROM tbl_product_photo WHERE pp_id = ?");
            $stmt->bind_param("i", $photo_id);
            $stmt->execute();
            
            $success_message = 'Photo has been deleted successfully!';
            
            // Refresh photos
            $stmt_photos = $db->prepare("SELECT * FROM tbl_product_photo WHERE p_id = ?");
            $stmt_photos->bind_param("i", $product_id);
            $stmt_photos->execute();
            $result_photos = $stmt_photos->get_result();
            
            $product_photos = [];
            while($row = $result_photos->fetch_assoc()) {
                $product_photos[] = $row;
            }
        }
        
    } catch (Exception $e) {
        $error_message = 'Database error: ' . $e->getMessage();
    }
}

// Get categories for dropdown selection
try {
    $stmt_categories = $db->prepare("
        SELECT e.ecat_id, e.ecat_name, m.mcat_name, t.tcat_name 
        FROM tbl_end_category e
        JOIN tbl_mid_category m ON e.mcat_id = m.mcat_id
        JOIN tbl_top_category t ON m.tcat_id = t.tcat_id
        ORDER BY t.tcat_name, m.mcat_name, e.ecat_name
    ");
    
    if (!$stmt_categories) {
        throw new Exception("Prepare failed: " . $db->error);
    }
    
    $stmt_categories->execute();
    $result_categories = $stmt_categories->get_result();
    
    // Get sizes for dropdown
    $stmt_sizes = $db->prepare("SELECT id, size_name FROM tbl_size ORDER BY size_name");
    
    if (!$stmt_sizes) {
        throw new Exception("Prepare failed: " . $db->error);
    }
    
    $stmt_sizes->execute();
    $result_sizes = $stmt_sizes->get_result();
    
    // Get colors for dropdown
    $stmt_colors = $db->prepare("SELECT id, color_name FROM tbl_color ORDER BY color_name");
    
    if (!$stmt_colors) {
        throw new Exception("Prepare failed: " . $db->error);
    }
    
    $stmt_colors->execute();
    $result_colors = $stmt_colors->get_result();
    
    // Store size and color maps for displaying selected items
    $sizes_map = [];
    $colors_map = [];
    
    // Prepare arrays of options for the dropdowns
    $size_options = [];
    $color_options = [];
    
    while($row = $result_sizes->fetch_assoc()) {
        $sizes_map[$row['id']] = $row['size_name'];
        $size_options[] = $row;
    }
    
    while($row = $result_colors->fetch_assoc()) {
        $colors_map[$row['id']] = $row['color_name'];
        $color_options[] = $row;
    }
    
} catch (Exception $e) {
    $error_message = 'Database error: ' . $e->getMessage();
}
?>

<div class="container mx-auto p-4">
    <!-- Header with title and back button -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold flex items-center mb-3 sm:mb-0">
            <i class="fas fa-edit mr-2"></i>Edit Product
        </h1>
        <button class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700 w-full sm:w-auto">
            <a href="products.php" class="block text-center">
                <i class="fas fa-arrow-left mr-2"></i>Back to Products
            </a>
        </button>
    </div>
    
    <div class="border-t border-b border-gray-300 my-4"></div>
    
    <!-- Error/Success Messages -->
    <?php if(isset($error_message) && !empty($error_message)): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
        <p class="font-bold">Error</p>
        <p><?php echo $error_message; ?></p>
    </div>
    <?php endif; ?>
    
    <?php if(isset($success_message) && !empty($success_message)): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
        <p class="font-bold">Success</p>
        <p><?php echo $success_message; ?></p>
    </div>
    <?php endif; ?>
    
    <!-- Product Edit Form -->
    <div class="bg-white rounded shadow p-6">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div>
                    <!-- Categories Section -->
                    <div class="mb-6">
                        <label for="ecat_id" class="block mb-2 font-medium">Category Selection <span class="text-red-500">*</span></label>
                        <select name="ecat_id" id="ecat_id" class="w-full border rounded px-3 py-2" required>
                            <option value="">Select Category</option>
                            <?php if(isset($result_categories) && $result_categories->num_rows > 0): ?>
                                <?php while($row = $result_categories->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($row['ecat_id']); ?>" <?php echo (isset($ecat_id) && $ecat_id == $row['ecat_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($row['tcat_name'] . ' → ' . $row['mcat_name'] . ' → ' . $row['ecat_name']); ?>
                                </option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <!-- Basic Info Section -->
                    <div class="mb-6">
                        <label for="p_name" class="block mb-2 font-medium">Product Name <span class="text-red-500">*</span></label>
                        <input type="text" name="p_name" id="p_name" class="w-full border rounded px-3 py-2" value="<?php echo htmlspecialchars($p_name); ?>" required>
                    </div>
                    
                    <div class="mb-6">
                        <label for="p_old_price" class="block mb-2 font-medium">Old Price </label>
                        <input type="number" step="0.01" name="p_old_price" id="p_old_price" class="w-full border rounded px-3 py-2" value="<?php echo htmlspecialchars($p_old_price); ?>">
                    </div>
                    
                    <div class="mb-6">
                        <label for="p_current_price" class="block mb-2 font-medium">Current Price <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="p_current_price" id="p_current_price" class="w-full border rounded px-3 py-2" value="<?php echo htmlspecialchars($p_current_price); ?>" required>
                    </div>
                    
                    <div class="mb-6">
                        <label for="p_qty" class="block mb-2 font-medium">Quantity <span class="text-red-500">*</span></label>
                        <input type="number" name="p_qty" id="p_qty" class="w-full border rounded px-3 py-2" value="<?php echo htmlspecialchars($p_qty); ?>" required>
                    </div>
                    
                    <!-- Size Selection with Dropdown -->
                    <div class="mb-6">
                        <label class="block mb-2 font-medium">Size</label>
                        <!-- Selected sizes display -->
                        <div id="selected-sizes" class="flex flex-wrap gap-2 mb-3 min-h-8">
                            <?php if(!empty($p_sizes)): ?>
                                <?php foreach($p_sizes as $size_id): ?>
                                    <?php if(isset($sizes_map[$size_id])): ?>
                                    <div class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm flex items-center">
                                        <span><?php echo htmlspecialchars($sizes_map[$size_id]); ?></span>
                                        <button type="button" class="ml-2 text-blue-500 hover:text-blue-700 remove-size" data-size-id="<?php echo $size_id; ?>">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <input type="hidden" name="p_size[]" value="<?php echo $size_id; ?>">
                                    </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Size dropdown -->
                        <div class="flex">
                            <select id="size-selector" class="w-full border rounded px-3 py-2">
                                <option value="">Select a size</option>
                                <?php foreach($size_options as $size): ?>
                                    <option value="<?php echo $size['id']; ?>" 
                                            data-size-name="<?php echo htmlspecialchars($size['size_name']); ?>"
                                            <?php echo (in_array($size['id'], $p_sizes)) ? 'disabled' : ''; ?>>
                                        <?php echo htmlspecialchars($size['size_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Click on a size to add it</p>
                    </div>
                    
                    <!-- Color Selection with Dropdown -->
                    <div class="mb-6">
                        <label class="block mb-2 font-medium">Color</label>
                        <!-- Selected colors display -->
                        <div id="selected-colors" class="flex flex-wrap gap-2 mb-3 min-h-8">
                            <?php if(!empty($p_colors)): ?>
                                <?php foreach($p_colors as $color_id): ?>
                                    <?php if(isset($colors_map[$color_id])): ?>
                                    <div class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm flex items-center">
                                        <span><?php echo htmlspecialchars($colors_map[$color_id]); ?></span>
                                        <button type="button" class="ml-2 text-blue-500 hover:text-blue-700 remove-color" data-color-id="<?php echo $color_id; ?>">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <input type="hidden" name="p_color[]" value="<?php echo $color_id; ?>">
                                    </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Color dropdown -->
                        <div class="flex">
                            <select id="color-selector" class="w-full border rounded px-3 py-2">
                                <option value="">Select a color</option>
                                <?php foreach($color_options as $color): ?>
                                    <option value="<?php echo $color['id']; ?>" 
                                            data-color-name="<?php echo htmlspecialchars($color['color_name']); ?>"
                                            <?php echo (in_array($color['id'], $p_colors)) ? 'disabled' : ''; ?>>
                                        <?php echo htmlspecialchars($color['color_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Click on a color to add it</p>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div>
                    <!-- Images Section -->
                    <div class="mb-6">
                        <label for="p_featured_photo" class="block mb-2 font-medium">Featured Photo</label>
                        
                        <?php if(!empty($featured_photo) && file_exists('../../uploadimgs/'.$featured_photo)): ?>
                            <div class="mb-3">
                                <img src="../../uploadimgs/<?php echo $featured_photo; ?>" alt="Featured Photo" class="w-32 h-32 object-cover border">
                                <p class="text-sm text-gray-500 mt-1">Current featured photo</p>
                            </div>
                        <?php endif; ?>
                        
                        <input type="file" name="p_featured_photo" id="p_featured_photo" class="w-full border rounded px-3 py-2">
                        <p class="text-sm text-gray-500 mt-1">Upload a new image to replace the current one (jpg, jpeg, png, gif only)</p>
                    </div>
                    
                    <!-- Current Other Photos -->
                    <div class="mb-6">
                        <label class="block mb-2 font-medium">Current Other Photos</label>
                        
                        <?php if(!empty($product_photos)): ?>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-3">
                                <?php foreach($product_photos as $photo): ?>
                                    <div class="border p-2 rounded">
                                        <img src="../../uploadimgs/<?php echo $photo['photo']; ?>" alt="Product Photo" class="w-full h-24 object-cover">
                                        <a href="products-edit.php?p_id=<?php echo $product_id; ?>&delete_photo=1&photo_id=<?php echo $photo['pp_id']; ?>" 
                                           class="block text-center bg-red-500 text-white py-1 px-2 mt-2 rounded text-sm hover:bg-red-600"
                                           onclick="return confirm('Are you sure you want to delete this photo?');">
                                            <i class="fas fa-trash-alt mr-1"></i> Delete
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-sm text-gray-500 mb-3">No additional photos available</p>
                        <?php endif; ?>
                        
                        <label for="p_photos" class="block mb-2 font-medium">Add More Photos</label>
                        <input type="file" name="p_photos[]" id="p_photos" class="w-full border rounded px-3 py-2" multiple>
                        <p class="text-sm text-gray-500 mt-1">You can select multiple photos (jpg, jpeg, png, gif only)</p>
                    </div>
                    
                    <!-- Description Sections -->
                    <div class="mb-6">
                        <label for="p_short_description" class="block mb-2 font-medium">Short Description</label>
                        <textarea name="p_short_description" id="p_short_description" rows="3" class="w-full border rounded px-3 py-2"><?php echo htmlspecialchars($p_short_description); ?></textarea>
                    </div>
                    
                    <div class="mb-6">
                        <label for="p_description" class="block mb-2 font-medium">Full Description</label>
                        <textarea name="p_description" id="p_description" rows="6" class="w-full border rounded px-3 py-2"><?php echo htmlspecialchars($p_description); ?></textarea>
                    </div>
                    
                    <!-- Status Section -->
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="p_is_active" value="1" <?php echo ($p_is_active == 1) ? 'checked' : ''; ?> class="mr-2">
                            <span>Active</span>
                        </label>
                        <p class="text-sm text-gray-500 mt-1">Uncheck to hide this product from the store</p>
                    </div>
                </div>
            </div>
            
            <!-- Submit Buttons -->
           <!-- Submit Buttons -->
           <div class="border-t border-gray-300 pt-6 mt-6 flex flex-col sm:flex-row justify-end gap-4">
                <button type="submit" name="form1" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 w-full sm:w-auto">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
                <a href="products.php" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600 text-center w-full sm:w-auto">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Size selector handling
    document.getElementById('size-selector').addEventListener('change', function() {
        const sizeId = this.value;
        if (!sizeId) return;
        
        const sizeName = this.options[this.selectedIndex].getAttribute('data-size-name');
        
        // Create and add the size tag
        const sizeTag = document.createElement('div');
        sizeTag.className = 'bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm flex items-center';
        sizeTag.innerHTML = `
            <span>${sizeName}</span>
            <button type="button" class="ml-2 text-blue-500 hover:text-blue-700 remove-size" data-size-id="${sizeId}">
                <i class="fas fa-times"></i>
            </button>
            <input type="hidden" name="p_size[]" value="${sizeId}">
        `;
        
        document.getElementById('selected-sizes').appendChild(sizeTag);
        
        // Disable the option
        this.options[this.selectedIndex].disabled = true;
        this.selectedIndex = 0;
    });

    // Remove size tag handling
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-size')) {
            const button = e.target.closest('.remove-size');
            const sizeId = button.getAttribute('data-size-id');
            
            // Remove the tag
            button.closest('div').remove();
            
            // Enable the option again
            const option = document.querySelector(`#size-selector option[value="${sizeId}"]`);
            if (option) option.disabled = false;
        }
    });

    // Color selector handling
    document.getElementById('color-selector').addEventListener('change', function() {
        const colorId = this.value;
        if (!colorId) return;
        
        const colorName = this.options[this.selectedIndex].getAttribute('data-color-name');
        
        // Create and add the color tag
        const colorTag = document.createElement('div');
        colorTag.className = 'bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm flex items-center';
        colorTag.innerHTML = `
            <span>${colorName}</span>
            <button type="button" class="ml-2 text-blue-500 hover:text-blue-700 remove-color" data-color-id="${colorId}">
                <i class="fas fa-times"></i>
            </button>
            <input type="hidden" name="p_color[]" value="${colorId}">
        `;
        
        document.getElementById('selected-colors').appendChild(colorTag);
        
        // Disable the option
        this.options[this.selectedIndex].disabled = true;
        this.selectedIndex = 0;
    });

    // Remove color tag handling
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-color')) {
            const button = e.target.closest('.remove-color');
            const colorId = button.getAttribute('data-color-id');
            
            // Remove the tag
            button.closest('div').remove();
            
            // Enable the option again
            const option = document.querySelector(`#color-selector option[value="${colorId}"]`);
            if (option) option.disabled = false;
        }
    });
    
</script>

<?php include_once '../../includes/footer.php'; ?>