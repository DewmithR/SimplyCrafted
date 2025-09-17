<?php 
include 'includes/header.php'; 
include 'config/db.php'; 

if (!isset($_GET['id'])) {
    echo "<p class='text-center'>No product selected.</p>";
    include 'includes/footer.php'; 
    exit;
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM products WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "<p class='text-center'>Product not found.</p>";
    include 'includes/footer.php'; 
    exit;
}

$product = $result->fetch_assoc();

// Add to Cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['user_id'])) {
        echo "<div class='alert alert-warning text-center'>Please <a href='login.php'>login</a> to add to cart.</div>";
    } else {
        $user_id = $_SESSION['user_id'];
        $quantity = intval($_POST['quantity']);

        // Check if product already in cart
        $check = $conn->query("SELECT * FROM cart WHERE user_id=$user_id AND product_id=$id");
        if ($check->num_rows > 0) {
            $conn->query("UPDATE cart SET quantity = quantity + $quantity WHERE user_id=$user_id AND product_id=$id");
        } else {
            $conn->query("INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $id, $quantity)");
        }

        echo "<div class='alert alert-success text-center'>Added to cart successfully!</div>";
    }
}
?>

<div class="row">
  <div class="col-md-6">
    <img src="assets/images/<?php echo $product['image']; ?>" class="img-fluid rounded shadow" alt="<?php echo $product['name']; ?>">
  </div>
  <div class="col-md-6">
    <h2><?php echo $product['name']; ?></h2>
    <p class="text-muted">Rs.<?php echo number_format($product['price'], 2); ?></p>
    <p><?php echo $product['description']; ?></p>
    <p><strong>Stock:</strong> <?php echo $product['stock']; ?></p>

    <form method="POST">
      <div class="mb-3">
        <label for="quantity" class="form-label">Quantity</label>
        <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" class="form-control" style="width:120px;">
      </div>
      <button type="submit" name="add_to_cart" class="btn btn-dark">Add to Cart</button>
    </form>
  </div>
</div>

<?php include 'includes/footer.php'; ?>