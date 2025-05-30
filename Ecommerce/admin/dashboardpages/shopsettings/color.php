
<?php 
include_once '../../includes/header.php'; 
// include_once 'db_connect.php'; // Add database connection include

// Database operations
// $conn = mysqli_connect($host, $username, $password, "santoshvastralay"); // Connect to database

// Check connection
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}



// Define variables for pagination and sorting
$itemsPerPage = isset($_GET['entries']) ? (int)$_GET['entries'] : 10;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($db, $_GET['search']) : '';
$sortColumn = isset($_GET['sort']) ? mysqli_real_escape_string($db, $_GET['sort']) : 'id';
$sortOrder = isset($_GET['order']) ? mysqli_real_escape_string($db, $_GET['order']) : 'ASC';

// Validate sort column to prevent SQL injection
$allowedColumns = ['id', 'color_name'];
if (!in_array($sortColumn, $allowedColumns)) {
    $sortColumn = 'id';
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
    $searchCondition = " WHERE LOWER(color_name) LIKE '%$searchTerm%'";
}

// Count total records for pagination
$countQuery = "SELECT COUNT(*) as total FROM tbl_color" . $searchCondition;
$countResult = mysqli_query($db, $countQuery);
$totalRecords = mysqli_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalRecords / $itemsPerPage);

// Get records for current page with sorting
$query = "SELECT * FROM tbl_color" . $searchCondition . " ORDER BY $sortColumn $sortOrder LIMIT $offset, $itemsPerPage";
$result = mysqli_query($db, $query);

// Handle color deletion if requested
if (isset($_GET['delete_id']) && !empty($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $delete_query = "DELETE FROM tbl_color WHERE id = $delete_id";
    
    if (mysqli_query($db, $delete_query)) {
        // Redirect to avoid resubmission on page refresh
        header("Location: color.php");
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
    <!-- Header with View Colors and Add New button -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
      <h1 class="text-2xl font-semibold flex items-center mb-3 sm:mb-0">
        <i class="fas fa-circle-dot mr-2"></i> View Colors
      </h1>
      <button id="addNewBtn" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700 w-full sm:w-auto">
        <a href="color-add.php" class="block text-center">Add New</a>
      </button>
    </div>
    
    <div class="border-t border-b border-gray-300 my-4"></div>
    
    <!-- Controls and search -->
    <form method="GET" action="color.php" class="flex flex-col sm:flex-row justify-between items-center mb-4">
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
               value="<?php echo htmlspecialchars($searchTerm); ?>" placeholder="Search by color name..." />
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
              <a href="<?php echo getSortUrl('id', $sortColumn, $sortOrder); ?>" class="flex items-center justify-between">
                # 
                <span class="ml-1">
                  <?php echo getSortIcon('id', $sortColumn, $sortOrder); ?>
                </span>
              </a>
            </th>
            <th class="py-2 px-4 border text-left relative">
              <a href="<?php echo getSortUrl('color_name', $sortColumn, $sortOrder); ?>" class="flex items-center justify-between">
                Color Name
                <span class="ml-1">
                  <?php echo getSortIcon('color_name', $sortColumn, $sortOrder); ?>
                </span>
              </a>
            </th>
            <th class="py-2 px-4 border text-left relative">
              Action
            </th>
          </tr>
        </thead>
        <tbody id="colorTableBody">
          <?php 
          if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                  echo '<tr class="hover:bg-gray-50">';
                  echo '<td class="py-2 px-4 border">' . $row['id'] . '</td>';
                  echo '<td class="py-2 px-4 border">' . htmlspecialchars($row['color_name']) . '</td>';
                  echo '<td class="py-2 px-4 border flex flex-col sm:flex-row">';
                  echo '<a href="color-edit.php?id=' . $row['id'] . '" class="bg-blue-500 text-white px-3 py-1 rounded mb-1 sm:mb-0 sm:mr-1 text-center hover:bg-blue-600">';
                  echo '<i class="fas fa-edit mr-1"></i> Edit</a>';
                  echo '<a href="color.php?delete_id=' . $row['id'] . '" class="bg-red-500 text-white px-3 py-1 rounded text-center hover:bg-red-600" ';
                  echo 'onclick="return confirm(\'Are you sure you want to delete this color?\')"><i class="fas fa-trash mr-1"></i> Delete</a>';
                  echo '</td>';
                  echo '</tr>';
              }
          } else {
              echo '<tr><td colspan="3" class="py-4 px-4 text-center">No colors found</td></tr>';
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
      
        
        <a href="?page=<?php echo max(1, $currentPage - 1); ?>&entries=<?php echo $itemsPerPage; ?>&search=<?php echo urlencode($searchTerm); ?>&sort=<?php echo $sortColumn; ?>&order=<?php echo $sortOrder; ?>" 
           class="border rounded px-3 py-1 mr-1 mb-1 bg-white hover:bg-gray-100 <?php echo $currentPage <= 1 ? 'opacity-50 cursor-not-allowed' : ''; ?>">
            previous
        </a>

        <a href="?page=<?php echo min($totalPages, $currentPage + 1); ?>&entries=<?php echo $itemsPerPage; ?>&search=<?php echo urlencode($searchTerm); ?>&sort=<?php echo $sortColumn; ?>&order=<?php echo $sortOrder; ?>" 
           class="border rounded px-3 py-1 mr-1 mb-1 bg-white hover:bg-gray-100 <?php echo $currentPage >= $totalPages ? 'opacity-50 cursor-not-allowed' : ''; ?>">
          <i class="fas fa-angle-left"></i>
        </a>
        
        <?php
        $startPage = max(1, min($currentPage - 1, $totalPages - 4));
        $endPage = min($startPage + 4, $totalPages);
        
        for ($i = $startPage; $i <= $endPage; $i++) {
            $activeClass = $i == $currentPage ? 'bg-blue-600 text-white' : 'bg-white hover:bg-gray-100';
            echo '<a href="?page=' . $i . '&entries=' . $itemsPerPage . '&search=' . urlencode($searchTerm) . '&sort=' . $sortColumn . '&order=' . $sortOrder . '" ';
            echo 'class="border rounded px-3 py-1 mr-1 mb-1 ' . $activeClass . '">' . $i . '</a>';
        }
        ?>
        
        <a href="?page=<?php echo min($totalPages, $currentPage + 1); ?>&entries=<?php echo $itemsPerPage; ?>&search=<?php echo urlencode($searchTerm); ?>&sort=<?php echo $sortColumn; ?>&order=<?php echo $sortOrder; ?>" 
           class="border rounded px-3 py-1 mr-1 mb-1 bg-white hover:bg-gray-100 <?php echo $currentPage >= $totalPages ? 'opacity-50 cursor-not-allowed' : ''; ?>">
          <i class="fas fa-angle-right"></i>
        </a>
        
        <a href="?page=<?php echo $totalPages; ?>&entries=<?php echo $itemsPerPage; ?>&search=<?php echo urlencode($searchTerm); ?>&sort=<?php echo $sortColumn; ?>&order=<?php echo $sortOrder; ?>" 
           class="border rounded px-3 py-1 mb-1 bg-white hover:bg-gray-100 <?php echo $currentPage >= $totalPages ? 'opacity-50 cursor-not-allowed' : ''; ?>">
            next
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
// Close the database connection
// mysqli_close($conn);
include_once '../../includes/footer.php'; 
?>