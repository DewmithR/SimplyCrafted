<?php
session_start();
include '../config/db.php';
include 'includes/admin_header.php';
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);

    //image upload
    $image = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $target_dir = "../assets/images/";
    move_uploaded_file($tmp_name, $target_dir . $image);

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdis", $name, $desc, $price, $stock, $image);
    $stmt->execute();
    header("Location: products.php");
    exit;
}


// Delete Product
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM products WHERE id=$id");
    header("Location: products.php");
    exit;
}
?>

<div class="container mt-4">
<h2>Manage Products</h2>

<!-- Add Product -->
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <h5 class="card-title">Add New Product</h5>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="number" step="0.01" name="price" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Stock</label>
                <input type="number" name="stock" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Image</label>
                <input type="file" name="image" class="form-control" required>
            </div>
            <button type="submit" name="add_product" class="btn btn-dark">Add Product</button>
        </form>
    </div>
</div>

<!-- Products -->
<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="card-title">Existing Products</h5>
        <table class="table table-bordered align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $res = $conn->query("SELECT * FROM products ORDER BY id DESC");
            while ($row = $res->fetch_assoc()):
            ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><img src="../assets/images/<?php echo $row['image']; ?>" width="70" class="rounded"></td>
                    <td><?php echo $row['name']; ?></td>
                    <td>Rs.<?php echo number_format($row['price'],2); ?></td>
                    <td><?php echo $row['stock']; ?></td>
                    <td>
                        <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="products.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</div>
<?php include 'includes/admin_footer.php'; ?>
