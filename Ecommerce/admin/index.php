
<?php include_once('C:/xampp/htdocs/santoshvas/Ecommerce/admin/includes/header.php');  ?>

<?php
$statement = $db->prepare("SELECT * FROM tbl_top_category");
$statement->execute();
$statement->store_result();
$total_top_category = $statement->num_rows;

$statement = $db->prepare("SELECT * FROM tbl_mid_category");
$statement->execute();
$statement->store_result();
$total_mid_category = $statement->num_rows;

$statement = $db->prepare("SELECT * FROM tbl_end_category");
$statement->execute();
$statement->store_result();
$total_end_category = $statement->num_rows;

$statement = $db->prepare("SELECT * FROM tbl_product");
$statement->execute();
$statement->store_result();
$total_product = $statement->num_rows; ?>
  
            <div class="head-title flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <div class="left">
                    <h1 class="text-3xl font-semibold text-gray-700">Dashboard</h1>
                    <ul class="breadcrumb flex items-center mt-2">
                        <li><a href="#" class="text-gray-500">Dashboard</a></li>
                        <li class="mx-2 text-gray-500"><i class='bx bx-chevron-right'></i></li>
                        <li><a href="../index.php" class="text-blue-500">Home</a></li>
                    </ul>
                </div>
                <!-- <a href="#" class="btn-download h-10 px-4 bg-blue-500 text-white rounded-full flex items-center mt-4 md:mt-0 w-max">
                    <i class='bx bxs-cloud-download mr-2'></i>
                    <span>Download PDF</span>
                </a> -->
            </div>

             <ul class="box-info grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <li class="p-6 bg-white rounded-xl flex items-center shadow-sm">
                    <i class='bx bxs-calendar-check text-4xl text-blue-500'></i>
                    <div class="ml-4">
                        <h3 class="text-2xl font-semibold text-gray-700">0</h3>
                        <p class="text-gray-500">New Orders</p>
                    </div>
                </li>

                <li class="p-6 bg-white rounded-xl flex items-center shadow-sm">
                <i class='bx bxs-package text-4xl text-blue-500' ></i>
                    <div class="ml-4">
                        <h3 class="text-2xl font-semibold text-gray-700"><?php echo $total_product; ?></h3>
                        <p class="text-gray-500">Total Products</p>
                    </div>
                </li>
                <li class="p-6 bg-white rounded-xl flex items-center shadow-sm">
                    <i class='bx bxs-group text-4xl text-yellow-500'></i>
                    <div class="ml-4">
                        <h3 class="text-2xl font-semibold text-gray-700">4</h3>
                        <p class="text-gray-500">Visitors</p>
                    </div>
                </li>
                <li class="p-6 bg-white rounded-xl flex items-center shadow-sm">
                    <i class='bx bxs-dollar-circle text-4xl text-green-500'></i>
                    <div class="ml-4">
                        <h3 class="text-2xl font-semibold text-gray-700">$2,543</h3>
                        <p class="text-gray-500">Total Sales</p>
                    </div>
                </li>
                <li class="p-6 bg-white rounded-xl flex items-center shadow-sm">
                    <i class='bx bxs-category text-4xl text-green-500'></i>
                    <div class="ml-4">
                        <h3 class="text-2xl font-semibold text-gray-700"><?php echo $total_top_category; ?></h3>
                        <p class="text-gray-500">Total Top Level Categories</p>
                    </div>
                </li>
                <li class="p-6 bg-white rounded-xl flex items-center shadow-sm">
                    <i class='bx bxs-category text-4xl text-red-500'></i>
                    <div class="ml-4">
                        <h3 class="text-2xl font-semibold text-gray-700"><?php echo $total_mid_category; ?></h3>
                        <p class="text-gray-500">Total Mid Level Categories</p>
                    </div>
                </li>

                <li class="p-6 bg-white rounded-xl flex items-center shadow-sm">
                    <i class='bx bxs-category text-4xl text-red-500'></i>
                    <div class="ml-4">
                        <h3 class="text-2xl font-semibold text-gray-700"><?php echo $total_end_category; ?></h3>
                        <p class="text-gray-500">Total End Level Categories</p>
                    </div>
                </li>
            </ul>

            <div class="table-data grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="order bg-white rounded-xl p-6 shadow-sm">
                    <div class="head flex items-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-700 mr-auto">Recent Orders</h3>
                        <i class='bx bx-search text-xl text-gray-700 cursor-pointer'></i>
                        <i class='bx bx-filter text-xl text-gray-700 cursor-pointer ml-4'></i>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="pb-3 text-left text-gray-500">User</th>
                                    <th class="pb-3 text-left text-gray-500">Date Order</th>
                                    <th class="pb-3 text-left text-gray-500">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="hover:bg-gray-100">
                                    <td class="py-3 flex items-center">
                                        <img src="https://via.placeholder.com/36" class="w-9 h-9 rounded-full object-cover">
                                        <p class="ml-2">Aditya</p>
                                    </td>
                                    <td class="py-3">01-02-2025</td>
                                    <td class="py-3">
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Completed</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-100">
                                    <td class="py-3 flex items-center">
                                        <img src="https://via.placeholder.com/36" class="w-9 h-9 rounded-full object-cover">
                                        <p class="ml-2">Aman</p>
                                    </td>
                                    <td class="py-3">09-02-2025</td>
                                    <td class="py-3">
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">Pending</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-100">
                                    <td class="py-3 flex items-center">
                                        <img src="https://via.placeholder.com/36" class="w-9 h-9 rounded-full object-cover">
                                        <p class="ml-2">Rohan</p>
                                    </td>
                                    <td class="py-3">11-02-2025</td>
                                    <td class="py-3">
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">Processing</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="todo bg-white rounded-xl p-6 shadow-sm">
                    <div class="head flex items-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-700 mr-auto">Todos</h3>
                        <i class='bx bx-plus text-xl text-gray-700 cursor-pointer'></i>
                        <i class='bx bx-filter text-xl text-gray-700 cursor-pointer ml-4'></i>
                    </div>
                    <ul class="todo-list">
                        <li class="mb-4 bg-gray-100 rounded-lg p-4 flex items-center justify-between border-l-4 border-blue-500">
                            <p>Update product pricing</p>
                            <i class='bx bx-dots-vertical-rounded text-xl text-gray-700 cursor-pointer'></i>
                        </li>
                        <li class="mb-4 bg-gray-100 rounded-lg p-4 flex items-center justify-between border-l-4 border-yellow-500">
                            <p>Respond to customer inquiries</p>
                            <i class='bx bx-dots-vertical-rounded text-xl text-gray-700 cursor-pointer'></i>
                        </li>
                        <li class="mb-4 bg-gray-100 rounded-lg p-4 flex items-center justify-between border-l-4 border-green-500">
                            <p>Prepare monthly sales report</p>
                            <i class='bx bx-dots-vertical-rounded text-xl text-gray-700 cursor-pointer'></i>
                        </li>
                        <li class="mb-4 bg-gray-100 rounded-lg p-4 flex items-center justify-between border-l-4 border-red-500">
                            <p>Review inventory levels</p>
                            <i class='bx bx-dots-vertical-rounded text-xl text-gray-700 cursor-pointer'></i>
                        </li>
                    </ul>
                </div>
            </div>
        </main>
    
    </section>


    <?php include_once('C:/xampp/htdocs/santoshvas/Ecommerce/admin/includes/footer.php'); ?>
  