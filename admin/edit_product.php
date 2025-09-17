<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../config/db.php';
include 'includes/admin_header.php';

if (!isset($_GET['id'])) { echo "No product selected."; exit; }
$id = intval($_GET['id']);
$res = $conn->query("SELECT * FROM products WHERE id=$id");
$product = $res->fetch_assoc();

if (isset($_POST['update_product'])) {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);

    $image = $product['image'];
    if ($_FILES['image']['name']) {
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/" . $image);
    }

    $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, stock=?, image=? WHERE id=?");
    $stmt->bind_param("ssdisi", $name, $desc, $price, $stock, $image, $id);
    $stmt->execute();
    header("Location: products.php");
    exit;
}
?>

<div class="container mt-4">
<h2>Edit Product</h2>

<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label class="form-label">Product Name</label>
        <input type="text" name="name" class="form-control" value="<?php echo $product['name']; ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3" required><?php echo $product['description']; ?></textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Price</label>
        <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $product['price']; ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Stock</label>
        <input type="number" name="stock" class="form-control" value="<?php echo $product['stock']; ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Image</label>
        <input type="file" name="image" class="form-control">
        <img src="../assets/images/<?php echo $product['image']; ?>" width="100" class="mt-2 rounded">
    </div>
    <button type="submit" name="update_product" class="btn btn-dark">Update Product</button>
</form>
</div>

<?php include 'includes/admin_footer.php'; ?>