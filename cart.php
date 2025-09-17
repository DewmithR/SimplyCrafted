<?php 
include 'includes/header.php'; 
include 'config/db.php'; 

if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-warning text-center'>Please <a href='login.php'>login</a> to view your cart.</div>";
    include 'includes/footer.php'; 
    exit;
}

$user_id = $_SESSION['user_id'];

// remove item
if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    $conn->query("DELETE FROM cart WHERE id=$remove_id AND user_id=$user_id");
    header("Location: cart.php");
    exit;
}

// Get cart items
$sql = "SELECT cart.id AS cart_id, products.name, products.price, products.image, cart.quantity 
        FROM cart 
        JOIN products ON cart.product_id = products.id 
        WHERE cart.user_id = $user_id";
$result = $conn->query($sql);
?>

<h2 class="text-center mb-4">Your Cart</h2>

<form method="POST" action="checkout.php">
  <div class="table-responsive">
    <table class="table table-bordered align-middle text-center">
      <thead class="table-dark">
        <tr>
          <th>Product</th>
          <th>Image</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Subtotal</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="cartTable">
        <?php 
        $total = 0;
        if ($result->num_rows > 0):
          while($row = $result->fetch_assoc()):
            $subtotal = $row['price'] * $row['quantity'];
            $total += $subtotal;
        ?>
        <tr data-price="<?php echo $row['price']; ?>">
          <td><?php echo $row['name']; ?></td>
          <td><img src="assets/images/<?php echo $row['image']; ?>" width="70" class="rounded"></td>
          <td>Rs.<?php echo number_format($row['price'],2); ?></td>
          <td>
            <input type="number" class="form-control qty-input" 
                   value="<?php echo $row['quantity']; ?>" min="1"
                   data-cartid="<?php echo $row['cart_id']; ?>" style="width:80px;">
          </td>
          <td class="subtotal">Rs.<?php echo number_format($subtotal,2); ?></td>
          <td><a href="cart.php?remove=<?php echo $row['cart_id']; ?>" class="btn btn-sm btn-danger">Remove</a></td>
        </tr>
        <?php endwhile; else: ?>
          <tr><td colspan="6">Your cart is empty.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <h4 class="text-end mt-3">Total: <span id="cartTotal">Rs.<?php echo number_format($total,2); ?></span></h4>
  <?php if ($result->num_rows > 0): ?>
    <div class="text-end">
      <button type="submit" class="btn btn-success">Proceed to Checkout</button>
    </div>
  <?php endif; ?>
</form>

<?php include 'includes/footer.php'; ?>
