<?php
include('..config/db_connect.php');

// Fetch dashboard statistics
function getDashboardStats($pdo) {
    $stats = [];
    
    // Get total products
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM products");
    $stats['total_products'] = $stmt->fetch()['total'];
    
    // Get total orders
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM orders");
    $stats['total_orders'] = $stmt->fetch()['total'];
    
    // Get total revenue
    $stmt = $pdo->query("SELECT SUM(total_price) as revenue FROM orders");
    $stats['revenue'] = $stmt->fetch()['revenue'] ?? 0;
    
    // Get active chats (messages from last 24 hours)
    $stmt = $pdo->query("SELECT COUNT(DISTINCT sender_id) as active_chats FROM chat_messages WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)");
    $stats['active_chats'] = $stmt->fetch()['active_chats'];
    
    return $stats;
}

// Fetch products
function getProducts($pdo) {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
    return $stmt->fetchAll();
}

// Handle product operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_product':
                $stmt = $pdo->prepare("INSERT INTO products (name, category, description, price, stock, image_url, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$_POST['name'], $_POST['category'], $_POST['description'], $_POST['price'], $_POST['stock'], $_POST['image_url'], $_POST['created_at'], $_POST['updated_at']]);
                break;
                
            case 'update_product':
                $stmt = $pdo->prepare("UPDATE products SET name=?, category=?, description=?, price=?, stock=?, image_url=?, created_at=?, updated_at=?  WHERE id=?");
                $stmt->execute([$_POST['name'], $_POST['category'], $_POST['description'], $_POST['price'], $_POST['stock'], $_POST['created_at'], $_POST['updated_at'], $_POST['id']]);
                break;
                
            case 'delete_product':
                $stmt = $pdo->prepare("DELETE FROM products WHERE id=?");
                $stmt->execute([$_POST['id']]);
                break;
        }
        
        // Redirect to avoid form resubmission
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Keep your existing CSS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <!-- Keep your existing sidebar HTML -->
        
        <div class="main-content">
            <div class="header">
                <h1>Dashboard Overview</h1>
                <div class="user-info">
                    <a href="logout.php" class="logout-btn">Logout</a>
                </div>
            </div>

            <div class="dashboard-cards">
                <div class="card">
                    <h3>Total Products</h3>
                    <p><?php echo $stats['total_products']; ?></p>
                </div>
                <div class="card">
                    <h3>Active Chats</h3>
                    <p><?php echo $stats['active_chats']; ?></p>
                </div>
                <div class="card">
                    <h3>Total Orders</h3>
                    <p><?php echo $stats['total_orders']; ?></p>
                </div>
                <div class="card">
                    <h3>Revenue</h3>
                    <p>$<?php echo number_format($stats['revenue'], 2); ?></p>
                </div>
            </div>

            <!-- Product Management Section -->
            <div class="card">
                <h2>Product Management</h2>
                <button onclick="showAddProductModal()" class="action-btn edit-btn">Add New Product</button>
                <table class="content-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($product['category']); ?></td>
                            <td>$<?php echo number_format($product['price'], 2); ?></td>
                            <td><?php echo $product['stock']; ?></td>
                            <td>
                                <button onclick="showEditProductModal(<?php echo htmlspecialchars(json_encode($product)); ?>)" class="action-btn edit-btn">Edit</button>
                                <button onclick="deleteProduct(<?php echo $product['id']; ?>)" class="action-btn delete-btn">Delete</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Add your chat section here -->
        </div>
    </div>

    <!-- Product Modal -->
    <div id="productModal" class="modal" style="display: none;">
        <!-- Add modal content here -->
    </div>

    <script>
        // Add your JavaScript functions here for handling modals and AJAX operations
        function showAddProductModal() {
            // Implementation
        }

        function showEditProductModal(product) {
            // Implementation
        }

        function deleteProduct(id) {
            if (confirm('Are you sure you want to delete this product?')) {
                $.post('index.php', {
                    action: 'delete_product',
                    id: id
                }).done(function() {
                    location.reload();
                });
            }
        }
    </script>
</body>
</html>