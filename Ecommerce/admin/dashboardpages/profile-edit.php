
<?php include_once '../includes/header.php' ?>
<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize variables
$error_message = '';
$success_message = '';

// Handle Update Information Form
if(isset($_POST['form1'])) {
    if($_SESSION['user']['role'] == 'Super Admin') {
        $valid = 1;

        if(empty($_POST['full_name'])) {
            $valid = 0;
            $error_message .= "Name can not be empty<br>";
        }

        if(empty($_POST['email'])) {
            $valid = 0;
            $error_message .= 'Email address can not be empty<br>';
        } else {
            if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
                $valid = 0;
                $error_message .= 'Email address must be valid<br>';
            } else {
                // Simulated database check for email existence
                // In a real application, replace this with your database query
                $current_email = $_SESSION['user']['email'];
                
                // Check if email already exists (simplified example)
                $email_exists = false;
                // Simulated database check - replace with your actual database code
                if($_POST['email'] != $current_email && $email_exists) {
                    $valid = 0;
                    $error_message .= 'Email address already exists<br>';
                }
            }
        }

        if($valid == 1) {
            // Update session information
            $_SESSION['user']['full_name'] = $_POST['full_name'];
            $_SESSION['user']['email'] = $_POST['email'];
            $_SESSION['user']['phone'] = $_POST['phone'];

            // In a real application, update the database here
            // For example: UPDATE users SET full_name = ?, email = ?, phone = ? WHERE id = ?

            $success_message = 'User Information is updated successfully.';
        }
    } else {
        // For non-admin users, only update phone
        $_SESSION['user']['phone'] = $_POST['phone'];

        // In a real application, update the database here
        // For example: UPDATE users SET phone = ? WHERE id = ?

        $success_message = 'User Information is updated successfully.';
    }
}

// Handle Update Photo Form
if(isset($_POST['form2'])) {
    $valid = 1;

    $path = $_FILES['photo']['name'];
    $path_tmp = $_FILES['photo']['tmp_name'];

    if($path != '') {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $file_name = basename($path, '.' . $ext);
        if($ext != 'jpg' && $ext != 'png' && $ext != 'jpeg' && $ext != 'gif') {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file<br>';
        }
    }

    if($valid == 1 && $path != '') {
        // Remove existing photo if it exists
        if(isset($_SESSION['user']['photo']) && $_SESSION['user']['photo'] != '') {
            // In a real app, use full path: unlink('path/to/uploads/'.$_SESSION['user']['photo']);
        }

        // Update the photo
        $final_name = 'user-' . $_SESSION['user']['id'] . '.' . $ext;
        // In a real app: move_uploaded_file($path_tmp, 'path/to/uploads/'.$final_name);
        $_SESSION['user']['photo'] = $final_name;

        // In a real application, update the database here
        // For example: UPDATE users SET photo = ? WHERE id = ?
        

        $success_message = 'User Photo is updated successfully.';
    }
}

// Handle Update Password Form
if(isset($_POST['form3'])) {
    $valid = 1;

    if(empty($_POST['password']) || empty($_POST['re_password'])) {
        $valid = 0;
        $error_message .= "Password can not be empty<br>";
    }

    if(!empty($_POST['password']) && !empty($_POST['re_password'])) {
        if($_POST['password'] != $_POST['re_password']) {
            $valid = 0;
            $error_message .= "Passwords do not match<br>";
        }
    }

    if($valid == 1) {
        // Update session information with hashed password
        $_SESSION['user']['password'] = md5($_POST['password']);

        // In a real application, update the database here
        // For example: UPDATE users SET password = ? WHERE id = ?

        $success_message = 'User Password is updated successfully.';
    }
}

// Get user information - In a real application, fetch this from your database
// This is simulated data for demonstration
if (!isset($_SESSION['user'])) {
    // Simulated user data
    $_SESSION['user'] = [
        'id' => 1,
        'full_name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '123-456-7890',
        'photo' => 'default.jpg',
        'status' => 'Active',
        'role' => 'Super Admin'
    ];
}

$full_name = $_SESSION['user']['full_name'];
$email = $_SESSION['user']['email'];
$phone = $_SESSION['user']['phone'];
$photo = $_SESSION['user']['photo'] ?? 'default.jpg';
$status = $_SESSION['user']['status'] ?? 'Active';
$role = $_SESSION['user']['role'] ?? 'User';
?>


    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Profile</h1>
        </div>

        <?php if(!empty($error_message)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo $error_message; ?></span>
        </div>
        <?php endif; ?>

        <?php if(!empty($success_message)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo $success_message; ?></span>
        </div>
        <?php endif; ?>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="flex flex-wrap">
                    <button class="tab-button text-gray-600 py-4 px-6 block hover:text-blue-500 focus:outline-none text-blue-500 border-b-2 border-blue-500 font-medium" 
                            onclick="openTab(event, 'tab_1')">
                        Update Information
                    </button>
                    <button class="tab-button text-gray-600 py-4 px-6 block hover:text-blue-500 focus:outline-none" 
                            onclick="openTab(event, 'tab_2')">
                        Update Photo
                    </button>
                    <button class="tab-button text-gray-600 py-4 px-6 block hover:text-blue-500 focus:outline-none" 
                            onclick="openTab(event, 'tab_3')">
                        Update Password
                    </button>
                </nav>
            </div>

            <div class="tab-content">
                <!-- Update Information Tab -->
                <div id="tab_1" class="tab-pane block">
                    <form action="" method="post" class="p-6">
                        <div class="mb-6">
                            <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                            <?php if($_SESSION['user']['role'] == 'Super Admin'): ?>
                                <input type="text" name="full_name" id="full_name" value="<?php echo $full_name; ?>" 
                                       class="w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <?php else: ?>
                                <div class="text-gray-700 py-2"><?php echo $full_name; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Existing Photo</label>
                            <div class="mt-1">
                                <img src="<?php echo isset($photo) ? 'uploadimgs/'.$photo : './uploadimgs/default.jpg'; ?>" 
                                     class="w-32 h-32 object-cover rounded-md" alt="Profile Photo">
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                            <?php if($_SESSION['user']['role'] == 'Super Admin'): ?>
                                <input type="email" name="email" id="email" value="<?php echo $email; ?>" 
                                       class="w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <?php else: ?>
                                <div class="text-gray-700 py-2"><?php echo $email; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-6">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="text" name="phone" id="phone" value="<?php echo $phone; ?>" 
                                   class="w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
                            <div class="text-gray-700 py-2"><?php echo $role; ?></div>
                        </div>

                        <div class="flex justify-start">
                            <button type="submit" name="form1" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Update Information
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Update Photo Tab -->
                <div id="tab_2" class="tab-pane hidden">
                    <form action="" method="post" enctype="multipart/form-data" class="p-6">
                        <div class="mb-6">
                            <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">New Photo</label>
                            <div class="mt-1">
                                <input type="file" name="photo" id="photo"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                        </div>

                        <div class="flex justify-start">
                            <button type="submit" name="form2" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Update Photo
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Update Password Tab -->
                <div id="tab_3" class="tab-pane hidden">
                    <form action="" method="post" class="p-6">
                        <div class="mb-6">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" name="password" id="password" 
                                   class="w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="mb-6">
                            <label for="re_password" class="block text-sm font-medium text-gray-700 mb-1">Retype Password</label>
                            <input type="password" name="re_password" id="re_password" 
                                   class="w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="flex justify-start">
                            <button type="submit" name="form3" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openTab(evt, tabName) {
            // Hide all tab content
            var tabContent = document.getElementsByClassName("tab-pane");
            for (var i = 0; i < tabContent.length; i++) {
                tabContent[i].classList.add("hidden");
                tabContent[i].classList.remove("block");
            }

            // Remove active class from all tab buttons
            var tabButtons = document.getElementsByClassName("tab-button");
            for (var i = 0; i < tabButtons.length; i++) {
                tabButtons[i].classList.remove("text-blue-500", "border-b-2", "border-blue-500", "font-medium");
            }

            // Show the current tab and add an "active" class to the button
            document.getElementById(tabName).classList.remove("hidden");
            document.getElementById(tabName).classList.add("block");
            evt.currentTarget.classList.add("text-blue-500", "border-b-2", "border-blue-500", "font-medium");
        }

        // Set default tab to be shown
        document.addEventListener("DOMContentLoaded", function() {
            // Default to showing first tab
            document.getElementById("tab_1").classList.remove("hidden");
            document.getElementById("tab_1").classList.add("block");
        });
    </script>
<?php include_once "../includes/footer.php" ?>