<?php
include_once '../../includes/header.php';

// Validate input
if (isset($_GET['tcat_id']) && is_numeric($_GET['tcat_id'])) {
    $tcatId = (int)$_GET['tcat_id'];
} else {
    echo '<option value="">Select Mid Level Category</option>';
    exit;
}

$output = '<option value="">Select Mid Level Category</option>';

if ($tcatId > 0) {
    $query = "SELECT * FROM tbl_mid_category WHERE tcat_id = $tcatId ORDER BY mcat_name ASC";
    $result = mysqli_query($db, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $output .= '<option value="' . $row['mcat_id'] . '">' . htmlspecialchars($row['mcat_name']) . '</option>';
        }
    }
}

echo $output;
?>