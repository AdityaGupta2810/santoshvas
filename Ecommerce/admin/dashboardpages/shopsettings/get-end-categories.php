<?php
include_once '../../includes/header.php';

// Validate input
if (isset($_GET['mcat_id']) && is_numeric($_GET['mcat_id'])) {
    $mcatId = (int)$_GET['mcat_id'];
} else {
    echo '<option value="">Select End Level Category</option>';
    exit;
}

$output = '<option value="">Select End Level Category</option>';

if ($mcatId > 0) {
    $query = "SELECT * FROM tbl_end_category WHERE mcat_id = $mcatId ORDER BY ecat_name ASC";
    $result = mysqli_query($db, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $output .= '<option value="' . $row['ecat_id'] . '">' . htmlspecialchars($row['ecat_name']) . '</option>';
        }
    }
}

echo $output;
?>