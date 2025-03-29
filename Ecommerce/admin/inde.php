<?php
session_start();
include_once('./includes/header.php');

// Function to count rows (simulating database queries)
function countRows($tableName) {
    // In a real scenario, this would be a database query
    $mockData = [
        'tbl_top_category' => 5,
        'tbl_mid_category' => 15,
        'tbl_end_category' => 30,
        'tbl_product' => 120
    ];
    
    return $mockData[$tableName] ?? 0;
}

$total_top_category = countRows('tbl_top_category');
$total_mid_category = countRows('tbl_mid_category');
$total_end_category = countRows('tbl_end_category');
$total_product = countRows('tbl_product');

// Initialize todos in session if not exists
if (!isset($_SESSION['todos'])) {
    $_SESSION['todos'] = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <section class="relative">
        <main class="ml-64 p-6">
            <div class="head-title flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <div class="left">
                    <h1 class="text-3xl font-semibold text-gray-700">Dashboard</h1>
                    <ul class="breadcrumb flex items-center mt-2">
                        <li><a href="#" class="text-gray-500">Dashboard</a></li>
                        <li class="mx-2 text-gray-500"><i class='bx bx-chevron-right'></i></li>
                        <li><a href="../index.php" class="text-blue-500">Home</a></li>
                    </ul>
                </div>
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
                    <i class='bx bxs-package text-4xl text-blue-500'></i>
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
                        <i class='bx bx-plus text-xl text-gray-700 cursor-pointer' id="addTodoBtn" onclick="showAddTodoModal()"></i>
                        <i class='bx bx-filter text-xl text-gray-700 cursor-pointer ml-4'></i>
                    </div>
                    <ul class="todo-list w-full" id="todoList">
                        <!-- Todo items will be loaded here -->
                        <li id="loading-indicator" class="text-center py-4 text-gray-500">Loading todos...</li>
                    </ul>
                </div>

                <!-- Add Todo Modal -->
                <div id="addTodoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
                    <div class="bg-white rounded-lg p-6 w-full max-w-md">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-700">Add New Todo</h3>
                            <button onclick="hideAddTodoModal()" class="text-gray-500 hover:text-gray-700">
                                <i class='bx bx-x text-2xl'></i>
                            </button>
                        </div>
                        <div class="mb-4">
                            <label for="todoText" class="block text-sm font-medium text-gray-700 mb-2">Task</label>
                            <textarea id="todoText" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                            <div class="flex space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="priority" value="blue" class="form-radio text-blue-500" checked>
                                    <span class="ml-2">Normal</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="priority" value="yellow" class="form-radio text-yellow-500">
                                    <span class="ml-2">Medium</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="priority" value="red" class="form-radio text-red-500">
                                    <span class="ml-2">High</span>
                                </label>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button onclick="hideAddTodoModal()" class="mr-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</button>
                            <button onclick="addNewTodo()" class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600">Add Task</button>
                        </div>
                    </div>
                </div>

                <!-- Edit Todo Modal -->
                <div id="editTodoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
                    <div class="bg-white rounded-lg p-6 w-full max-w-md">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-700">Edit Todo</h3>
                            <button onclick="hideEditTodoModal()" class="text-gray-500 hover:text-gray-700">
                                <i class='bx bx-x text-2xl'></i>
                            </button>
                        </div>
                        <div class="mb-4">
                            <label for="editTodoText" class="block text-sm font-medium text-gray-700 mb-2">Task</label>
                            <textarea id="editTodoText" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                        <input type="hidden" id="editingTodoId" value="">
                        <div class="flex justify-end">
                            <button onclick="hideEditTodoModal()" class="mr-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</button>
                            <button onclick="updateTodo()" class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </section>

    <script>
    // Load todos when page loads
    document.addEventListener('DOMContentLoaded', function() {
        loadTodos();
    });

    // Load todos 
    function loadTodos() {
        fetch('todo_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=get'
        })
        .then(response => response.json())
        .then(data => {
            const todoList = document.getElementById('todoList');
            todoList.innerHTML = ''; // Clear loading indicator
            
            if (data.success && data.todos.length > 0) {
                data.todos.forEach(todo => {
                    addTodoToList(todo.id, todo.task, todo.priority);
                });
            } else if (data.todos.length === 0) {
                todoList.innerHTML = '<li class="text-center py-4 text-gray-500">No todos found. Add a new task!</li>';
            } else {
                console.error('Error loading todos:', data.message);
                todoList.innerHTML = '<li class="text-center py-4 text-red-500">Error loading todos. Please try again.</li>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('todoList').innerHTML = 
                '<li class="text-center py-4 text-red-500">Could not connect to server. Please try again later.</li>';
        });
    }

    // Add a todo to the UI list
    function addTodoToList(id, text, priority = 'blue') {
        const todoList = document.getElementById('todoList');
        
        const li = document.createElement('li');
        li.className = `mb-4 bg-gray-100 rounded-lg p-4 flex flex-wrap items-center justify-between border-l-4 border-${priority}-500`;
        li.dataset.id = id;
        li.innerHTML = `
            <p class="flex-grow break-words mr-2">${text}</p>
            <div class="relative">
                <i class='bx bx-dots-vertical-rounded text-xl text-gray-700 cursor-pointer' onclick="toggleDropdown(this)"></i>
                <div class="dropdown-menu absolute right-0 mt-2 bg-white rounded-md shadow-lg py-1 w-32 z-10 hidden">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="editTodo(${id}, '${text}')">Edit</a>
                    <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100" onclick="deleteTodo(${id})">Delete</a>
                </div>
            </div>
        `;
        
        todoList.appendChild(li);
    }

    // Functions for todo management
    function toggleDropdown(element) {
        // Close all other dropdowns
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            if (menu !== element.nextElementSibling) {
                menu.classList.add('hidden');
            }
        });
        
        // Toggle this dropdown
        const dropdown = element.nextElementSibling;
        dropdown.classList.toggle('hidden');
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function closeDropdown(e) {
            if (!element.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
                document.removeEventListener('click', closeDropdown);
            }
        });
    }

    // Modal functions
    function showAddTodoModal() {
        document.getElementById('addTodoModal').classList.remove('hidden');
        document.getElementById('todoText').value = '';
        document.querySelector('input[name="priority"][value="blue"]').checked = true;
    }

    function hideAddTodoModal() {
        document.getElementById('addTodoModal').classList.add('hidden');
    }

    function showEditTodoModal(id, text) {
        document.getElementById('editTodoModal').classList.remove('hidden');
        document.getElementById('editTodoText').value = text;
        document.getElementById('editingTodoId').value = id;
    }

    function hideEditTodoModal() {
        document.getElementById('editTodoModal').classList.add('hidden');
    }

    // Add a new todo
    function addNewTodo() {
        const todoText = document.getElementById('todoText').value.trim();
        if (!todoText) {
            alert('Please enter a task!');
            return;
        }
        
        const priority = document.querySelector('input[name="priority"]:checked').value;
        
        // Show loading state
        const addButton = document.querySelector('#addTodoModal button:last-child');
        const originalText = addButton.innerText;
        addButton.innerText = 'Adding...';
        addButton.disabled = true;
        
        // Send to server
        const formData = new URLSearchParams();
        formData.append('action', 'add');
        formData.append('task', todoText);
        formData.append('priority', priority);
        
        fetch('todo_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                addTodoToList(data.id, data.task, data.priority);
                hideAddTodoModal();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Could not connect to server. Please try again.');
        })
        .finally(() => {
            // Reset button state
            addButton.innerText = originalText;
            addButton.disabled = false;
        });
    }

    // Edit a todo
    function editTodo(id, text) {
        showEditTodoModal(id, text);
    }

    // Update a todo
    function updateTodo() {
        const id = document.getElementById('editingTodoId').value;
        const newText = document.getElementById('editTodoText').value.trim();
        
        if (!newText) {
            alert('Please enter a task!');
            return;
        }
        
        // Show loading state
        const updateButton = document.querySelector('#editTodoModal button:last-child');
        const originalText = updateButton.innerText;
        updateButton.innerText = 'Updating...';
        updateButton.disabled = true;
        
        // Send to server
        const formData = new URLSearchParams();
        formData.append('action', 'edit');
        formData.append('id', id);
        formData.append('task', newText);
        
        fetch('todo_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update in DOM
                const li = document.querySelector(`li[data-id="${id}"]`);
                if (li) {
                    li.querySelector('p').innerText = newText;
                }
                hideEditTodoModal();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Could not connect to server. Please try again.');
        })
        .finally(() => {
            // Reset button state
            updateButton.innerText = originalText;
            updateButton.disabled = false;
        });
    }

    // Delete a todo
    function deleteTodo(id) {
        if (confirm('Are you sure you want to delete this task?')) {
            // Send to server
            const formData = new URLSearchParams();
            formData.append('action', 'delete');
            formData.append('id', id);
            
            fetch('todo_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove from DOM
                    const li = document.querySelector(`li[data-id="${id}"]`);
                    if (li) li.remove();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Could not connect to server. Please try again.');
            });
        }
    }
    </script>
</body>
</html>
<?php include_once('./includes/footer.php'); ?>