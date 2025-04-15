<?php 
include_once '../../includes/header.php'; 

// Define variables for pagination and sorting
$itemsPerPage = isset($_GET['entries']) ? (int)$_GET['entries'] : 10;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($db, $_GET['search']) : '';
$sortColumn = isset($_GET['sort']) ? mysqli_real_escape_string($db, $_GET['sort']) : 'p_id';
$sortOrder = isset($_GET['order']) ? mysqli_real_escape_string($db, $_GET['order']) : 'ASC';

// Validate sort column to prevent SQL injection
$allowedColumns = ['p_id', 'p_name', 'p_old_price', 'p_current_price', 'p_qty', 'p_is_active', 'ecat_id'];
if (!in_array($sortColumn, $allowedColumns)) {
    $sortColumn = 'p_id';
}

// Validate sort order
if ($sortOrder != 'ASC' && $sortOrder != 'DESC') {
    $sortOrder = 'ASC';
}

// Calculate offset for query
$offset = ($currentPage - 1) * $itemsPerPage;

// Prepare search condition
$searchCondition = '';
if (!empty($searchTerm)) {
    $searchTerm = strtolower($searchTerm);
    $searchCondition = " WHERE LOWER(p.p_name) LIKE '%$searchTerm%'";
}

// Count total records for pagination
$countQuery = "SELECT COUNT(*) as total FROM tbl_product p" . $searchCondition;
$countResult = mysqli_query($db, $countQuery);
$totalRecords = mysqli_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalRecords / $itemsPerPage);

// Get records for current page with sorting
$query = "SELECT p.*, e.ecat_name, m.mcat_name, t.tcat_name 
          FROM tbl_product p
          LEFT JOIN tbl_end_category e ON p.ecat_id = e.ecat_id
          LEFT JOIN tbl_mid_category m ON e.mcat_id = m.mcat_id
          LEFT JOIN tbl_top_category t ON m.tcat_id = t.tcat_id"
          . $searchCondition . " ORDER BY $sortColumn $sortOrder LIMIT $offset, $itemsPerPage";

$result = mysqli_query($db, $query);

// Handle product deletion if requested
if (isset($_GET['delete_id']) && !empty($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $delete_query = "DELETE FROM tbl_product WHERE p_id = $delete_id";
    
    if (mysqli_query($db, $delete_query)) {
        // Redirect to avoid resubmission on page refresh
        header("Location: products.php");
        exit();
    }
}

// Function to create sort URL
function getSortUrl($column, $currentSort, $currentOrder) {
    $newOrder = ($currentSort == $column && $currentOrder == 'ASC') ? 'DESC' : 'ASC';
    $params = $_GET;
    $params['sort'] = $column;
    $params['order'] = $newOrder;
    return '?' . http_build_query($params);
}

// Function to get sort icon
function getSortIcon($column, $currentSort, $currentOrder) {
    if ($currentSort != $column) {
        return '<i class="fas fa-sort text-gray-400"></i>';
    }
    return ($currentOrder == 'ASC') ? 
        '<i class="fas fa-sort-up text-blue-500"></i>' : 
        '<i class="fas fa-sort-down text-blue-500"></i>';
}
?>

<div class="container mx-auto p-4">
    <!-- Header with View Products and Add New button -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
      <h1 class="text-2xl font-semibold flex items-center mb-3 sm:mb-0">
        <i class="fas fa-box mr-2"></i>View Products
      </h1>
      <button id="addNewBtn" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700 w-full sm:w-auto">
        <a href="products-add.php" class="block text-center">Add Product</a>
      </button>
    </div>
    
    <div class="border-t border-b border-gray-300 my-4"></div>
    
    <!-- Controls and search -->
    <form method="GET" action="products.php" class="flex flex-col sm:flex-row justify-between items-center mb-4">
      <div class="flex items-center mb-3 sm:mb-0 w-full sm:w-auto">
        <span class="mr-2">Show</span>
        <select name="entries" id="entriesSelect" class="border rounded px-2 py-1 mr-2" onchange="this.form.submit()">
          <option value="10" <?php echo $itemsPerPage == 10 ? 'selected' : ''; ?>>10</option>
          <option value="25" <?php echo $itemsPerPage == 25 ? 'selected' : ''; ?>>25</option>
          <option value="50" <?php echo $itemsPerPage == 50 ? 'selected' : ''; ?>>50</option>
          <option value="100" <?php echo $itemsPerPage == 100 ? 'selected' : ''; ?>>100</option>
        </select>
        <span>entries</span>
        
        <!-- Hidden sort inputs -->
        <input type="hidden" name="sort" value="<?php echo $sortColumn; ?>">
        <input type="hidden" name="order" value="<?php echo $sortOrder; ?>">
        <input type="hidden" name="page" value="1"> <!-- Reset to first page on search -->
      </div>
      
      <div class="flex items-center w-full sm:w-auto">
        <span class="mr-2">Search:</span>
        <input type="text" name="search" id="searchInput" class="border rounded px-2 py-1 w-full sm:w-64" 
               value="<?php echo htmlspecialchars($searchTerm); ?>" placeholder="Search by product name..." />
        <button type="submit" class="ml-2 bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
          <i class="fas fa-search"></i>
        </button>
      </div>
    </form>
    
    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded shadow">
      <table class="w-full bg-white border-collapse">
        <thead>
          <tr class="bg-gray-50">
            <th class="py-2 px-4 border text-left w-16 relative">
              <a href="<?php echo getSortUrl('p_id', $sortColumn, $sortOrder); ?>" class="flex items-center justify-between">
                # 
                <span class="ml-1">
                  <?php echo getSortIcon('p_id', $sortColumn, $sortOrder); ?>
                </span>
              </a>
            </th>
            <th class="py-2 px-4 border text-left relative">
              Photo
            </th>
            <th class="py-2 px-4 border text-left relative">
              <a href="<?php echo getSortUrl('p_name', $sortColumn, $sortOrder); ?>" class="flex items-center justify-between">
                Product Name
                <span class="ml-1">
                  <?php echo getSortIcon('p_name', $sortColumn, $sortOrder); ?>
                </span>
              </a>
            </th>
            <th class="py-2 px-4 border text-left relative">
              <a href="<?php echo getSortUrl('p_old_price', $sortColumn, $sortOrder); ?>" class="flex items-center justify-between">
                Old Price
                <span class="ml-1">
                  <?php echo getSortIcon('p_old_price', $sortColumn, $sortOrder); ?>
                </span>
              </a>
            </th>
            <th class="py-2 px-4 border text-left relative">
              <a href="<?php echo getSortUrl('p_current_price', $sortColumn, $sortOrder); ?>" class="flex items-center justify-between">
               (C) Price
                <span class="ml-1">
                  <?php echo getSortIcon('p_current_price', $sortColumn, $sortOrder); ?>
                </span>
              </a>
            </th>
            <th class="py-2 px-4 border text-left relative">
              <a href="<?php echo getSortUrl('p_qty', $sortColumn, $sortOrder); ?>" class="flex items-center justify-between">
               Quantity
                <span class="ml-1">
                  <?php echo getSortIcon('p_qty', $sortColumn, $sortOrder); ?>
                </span>
              </a>
            </th>
            <!-- <th class="py-2 px-4 border text-left relative">
              Featured?
            </th> -->
            <th class="py-2 px-4 border text-left relative">
              <a href="<?php echo getSortUrl('p_is_active', $sortColumn, $sortOrder); ?>" class="flex items-center justify-between">
              Active?
                <span class="ml-1">
                  <?php echo getSortIcon('p_is_active', $sortColumn, $sortOrder); ?>
                </span>
              </a>
            </th>
            <th class="py-2 px-4 border text-left relative">
              Category
            </th>
            <th class="py-2 px-4 border text-left relative">
              Action
            </th>
          </tr>
        </thead>
        <tbody id="productsTableBody">
          <?php 
          if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                  echo '<tr class="hover:bg-gray-50">';
                  echo '<td class="py-2 px-4 border">' . $row['p_id'] . '</td>';
                  echo '<td class="py-2 px-4 border"><img src="../../uploadimgs/' . htmlspecialchars($row['p_featured_photo']) . '" alt="Product" class="w-20 h-auto"></td>';

                  echo '<td class="py-2 px-4 border">' . htmlspecialchars($row['p_name']) . '</td>';
                  echo '<td class="py-2 px-4 border">' . htmlspecialchars($row['p_old_price']) . '</td>';
                  echo '<td class="py-2 px-4 border">' . htmlspecialchars($row['p_current_price']) . '</td>';
                  echo '<td class="py-2 px-4 border">' . htmlspecialchars($row['p_qty']) . '</td>';
                //   echo '<td class="py-2 px-4 border text-center">';
                //   echo ($row['p_is_featured'] == 1) ? '<span class="text-white bg-green-500 px-2 py-1 rounded">Yes</span>' : '<span class="text-white bg-red-500 px-2 py-1 rounded">No</span>';
                //   echo '</td>';
                  echo '<td class="py-2 px-4 border text-center">';
                  echo ($row['p_is_active'] == 1) ? '<span class="text-white bg-green-500 px-2 py-1 rounded">Yes</span>' : '<span class="text-white bg-red-500 px-2 py-1 rounded">No</span>';
                  echo '</td>';
                  echo '<td class="py-2 px-4 border">' . htmlspecialchars($row['tcat_name']). "<br>" . htmlspecialchars($row['mcat_name']) . "<br>" . htmlspecialchars($row['ecat_name']) . '</td>';
                  echo '<td class="py-2 px-4 border flex flex-col sm:flex-row">';
                  echo '<a href="products-edit.php?p_id=' . $row['p_id'] . '" class="bg-blue-500 text-white px-3 py-1 rounded mb-1 sm:mb-0 sm:mr-1 text-center hover:bg-blue-600">';
                  echo '<i class="fas fa-edit mr-1"></i> Edit</a>';
                  echo '<a href="products.php?delete_id=' . $row['p_id'] . '" class="bg-red-500 text-white px-3 py-1 rounded text-center hover:bg-red-600" ';
                  echo 'onclick="return confirm(\'Are you sure you want to delete this product?\')"><i class="fas fa-trash mr-1"></i> Delete</a>';
                  echo '</td>';
                  echo '</tr>';
              }
          } else {
              echo '<tr><td colspan="10" class="py-4 px-4 text-center">No products found</td></tr>';
          }
          ?>
        </tbody>
      </table>
    </div>
    
    <!-- Pagination -->
    <div class="flex flex-col sm:flex-row justify-between items-center mt-4">
      <div id="pageInfo" class="text-sm mb-3 sm:mb-0">
        Showing <?php echo min($totalRecords, 1 + $offset); ?> to <?php echo min($offset + $itemsPerPage, $totalRecords); ?> of <?php echo $totalRecords; ?> entries
      </div>
      <div class="flex flex-wrap justify-center">
        <a href="?page=1&entries=<?php echo $itemsPerPage; ?>&search=<?php echo urlencode($searchTerm); ?>&sort=<?php echo $sortColumn; ?>&order=<?php echo $sortOrder; ?>" 
           class="border rounded px-3 py-1 mr-1 mb-1 bg-white hover:bg-gray-100 <?php echo $currentPage <= 1 ? 'opacity-50 cursor-not-allowed' : ''; ?>">
          First
        </a>
        
        <a href="?page=<?php echo max(1, $currentPage - 1); ?>&entries=<?php echo $itemsPerPage; ?>&search=<?php echo urlencode($searchTerm); ?>&sort=<?php echo $sortColumn; ?>&order=<?php echo $sortOrder; ?>" 
           class="border rounded px-3 py-1 mr-1 mb-1 bg-white hover:bg-gray-100 <?php echo $currentPage <= 1 ? 'opacity-50 cursor-not-allowed' : ''; ?>">
          Previous
        </a>
        
        <?php
        $startPage = max(1, min($currentPage - 2, $totalPages - 4));
        $endPage = min($startPage + 4, $totalPages);
        
        for ($i = $startPage; $i <= $endPage; $i++) {
            $activeClass = $i == $currentPage ? 'bg-blue-600 text-white' : 'bg-white hover:bg-gray-100';
            echo '<a href="?page=' . $i . '&entries=' . $itemsPerPage . '&search=' . urlencode($searchTerm) . '&sort=' . $sortColumn . '&order=' . $sortOrder . '" ';
            echo 'class="border rounded px-3 py-1 mr-1 mb-1 ' . $activeClass . '">' . $i . '</a>';
        }
        ?>
        
        <a href="?page=<?php echo min($totalPages, $currentPage + 1); ?>&entries=<?php echo $itemsPerPage; ?>&search=<?php echo urlencode($searchTerm); ?>&sort=<?php echo $sortColumn; ?>&order=<?php echo $sortOrder; ?>" 
           class="border rounded px-3 py-1 mr-1 mb-1 bg-white hover:bg-gray-100 <?php echo $currentPage >= $totalPages ? 'opacity-50 cursor-not-allowed' : ''; ?>">
          Next
        </a>
        
        <a href="?page=<?php echo $totalPages; ?>&entries=<?php echo $itemsPerPage; ?>&search=<?php echo urlencode($searchTerm); ?>&sort=<?php echo $sortColumn; ?>&order=<?php echo $sortOrder; ?>" 
           class="border rounded px-3 py-1 mb-1 bg-white hover:bg-gray-100 <?php echo $currentPage >= $totalPages ? 'opacity-50 cursor-not-allowed' : ''; ?>">
          Last
        </a>
      </div>
    </div>
    
    <!-- Page navigation indicator -->
    <div class="flex items-center mt-4">
      <div class="w-full h-2 bg-gray-300 rounded">
        <?php
        $progressPercent = ($currentPage / max(1, $totalPages)) * 100;
        echo '<div class="h-2 bg-blue-500 rounded" style="width: ' . $progressPercent . '%"></div>';
        ?>
      </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add live search functionality
    const searchInput = document.getElementById('searchInput');
    let searchTimeout;
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                const form = searchInput.closest('form');
                form.submit();
            }, 500); // 500ms delay
        });
    }
});
</script>

<?php 
include_once '../../includes/footer.php'; 
?>