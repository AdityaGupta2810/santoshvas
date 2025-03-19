<?php 
include_once '../../includes/header.php';

// Initialize variables to store form data for persistence
$p_name = '';
$p_old_price = '';
$p_current_price = '';
$p_qty = '';
$p_size = '';
$p_color = '';
$p_description = '';
$p_short_description = '';
$p_is_active = 1; // Default to active

// Check if form is submitted
if (isset($_POST['form1'])) {
    // Collect and sanitize form data
    $p_name = mysqli_real_escape_string($db, $_POST['p_name']);
    $p_old_price = isset($_POST['p_old_price']) && !empty($_POST['p_old_price']) ? (float)$_POST['p_old_price'] : null;
    $p_current_price = (float)$_POST['p_current_price'];
    $p_qty = (int)$_POST['p_qty'];
    $p_size = isset($_POST['p_size']) && !empty($_POST['p_size']) ? (int)$_POST['p_size'] : null;
    $p_color = isset($_POST['p_color']) && !empty($_POST['p_color']) ? (int)$_POST['p_color'] : null;
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
    
    // Check if featured photo is uploaded
    if(!isset($_FILES['p_featured_photo']['name']) || empty($_FILES['p_featured_photo']['name'])) {
        $valid = false;
        $error_message .= 'Featured photo is required<br>';
    }
    
    // Handle featured photo upload
    $featured_photo = '';
    if($valid) {
        if(isset($_FILES['p_featured_photo']['name']) && !empty($_FILES['p_featured_photo']['name'])) {
            $file_name = $_FILES['p_featured_photo']['name'];
            $file_temp = $_FILES['p_featured_photo']['tmp_name'];
            $ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $allowed_exts = array('jpg', 'jpeg', 'png', 'gif');
            
            if(in_array(strtolower($ext), $allowed_exts)) {
                $featured_photo = 'product_'.time().'.'.$ext;
                move_uploaded_file($file_temp, '../../assets/uploads/products/'.$featured_photo);
            } else {
                $valid = false;
                $error_message .= 'Featured photo must be jpg, jpeg, png or gif file<br>';
            }
        }
    }
    
    // Process multiple photos if needed
    $photos = array();
    if($valid) {
        // Process additional photos
        if(isset($_FILES['p_photos']['name']) && !empty($_FILES['p_photos']['name'][0])) {
            $total_files = count($_FILES['p_photos']['name']);
            
            for($i=0; $i<$total_files; $i++) {
                $file_name = $_FILES['p_photos']['name'][$i];
                $file_temp = $_FILES['p_photos']['tmp_name'][$i];
                $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                
                if(in_array(strtolower($ext), $allowed_exts)) {
                    $photo_name = 'product_'.time().'_'.$i.'.'.$ext;
                    move_uploaded_file($file_temp, '../../assets/uploads/products/'.$photo_name);
                    $photos[] = $photo_name;
                } else {
                    $valid = false;
                    $error_message .= 'Photo '.$i.' must be jpg, jpeg, png or gif file<br>';
                    break;
                }
            }
        }
    }
    
    // Insert data if valid
    if($valid) {
        try {
            // Start transaction
            $db->begin_transaction();
            
            // Insert product - Fix: using proper parameter types and handling NULL values
            $sql = "INSERT INTO tbl_product (
                p_name, p_old_price, p_current_price, p_qty, 
                p_featured_photo, p_description, p_short_description, 
                p_is_active, ecat_id
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $statement = $db->prepare($sql);
            
            if (!$statement) {
                throw new Exception("Prepare failed: " . $db->error);
            }
            
            // Updated bind_param with correct types
            $statement->bind_param(
                "sddiissii", 
                $p_name, $p_old_price, $p_current_price, $p_qty,
                $featured_photo, $p_description, $p_short_description, 
                $p_is_active, $ecat_id
            );
            
            if (!$statement->execute()) {
                throw new Exception("Execute failed: " . $statement->error);
            }
            
            $product_id = $db->insert_id;
            
            // Handle size relationship if size is selected
            if($p_size) {
                $stmt_size = $db->prepare("INSERT INTO tbl_product_size (p_id, size_id) VALUES (?, ?)");
                if (!$stmt_size) {
                    throw new Exception("Prepare size failed: " . $db->error);
                }
                $stmt_size->bind_param("ii", $product_id, $p_size);
                if (!$stmt_size->execute()) {
                    throw new Exception("Execute size failed: " . $stmt_size->error);
                }
            }
            
            // Handle color relationship if color is selected
            if($p_color) {
                $stmt_color = $db->prepare("INSERT INTO tbl_product_color (p_id, color_id) VALUES (?, ?)");
                if (!$stmt_color) {
                    throw new Exception("Prepare color failed: " . $db->error);
                }
                $stmt_color->bind_param("ii", $product_id, $p_color);
                if (!$stmt_color->execute()) {
                    throw new Exception("Execute color failed: " . $stmt_color->error);
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
            
            $success_message = 'Product has been added successfully!';
            
            // Clear form data
            $p_name = '';
            $p_old_price = '';
            $p_current_price = '';
            $p_qty = '';
            $p_size = '';
            $p_color = '';
            $p_description = '';
            $p_short_description = '';
            $p_is_active = 1;
            
        } catch (Exception $e) {
            // Rollback transaction on error
            $db->rollback();
            $error_message = 'Database error: ' . $e->getMessage();
        }
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
    
} catch (Exception $e) {
    $error_message = 'Database error: ' . $e->getMessage();
}
?>

<div class="container mx-auto p-4">
    <!-- Header with title and back button -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold flex items-center mb-3 sm:mb-0">
            <i class="fas fa-plus-circle mr-2"></i>Add Product
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
    
    <!-- Product Add Form -->
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
                                <option value="<?php echo htmlspecialchars($row['ecat_id']); ?>" <?php echo (isset($_POST['ecat_id']) && $_POST['ecat_id'] == $row['ecat_id']) ? 'selected' : ''; ?>>
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
                        <label for="p_old_price" class="block mb-2 font-medium">Old Price (in USD)</label>
                        <input type="number" step="0.01" name="p_old_price" id="p_old_price" class="w-full border rounded px-3 py-2" value="<?php echo htmlspecialchars($p_old_price); ?>">
                    </div>
                    
                    <div class="mb-6">
                        <label for="p_current_price" class="block mb-2 font-medium">Current Price (in USD) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="p_current_price" id="p_current_price" class="w-full border rounded px-3 py-2" value="<?php echo htmlspecialchars($p_current_price); ?>" required>
                    </div>
                    
                    <div class="mb-6">
                        <label for="p_qty" class="block mb-2 font-medium">Quantity <span class="text-red-500">*</span></label>
                        <input type="number" name="p_qty" id="p_qty" class="w-full border rounded px-3 py-2" value="<?php echo htmlspecialchars($p_qty); ?>" required>
                    </div>
                    
                    <!-- Size and Color Dropdowns -->
                    <div class="mb-6">
                        <label for="p_size" class="block mb-2 font-medium">Size</label>
                        <select name="p_size" id="p_size" class="w-full border rounded px-3 py-2">
                            <option value="">Select Size</option>
                            <?php if(isset($result_sizes) && $result_sizes->num_rows > 0): ?>
                                <?php while($row = $result_sizes->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($row['id']); ?>" <?php echo ($p_size == $row['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($row['size_name']); ?>
                                </option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="mb-6">
                        <label for="p_color" class="block mb-2 font-medium">Color</label>
                        <select name="p_color" id="p_color" class="w-full border rounded px-3 py-2">
                            <option value="">Select Color</option>
                            <?php if(isset($result_colors) && $result_colors->num_rows > 0): ?>
                                <?php while($row = $result_colors->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($row['id']); ?>" <?php echo ($p_color == $row['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($row['color_name']); ?>
                                </option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div>
                    <!-- Images Section -->
                    <div class="mb-6">
                        <label for="p_featured_photo" class="block mb-2 font-medium">Featured Photo <span class="text-red-500">*</span></label>
                        <input type="file" name="p_featured_photo" id="p_featured_photo" class="w-full border rounded px-3 py-2" required>
                        <p class="text-sm text-gray-500 mt-1">Main product image (jpg, jpeg, png, gif only)</p>
                    </div>
                    
                    <div class="mb-6">
                        <label for="p_photos" class="block mb-2 font-medium">Other Photos</label>
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
            <div class="border-t border-gray-300 mt-6 pt-6 flex flex-col sm:flex-row justify-end">
                <button type="submit" name="form1" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 mb-2 sm:mb-0 sm:mr-2">
                    <i class="fas fa-save mr-2"></i>Save Product
                </button>
                <a href="products.php" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600 text-center">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Initialize rich text editors -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize rich text editors if available
    if(typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#p_description',
            height: 300,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount'
            ],
            toolbar: 'undo redo | formatselect | bold italic backcolor | \
                alignleft aligncenter alignright alignjustify | \
                bullist numlist outdent indent | removeformat | help'
        });
        
        tinymce.init({
            selector: '#p_short_description',
            height: 150,
            menubar: false,
            plugins: [
                'advlist autolink lists link charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount'
            ],
            toolbar: 'undo redo | formatselect | bold italic | \
                alignleft aligncenter alignright alignjustify | \
                bullist numlist | removeformat'
        });
    }
});
</script>

<?php include_once '../../includes/footer.php'; ?>