<?php
include('../config/db_connect.php');

// Fetch dashboard statistics
function getDashboardStats($pdo) {
    $stats = [];
    $stats['total_products'] = $pdo->query("SELECT COUNT(*) as total FROM products")->fetch()['total'];
    $stats['total_orders'] = $pdo->query("SELECT COUNT(*) as total FROM orders")->fetch()['total'];
    $stats['revenue'] = $pdo->query("SELECT SUM(total_price) as revenue FROM orders")->fetch()['revenue'] ?? 0;
    $stats['active_chats'] = $pdo->query("SELECT COUNT(DISTINCT  user_id ) as active_chats FROM chat_messages WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)")->fetch()['active_chats'];
    return $stats;
}

// Fetch products
function getProducts($pdo) {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
    return $stmt->fetchAll();
}

// Handle form submissions for product and "About Us"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_product':
                $stmt = $pdo->prepare("INSERT INTO products (name, category, description, price, quantity, image_url, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
                $stmt->execute([$_POST['name'], $_POST['category'], $_POST['description'], $_POST['price'], $_POST['quantity'], $_POST['image_url']]);
                break;

            case 'update_product':
                $stmt = $pdo->prepare("UPDATE products SET name=?, category=?, description=?, price=?, stock=?, image_url=?, updated_at=NOW() WHERE id=?");
                $stmt->execute([$_POST['name'], $_POST['category'], $_POST['description'], $_POST['price'], $_POST['quantity'], $_POST['image_url'], $_POST['id']]);
                break;

            case 'delete_product':
                $stmt = $pdo->prepare("DELETE FROM products WHERE id=?");
                $stmt->execute([$_POST['id']]);
                break;

            case 'update_about_us':
                $stmt = $pdo->prepare("UPDATE settings SET about_us=? WHERE id=1");
                $stmt->execute([$_POST['about_us']]);
                break;
        }
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}

$stats = getDashboardStats($pdo);
$products = getProducts($pdo);
//$aboutUs = $pdo->query("SELECT about_us FROM settings WHERE id=1")->fetch()['about_us'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="dash.css">
    <style>
        /* Your CSS from the initial dashboard goes here */
    </style>
</head>
<body>
<div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="#dashboard">Dashboard</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="#about-us">About Us</a></li>
            <li><a href="chat.php">Chat</a></li>
            <li><a href="#chat">settings</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Dashboard Overview -->
        <section id="dashboard">
            <h1>Dashboard Overview</h1>
            <div class="dashboard-cards">
                <div>Total Products: <?= $stats['total_products']; ?></div>
                <div>Total Orders: <?= $stats['total_orders']; ?></div>
                <div>Revenue: $<?= $stats['revenue']; ?></div>
                <div>Active Chats: <?= $stats['active_chats']; ?></div>
            </div>
        </section>

        <!-- Products -->
        <section id="products">
            <h2>Manage Products</h2>
            <form method="POST">
                <input type="hidden" name="action" value="add_product">
                <input type="text" name="name" placeholder="Product Name" required>
                <input type="text" name="category" placeholder="Category" required>
                <textarea name="description" placeholder="Description"></textarea>
                <input type="number" name="price" placeholder="Price" required>
                <input type="number" name="quantity" placeholder="quantity" required>
                <input type="file" name="image_url" placeholder="Image URL">
                <button type="submit">Add Product</button>
            </form>
            <table>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= $product['name']; ?></td>
                        <td><?= $product['category']; ?></td>
                        <td><?= $product['price']; ?></td>
                        <td><?= $product['quantity']; ?></td>
                        <td>
                            <!-- Buttons to handle update and delete -->
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <!-- About Us -->
        <section id="about-us">
            <h2>Manage About Us</h2>
            <form method="POST">
                <input type="hidden" name="action" value="update_about_us">
                <textarea name="about_us" rows="5"><?= $aboutUs; ?></textarea>
                <button type="submit">Update</button>
            </form>
        </section>

<script>
    // JavaScript to handle chat dynamically
</script>
</body>
</html>
