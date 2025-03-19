<?php
include_once '../../includes/header.php';

// Initialize variables for form data
$p_name = '';
$p_old_price = '';
$p_current_price = '';
$p_qty = '';
$p_description = '';
$p_short_description = '';
$p_feature = '';
$p_is_active = 1;
$p_is_featured = 0;
$ecat_id = '';

// Arrays to store validation errors and success messages
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

    // Form validation
    if (empty($p_name)) {
        $errors[] = "Product name cannot be empty";
    }
    
    if (empty($p_current_price)) {
        $errors[] = "Current price cannot be empty";
    } elseif (!is_numeric($p_current_price)) {
        $errors[] = "Current price must be a number";
    }
    
    if (!empty($p_old_price) && !is_numeric($p_old_price)) {
        $errors[] = "Old price must be a number";
    }
    
    if (empty($p_qty)) {
        $errors[] = "Quantity cannot be empty";
    } elseif (!is_numeric($p_qty)) {
        $errors[] = "Quantity must be a number";
    }
    
    if (empty($ecat_id)) {
        $errors[] = "You must select a category";
    }

    // Handle featured photo upload
    $target_dir = "../../assets/uploads/products/";
    $p_featured_photo = '';
    
    if (isset($_FILES['p_featured_photo']) && $_FILES['p_featured_photo']['error'] == 0) {
        $filename = basename($_FILES["p_featured_photo"]["name"]);
        $fileType = pathinfo($filename, PATHINFO_EXTENSION);
        
        // Generate unique filename
        $new_filename = uniqid() . '.' . $fileType;
        $target_file = $target_dir . $new_filename;

        // Check file type
        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array(strtolower($fileType), $allowed_types)) {
            $errors[] = "Only JPG, JPEG, PNG & GIF files are allowed for the featured photo.";
        }
        
        // Check file size (limit to 5MB)
        if ($_FILES["p_featured_photo"]["size"] > 5000000) {
            $errors[] = "Featured photo file is too large. Maximum size: 5MB";
        }
        
        // If no errors, try to upload the file
        if (empty($errors)) {
            if (!move_uploaded_file($_FILES["p_featured_photo"]["tmp_name"], $target_file)) {
                $errors[] = "Sorry, there was an error uploading your featured photo.";
            } else {
                $p_featured_photo = $new_filename;
            }
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
                
                // Generate unique filename
                $new_filename = uniqid() . '.' . $fileType;
                $target_file = $target_dir . $new_filename;

                // Check file type
                $allowed_types = ["jpg", "jpeg", "png", "gif"];
                if (!in_array(strtolower($fileType), $allowed_types)) {
                    $errors[] = "Only JPG, JPEG, PNG & GIF files are allowed for product photos.";
                    break;
                }
                
                // Check file size (limit to 5MB)
                if ($_FILES["p_photos"]["size"][$i] > 5000000) {
                    $errors[] = "One or more photo files are too large. Maximum size: 5MB";
                    break;
                }
                
                // Try to upload the file
                if (empty($errors)) {
                    if (move_uploaded_file($_FILES["p_photos"]["tmp_name"][$i], $target_file)) {
                        $photo_names[] = $new_filename;
                    } else {
                        $errors[] = "Sorry, there was an error uploading one of your product photos.";
                        break;
                    }
                }
            }
        }
    }

    // If no errors, insert product into database
    if (empty($errors)) {
        // Insert into tbl_product
        $stmt = $db->prepare("INSERT INTO tbl_product (p_name, p_old_price, p_current_price, p_qty, p_featured_photo, p_description, p_short_description, p_feature, p_is_active, p_is_featured, ecat_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssiis", $p_name, $p_old_price, $p_current_price, $p_qty, $p_featured_photo, $p_description, $p_short_description, $p_feature, $p_is_active, $p_is_featured, $ecat_id);
        
        if ($stmt->execute()) {
            $p_id = $stmt->insert_id;
            
            // Insert product photos if any
            if (!empty($photo_names)) {
                foreach ($photo_names as $photo) {
                    $stmt = $db->prepare("INSERT INTO tbl_product_photo (p_id, photo) VALUES (?, ?)");
                    $stmt->bind_param("is", $p_id, $photo);
                    $stmt->execute();
                }
            }
            
            $success = "Product added successfully!";
            
            // Reset form fields
            $p_name = '';
            $p_old_price = '';
            $p_current_price = '';
            $p_qty = '';
            $p_description = '';
            $p_short_description = '';
            $p_feature = '';
            $p_is_active = 1;
            $p_is_featured = 0;
            $ecat_id = '';
        } else {
            $errors[] = "Error adding product: " . $stmt->error;
        }
    }
}

// Get categories for dropdown
$categories_query = "SELECT e.ecat_id, e.ecat_name, m.mcat_name, t.tcat_name 
                    FROM tbl_end_category e
                    JOIN tbl_mid_category m ON e.mcat_id = m.mcat_id
                    JOIN tbl_top_category t ON m.tcat_id = t.tcat_id
                    ORDER BY t.tcat_name, m.mcat_name, e.ecat_name";
$categories_result = mysqli_query($db, $categories_query);
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

    <!-- Display errors if any -->
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

    <!-- Display success message if any -->
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
                    <!-- Product Name -->
                    <div>
                        <label for="p_name" class="block mb-2 font-medium">Product Name *</label>
                        <input type="text" name="p_name" id="p_name" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars($p_name); ?>">
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="ecat_id" class="block mb-2 font-medium">Category *</label>
                        <select name="ecat_id" id="ecat_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select a category</option>
                            <?php while ($row = mysqli_fetch_assoc($categories_result)): ?>
                                <option value="<?php echo $row['ecat_id']; ?>" <?php echo ($ecat_id == $row['ecat_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($row['tcat_name'] . ' → ' . $row['mcat_name'] . ' → ' . $row['ecat_name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <!-- Old Price -->
                    <div>
                        <label for="p_old_price" class="block mb-2 font-medium">Old Price ($)</label>
                        <input type="text" name="p_old_price" id="p_old_price" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars($p_old_price); ?>">
                    </div>

                    <!-- Current Price -->
                    <div>
                        <label for="p_current_price" class="block mb-2 font-medium">Current Price ($) *</label>
                        <input type="text" name="p_current_price" id="p_current_price" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars($p_current_price); ?>">
                    </div>

                    <!-- Quantity -->
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
                
                <div class="flex items-center mb-4">
                    <input type="checkbox" name="p_is_active" id="p_is_active" class="mr-2" <?php echo $p_is_active ? 'checked' : ''; ?>>
                    <label for="p_is_active" class="font-medium">Active (Product is visible on the website)</label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="p_is_featured" id="p_is_featured" class="mr-2" <?php echo $p_is_featured ? 'checked' : ''; ?>>
                    <label for="p_is_featured" class="font-medium">Featured (Product appears in featured section)</label>
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
// Add rich text editor for product description if you have one integrated
// Example: if you have CKEditor included in your project
if (typeof CKEDITOR !== 'undefined') {
    CKEDITOR.replace('p_description');
}

// Preview image on file select for featured photo
document.getElementById('p_featured_photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.createElement('div');
            preview.innerHTML = `
                <div class="mt-2 flex items-center">
                    <img src="${e.target.result}" alt="Preview" class="w-24 h-24 object-cover rounded border">
                    <span class="ml-2 text-sm text-gray-500">Preview</span>
                </div>
            `;
            
            // Remove previous preview if exists
            const oldPreview = document.querySelector('.preview-container');
            if (oldPreview) {
                oldPreview.remove();
            }
            
            preview.classList.add('preview-container');
            e.target.parentNode.appendChild(preview);
        }
        reader.readAsDataURL(file);
    }
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    let errors = [];
    const required = ['p_name', 'p_current_price', 'p_qty', 'ecat_id'];
    
    required.forEach(field => {
        const element = document.getElementById(field);
        if (!element.value.trim()) {
            errors.push(`${element.previousElementSibling.textContent.replace(' *', '')} is required`);
            element.classList.add('border-red-500');
        } else {
            element.classList.remove('border-red-500');
        }
    });
    
    // Validate numeric fields
    ['p_current_price', 'p_old_price', 'p_qty'].forEach(field => {
        const element = document.getElementById(field);
        if (element.value.trim() && isNaN(element.value)) {
            errors.push(`${element.previousElementSibling.textContent.replace(' *', '')} must be a number`);
            element.classList.add('border-red-500');
        }
    });
    
    // Featured photo is required for new products
    const featuredPhoto = document.getElementById('p_featured_photo');
    if (featuredPhoto.files.length === 0 && !document.querySelector('.preview-container')) {
        errors.push('Featured photo is required');
    }
    
    if (errors.length > 0) {
        e.preventDefault();
        alert(errors.join('\n'));
    }
});
</script>

<?php 
include_once '../../includes/footer.php'; 
?>