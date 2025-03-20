<?php
include_once '../../includes/header.php';

// Initialize variables
$p_name = $p_old_price = $p_current_price = $p_qty = $p_description = $p_short_description = $p_feature = '';
$p_is_active = 1;
$p_is_featured = 0;
$ecat_id = '';
$p_sizes = $p_colors = [];
$errors = [];
$success = '';

// Process form submission
if (isset($_POST['form1'])) {
    // Get form data with validation
    $p_name = mysqli_real_escape_string($db, $_POST['p_name']);
    $p_old_price = mysqli_real_escape_string($db, $_POST['p_old_price']);
    $p_current_price = mysqli_real_escape_string($db, $_POST['p_current_price']);
    $p_qty = mysqli_real_escape_string($db, $_POST['p_qty']);
    $p_description = mysqli_real_escape_string($db, $_POST['p_description']);
    $p_short_description = mysqli_real_escape_string($db, $_POST['p_short_description']);
    $p_feature = mysqli_real_escape_string($db, $_POST['p_feature']);
    $p_is_active = isset($_POST['p_is_active']) ? 1 : 0;
    $p_is_featured = isset($_POST['p_is_featured']) ? 1 : 0;
    $ecat_id = mysqli_real_escape_string($db, $_POST['ecat_id']);
    $p_sizes = isset($_POST['p_size']) ? $_POST['p_size'] : [];
    $p_colors = isset($_POST['p_color']) ? $_POST['p_color'] : [];

    // Validate required fields
    if (empty($p_name)) $errors[] = "Product name cannot be empty";
    if (empty($p_current_price)) $errors[] = "Current price cannot be empty";
    elseif (!is_numeric($p_current_price)) $errors[] = "Current price must be a number";
    if (!empty($p_old_price) && !is_numeric($p_old_price)) $errors[] = "Old price must be a number";
    if (empty($p_qty)) $errors[] = "Quantity cannot be empty";
    elseif (!is_numeric($p_qty)) $errors[] = "Quantity must be a number";
    if (empty($ecat_id)) $errors[] = "You must select a category";

    // Handle featured photo upload
    $target_dir = "../../assets/uploads/products/";
    $p_featured_photo = '';
    
    if (isset($_FILES['p_featured_photo']) && $_FILES['p_featured_photo']['error'] == 0) {
        $filename = basename($_FILES["p_featured_photo"]["name"]);
        $fileType = pathinfo($filename, PATHINFO_EXTENSION);
        $new_filename = uniqid() . '.' . $fileType;
        $target_file = $target_dir . $new_filename;
        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        
        if (!in_array(strtolower($fileType), $allowed_types)) {
            $errors[] = "Only JPG, JPEG, PNG & GIF files are allowed for the featured photo.";
        } elseif ($_FILES["p_featured_photo"]["size"] > 5000000) {
            $errors[] = "Featured photo file is too large. Maximum size: 5MB";
        } elseif (move_uploaded_file($_FILES["p_featured_photo"]["tmp_name"], $target_file)) {
            $p_featured_photo = $new_filename;
        } else {
            $errors[] = "Sorry, there was an error uploading your featured photo.";
        }
    } else {
        $errors[] = "Featured photo is required";
    }

    // Handle product photos (multiple)
    $photo_names = [];
    if (!empty($_FILES['p_photos']['name'][0])) {
        $total = count($_FILES['p_photos']['name']);
        
        for ($i = 0; $i < $total; $i++) {
            if ($_FILES['p_photos']['error'][$i] == 0) {
                $filename = basename($_FILES["p_photos"]["name"][$i]);
                $fileType = pathinfo($filename, PATHINFO_EXTENSION);
                $new_filename = uniqid() . '.' . $fileType;
                $target_file = $target_dir . $new_filename;
                $allowed_types = ["jpg", "jpeg", "png", "gif"];
                
                if (!in_array(strtolower($fileType), $allowed_types)) {
                    $errors[] = "Only JPG, JPEG, PNG & GIF files are allowed for product photos.";
                    break;
                } elseif ($_FILES["p_photos"]["size"][$i] > 5000000) {
                    $errors[] = "One or more photo files are too large. Maximum size: 5MB";
                    break;
                } elseif (move_uploaded_file($_FILES["p_photos"]["tmp_name"][$i], $target_file)) {
                    $photo_names[] = $new_filename;
                } else {
                    $errors[] = "Sorry, there was an error uploading one of your product photos.";
                    break;
                }
            }
        }
    }

    // If no errors, insert product into database
    if (empty($errors)) {
        try {
            // Insert into tbl_product
            $stmt = $db->prepare("INSERT INTO tbl_product (p_name, p_old_price, p_current_price, p_qty, p_featured_photo, p_description, p_short_description, p_feature, p_is_active, p_is_featured, ecat_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssiis", $p_name, $p_old_price, $p_current_price, $p_qty, $p_featured_photo, $p_description, $p_short_description, $p_feature, $p_is_active, $p_is_featured, $ecat_id);
            
            if ($stmt->execute()) {
                $p_id = $stmt->insert_id;
                
                // Insert product photos
                if (!empty($photo_names)) {
                    $stmt = $db->prepare("INSERT INTO tbl_product_photo (p_id, photo) VALUES (?, ?)");
                    foreach ($photo_names as $photo) {
                        $stmt->bind_param("is", $p_id, $photo);
                        $stmt->execute();
                    }
                }
                
                // Insert sizes and colors
                if (!empty($p_sizes)) {
                    $stmt_size = $db->prepare("INSERT INTO tbl_product_size (p_id, size_id) VALUES (?, ?)");
                    foreach($p_sizes as $size_id) {
                        $size_id = (int)$size_id;
                        $stmt_size->bind_param("ii", $p_id, $size_id);
                        $stmt_size->execute();
                    }
                }
                
                if (!empty($p_colors)) {
                    $stmt_color = $db->prepare("INSERT INTO tbl_product_color (p_id, color_id) VALUES (?, ?)");
                    foreach($p_colors as $color_id) {
                        $color_id = (int)$color_id;
                        $stmt_color->bind_param("ii", $p_id, $color_id);
                        $stmt_color->execute();
                    }
                }
                
                $success = "Product added successfully!";
                
                // Reset form fields
                $p_name = $p_old_price = $p_current_price = $p_qty = $p_description = $p_short_description = $p_feature = '';
                $p_is_active = 1;
                $p_is_featured = 0;
                $ecat_id = '';
                $p_sizes = $p_colors = [];
            } else {
                $errors[] = "Error adding product: " . $stmt->error;
            }
        } catch (Exception $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}

// Get categories, sizes, and colors for dropdowns
try {
    $stmt_categories = $db->prepare("
        SELECT e.ecat_id, e.ecat_name, m.mcat_name, t.tcat_name 
        FROM tbl_end_category e
        JOIN tbl_mid_category m ON e.mcat_id = m.mcat_id
        JOIN tbl_top_category t ON m.tcat_id = t.tcat_id
        ORDER BY t.tcat_name, m.mcat_name, e.ecat_name
    ");
    $stmt_categories->execute();
    $result_categories = $stmt_categories->get_result();
    
    $stmt_sizes = $db->prepare("SELECT id, size_name FROM tbl_size ORDER BY size_name");
    $stmt_sizes->execute();
    $result_sizes = $stmt_sizes->get_result();
    
    $stmt_colors = $db->prepare("SELECT id, color_name FROM tbl_color ORDER BY color_name");
    $stmt_colors->execute();
    $result_colors = $stmt_colors->get_result();
    
    $sizes_map = [];
    $colors_map = [];
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
    $errors[] = 'Database error: ' . $e->getMessage();
}
?>

<div class="container mx-auto p-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold flex items-center mb-3 sm:mb-0">
            <i class="fas fa-plus-square mr-2"></i>Add Product
        </h1>
        <button class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700 w-full sm:w-auto">
            <a href="products.php" class="block text-center">
                <i class="fas fa-arrow-left mr-2"></i>Back to Products
            </a>
        </button>
    </div>

    <div class="border-t border-b border-gray-300 my-4"></div>

    <!-- Display errors and success message -->
    <?php if (!empty($errors)): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p class="font-bold">Errors:</p>
            <ul class="ml-4 list-disc">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p><?php echo $success; ?></p>
        </div>
    <?php endif; ?>

    <!-- Product Add Form -->
    <div class="bg-white rounded shadow p-6">
        <form action="" method="post" enctype="multipart/form-data">
            <!-- Basic Product Information -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Basic Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="p_name" class="block mb-2 font-medium">Product Name *</label>
                        <input type="text" name="p_name" id="p_name" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars($p_name); ?>">
                    </div>

                    <div>
                        <label for="ecat_id" class="block mb-2 font-medium">Category *</label>
                        <select name="ecat_id" id="ecat_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select a category</option>
                            <?php while ($row = $result_categories->fetch_assoc()): ?>
                                <option value="<?php echo $row['ecat_id']; ?>" <?php echo ($ecat_id == $row['ecat_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($row['tcat_name'] . ' → ' . $row['mcat_name'] . ' → ' . $row['ecat_name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label for="p_old_price" class="block mb-2 font-medium">Old Price</label>
                        <input type="text" name="p_old_price" id="p_old_price" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars($p_old_price); ?>">
                    </div>

                    <div>
                        <label for="p_current_price" class="block mb-2 font-medium">Current Price ($) *</label>
                        <input type="text" name="p_current_price" id="p_current_price" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars($p_current_price); ?>">
                    </div>

                    <div>
                        <label for="p_qty" class="block mb-2 font-medium">Quantity *</label>
                        <input type="text" name="p_qty" id="p_qty" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars($p_qty); ?>">
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-300 my-6"></div>

            <!-- Product Images -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Product Images</h2>
                
                <div class="mb-4">
                    <label for="p_featured_photo" class="block mb-2 font-medium">Featured Photo *</label>
                    <input type="file" name="p_featured_photo" id="p_featured_photo" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" accept="image/*">
                    <p class="text-sm text-gray-500 mt-1">Maximum file size: 5MB. Allowed formats: JPG, JPEG, PNG, GIF</p>
                </div>

                <div class="mb-4">
                    <label for="p_photos" class="block mb-2 font-medium">Other Photos (Multiple)</label>
                    <input type="file" name="p_photos[]" id="p_photos" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" accept="image/*" multiple>
                    <p class="text-sm text-gray-500 mt-1">Maximum file size: 5MB per image. Allowed formats: JPG, JPEG, PNG, GIF</p>
                </div>
            </div>

            <div class="border-t border-gray-300 my-6"></div>

            <!-- Size and Color Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Size Selection -->
                <div>
                    <label class="block mb-2 font-medium">Size</label>
                    <div id="selected-sizes" class="flex flex-wrap gap-2 mb-3 min-h-8">
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
                    </div>
                    
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
                
                <!-- Color Selection -->
                <div>
                    <label class="block mb-2 font-medium">Color</label>
                    <div id="selected-colors" class="flex flex-wrap gap-2 mb-3 min-h-8">
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
                    </div>
                    
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
            </div>

            <div class="border-t border-gray-300 my-6"></div>

            <!-- Product Description -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Product Description</h2>
                
                <div class="mb-4">
                    <label for="p_short_description" class="block mb-2 font-medium">Short Description</label>
                    <textarea name="p_short_description" id="p_short_description" rows="3" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($p_short_description); ?></textarea>
                </div>

                <div class="mb-4">
                    <label for="p_description" class="block mb-2 font-medium">Full Description</label>
                    <textarea name="p_description" id="p_description" rows="6" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($p_description); ?></textarea>
                </div>

                <div class="mb-4">
                    <label for="p_feature" class="block mb-2 font-medium">Features (One feature per line)</label>
                    <textarea name="p_feature" id="p_feature" rows="4" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($p_feature); ?></textarea>
                </div>
            </div>

            <div class="border-t border-gray-300 my-6"></div>

            <!-- Product Settings -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Product Settings</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="p_is_active" id="p_is_active" class="mr-2" <?php echo $p_is_active ? 'checked' : ''; ?>>
                        <label for="p_is_active" class="font-medium">Active (Product is visible on the website)</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="p_is_featured" id="p_is_featured" class="mr-2" <?php echo $p_is_featured ? 'checked' : ''; ?>>
                        <label for="p_is_featured" class="font-medium">Featured (Product appears in featured section)</label>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-300 my-6"></div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" name="form1" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-plus-circle mr-2"></i>Add Product
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Size selection handling
const sizeSelector = document.getElementById('size-selector');
const selectedSizesContainer = document.getElementById('selected-sizes');

sizeSelector.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const sizeId = this.value;
    
    if (sizeId === '') return;
    
    const sizeName = selectedOption.getAttribute('data-size-name');
    const sizeTag = document.createElement('div');
    sizeTag.className = 'bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm flex items-center';
    sizeTag.innerHTML = `
        <span>${sizeName}</span>
        <button type="button" class="ml-2 text-blue-500 hover:text-blue-700 remove-size" data-size-id="${sizeId}">
            <i class="fas fa-times"></i>
        </button>
        <input type="hidden" name="p_size[]" value="${sizeId}">
    `;
    
    selectedSizesContainer.appendChild(sizeTag);
    selectedOption.disabled = true;
    this.selectedIndex = 0;
});

selectedSizesContainer.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-size') || e.target.closest('.remove-size')) {
        const removeButton = e.target.classList.contains('remove-size') ? e.target : e.target.closest('.remove-size');
        const sizeId = removeButton.getAttribute('data-size-id');
        removeButton.closest('div').remove();
        
        const option = sizeSelector.querySelector(`option[value="${sizeId}"]`);
        if (option) option.disabled = false;
    }
});

// Color selection handling
const colorSelector = document.getElementById('color-selector');
const selectedColorsContainer = document.getElementById('selected-colors');

colorSelector.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const colorId = this.value;
    
    if (colorId === '') return;
    
    const colorName = selectedOption.getAttribute('data-color-name');
    const colorTag = document.createElement('div');
    colorTag.className = 'bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm flex items-center';
    colorTag.innerHTML = `
        <span>${colorName}</span>
        <button type="button" class="ml-2 text-blue-500 hover:text-blue-700 remove-color" data-color-id="${colorId}">
            <i class="fas fa-times"></i>
        </button>
        <input type="hidden" name="p_color[]" value="${colorId}">
    `;
    
    selectedColorsContainer.appendChild(colorTag);
    selectedOption.disabled = true;
    this.selectedIndex = 0;
});

selectedColorsContainer.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-color') || e.target.closest('.remove-color')) {
        const removeButton = e.target.classList.contains('remove-color') ? e.target : e.target.closest('.remove-color');
        const colorId = removeButton.getAttribute('data-color-id');
        removeButton.closest('div').remove();
        
        const option = colorSelector.querySelector(`option[value="${colorId}"]`);
        if (option) option.disabled = false;
    }
});

// Preview image on file select
document.getElementById('p_featured_photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.createElement('div');
            preview.innerHTML = `<div class="mt-2 flex items-center"><img src="${e.target.result}" alt="Preview" class="w-24 h-24 object-cover rounded border"><span class="ml-2 text-sm text-gray-500">Preview</span></div>`;
            
            const oldPreview = document.querySelector('.preview-container');
            if (oldPreview) oldPreview.remove();
            
            preview.classList.add('preview-container');
            e.target.parentNode.appendChild(preview);
        }
        reader.readAsDataURL(file);
    }
});

// Add rich text editor if available
if (typeof CKEDITOR !== 'undefined') {
    CKEDITOR.replace('p_description');
}
</script>

<?php include_once '../../includes/footer.php'; ?>