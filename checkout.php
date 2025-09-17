<?php 
include 'includes/header.php'; 
include 'config/db.php'; 

if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-warning text-center'>Please <a href='login.php'>login</a> to checkout.</div>";
    include 'includes/footer.php';
    exit;
}

$user_id = $_SESSION['user_id'];

// Get cart items
$sql = "SELECT cart.id AS cart_id, products.id AS product_id, products.name, products.price, cart.quantity 
        FROM cart 
        JOIN products ON cart.product_id = products.id 
        WHERE cart.user_id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "<div class='alert alert-info text-center'>Your cart is empty. <a href='shop.php'>Shop now</a>.</div>";
    include 'includes/footer.php';
    exit;
}

// order submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['place_order'])) {
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $address   = $conn->real_escape_string($_POST['address']);
    $phone     = $conn->real_escape_string($_POST['phone']);

    // Insert into orders
    $stmt = $conn->prepare("INSERT INTO orders (user_id, status, full_name, address, phone) VALUES (?, 'pending', ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $full_name, $address, $phone);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Insert order items
    $result->data_seek(0);
    while ($row = $result->fetch_assoc()) {
        $product_id = $row['product_id'];
        $quantity = $row['quantity'];
        $price = $row['price'];
        $stmt2 = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt2->bind_param("iiid", $order_id, $product_id, $quantity, $price);
        $stmt2->execute();

        // reduce stock
        $conn->query("UPDATE products SET stock = stock - $quantity WHERE id = $product_id");
    }

    // Clear the cart
    $conn->query("DELETE FROM cart WHERE user_id = $user_id");

    echo "<div class='alert alert-success text-center'>Thank you, your order has been placed successfully!</div>";
    include 'includes/footer.php';
    exit;
}
?>

<h2 class="text-center mb-2">Checkout</h2>
<p class="text-center mb-3">Please note that all the payments are cash or card on delivery only.</p>

<div class="row">
  <div class="col-md-6">
    <form method="POST">
      <div class="mb-3">
        <label for="full_name" class="form-label">Full Name</label>
        <input type="text" class="form-control" name="full_name" id="full_name" required>
      </div>
      <div class="mb-3">
        <label for="address" class="form-label">Shipping Address</label>
        <textarea class="form-control" name="address" id="address" rows="3" required></textarea>
      </div>
      <div class="mb-3">
        <label for="phone" class="form-label">Phone Number</label>
        <input type="text" class="form-control" name="phone" id="phone" required>
      </div>
      <button type="submit" name="place_order" class="btn btn-success w-100">Place Order</button>
    </form>
  </div>

  <div class="col-md-6">
    <h4>Order Summary</h4>
    <ul class="list-group mb-3">
      <?php 
      $total = 0;
      while ($row = $result->fetch_assoc()):
        $subtotal = $row['price'] * $row['quantity'];
        $total += $subtotal;
      ?>
      <li class="list-group-item d-flex justify-content-between">
        <span><?php echo $row['name']; ?> (x<?php echo $row['quantity']; ?>)</span>
        <span>Rs.<?php echo number_format($subtotal,2); ?></span>
      </li>
      <?php endwhile; ?>
      <li class="list-group-item d-flex justify-content-between fw-bold">
        <span>Total</span>
        <span>Rs.<?php echo number_format($total,2); ?></span>
      </li>
    </ul>
  </div>
</div>

<?php include 'includes/footer.php'; ?>