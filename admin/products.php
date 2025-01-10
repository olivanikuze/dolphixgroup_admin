<?php
// Include the database connection
include('../config/db_connect.php');

// Fetch all records from the `products` table
$query = "SELECT * FROM products";
$stmt = $pdo->query($query);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Products</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Description</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Image</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?php echo htmlspecialchars($product['id']); ?></td>
                <td><?php echo htmlspecialchars($product['name']); ?></td>
                <td><?php echo htmlspecialchars($product['category']); ?></td>
                <td><?php echo htmlspecialchars($product['description']); ?></td>
                <td><?php echo htmlspecialchars($product['price']); ?></td>
                <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                <td><img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Product Image" style="width: 50px; height: 50px;"></td>
                <td><?php echo htmlspecialchars($product['created_at']); ?></td>
                <td><?php echo htmlspecialchars($product['updated_at']); ?></td>
                <td>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#actionModal" 
                        onclick='populateModal(<?php echo json_encode($product); ?>, "view")'>View</button>
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#actionModal" 
                        onclick='populateModal(<?php echo json_encode($product); ?>, "edit")'>Edit</button>
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#actionModal" 
                        onclick='populateModal(<?php echo json_encode($product); ?>, "add")'>Add</button>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#actionModal" 
                        onclick='populateModal(<?php echo json_encode($product); ?>, "delete")'>Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modals -->
 <!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addProductForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="addProductName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="addProductName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="addProductCategory" class="form-label">Category</label>
                        <input type="text" class="form-control" id="addProductCategory" name="category" required>
                    </div>
                    <div class="mb-3">
                        <label for="addProductDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="addProductDescription" name="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="addProductPrice" class="form-label">Price</label>
                        <input type="number" class="form-control" id="addProductPrice" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="addProductStock" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="addProductStock" name="stock" required>
                    </div>
                    <div class="mb-3">
                        <label for="addProductImage" class="form-label">Image URL</label>
                        <input type="url" class="form-control" id="addProductImage" name="image_url" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Add Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="actionModal" tabindex="-1" aria-labelledby="actionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="actionModalLabel">Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="actionForm">
                    <input type="hidden" id="product_id" name="id">
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="product_name" name="name">
                    </div>
                    <div class="mb-3">
                        <label for="product_category" class="form-label">Category</label>
                        <input type="text" class="form-control" id="product_category" name="category">
                    </div>
                    <div class="mb-3">
                        <label for="product_description" class="form-label">Description</label>
                        <textarea class="form-control" id="product_description" name="description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="product_price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="product_price" name="price">
                    </div>
                    <div class="mb-3">
                        <label for="product_quantity" class="form-label">quantity</label>
                        <input type="number" class="form-control" id="product_stock" name="stock">
                    </div>
                    <div class="mb-3">
                        <label for="product_image" class="form-label">Image URL</label>
                        <input type="text" class="form-control" id="product_image" name="image_url">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="modalActionButton">Submit</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function populateModal(product, action) {
        document.getElementById('actionForm').reset();
        document.getElementById('product_id').value = product.id;
        document.getElementById('product_name').value = product.name;
        document.getElementById('product_category').value = product.category;
        document.getElementById('product_description').value = product.description;
        document.getElementById('product_price').value = product.price;
        document.getElementById('product_quantity').value = product.quantity;
        document.getElementById('product_image').value = product.image_url;

        const modalActionButton = document.getElementById('modalActionButton');
        modalActionButton.textContent = action.charAt(0).toUpperCase() + action.slice(1);
        modalActionButton.onclick = () => handleAction(action);
    }

    function handleAction(action) {
        const form = document.getElementById('actionForm');
        const formData = new FormData(form);

        if (action === "delete") {
            if (!confirm("Are you sure you want to delete this product?")) {
                return;
            }
        }

        // Handle AJAX to perform actions (e.g., edit, delete)
        console.log(`Performing ${action} with data:`, Object.fromEntries(formData));
        // Add your backend handling logic here
    }
</script>
</body>
</html>
