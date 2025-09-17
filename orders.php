<?php 
include 'includes/header.php'; 
include 'config/db.php'; 

if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-warning text-center'>Please <a href='login.php'>login</a> to view your orders.</div>";
    include 'includes/footer.php';
    exit;
}

$user_id = $_SESSION['user_id'];

// get orders
$sql = "SELECT * FROM orders WHERE user_id=$user_id ORDER BY order_date DESC";
$orders = $conn->query($sql);
?>

<h2 class="text-center mb-4">My Orders</h2>

<?php if ($orders->num_rows > 0): ?>
  <div class="accordion" id="ordersAccordion">
    <?php while($order = $orders->fetch_assoc()): ?>
      <div class="accordion-item mb-2">
        <h2 class="accordion-header" id="heading<?php echo $order['id']; ?>">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $order['id']; ?>">
            Order #<?php echo $order['id']; ?> | Status: 
            <span class="badge bg-<?php 
                switch($order['status']) {
                    case 'completed': echo 'success'; break;
                    case 'processing': echo 'info'; break;
                    case 'shipped': echo 'primary'; break;
                    case 'cancelled': echo 'danger'; break;
                    default: echo 'secondary'; 
                }
            ?>">
              <?php echo ucfirst($order['status']); ?>
            </span>
          </button>
        </h2>
        <div id="collapse<?php echo $order['id']; ?>" class="accordion-collapse collapse" data-bs-parent="#ordersAccordion">
          <div class="accordion-body">
            <p><strong>Date:</strong> <?php echo $order['order_date']; ?></p>
            <p><strong>Name:</strong> <?php echo $order['full_name']; ?></p>
            <p><strong>Address:</strong> <?php echo $order['address']; ?></p>
            <p><strong>Phone:</strong> <?php echo $order['phone']; ?></p>
            
            <h6>Items:</h6>
            <ul class="list-group mb-2">
              <?php 
              $order_id = $order['id'];
              $items = $conn->query("SELECT order_items.*, products.name 
                                     FROM order_items 
                                     JOIN products ON order_items.product_id = products.id 
                                     WHERE order_items.order_id=$order_id");
              while($item = $items->fetch_assoc()): ?>
                <li class="list-group-item d-flex justify-content-between">
                  <span><?php echo $item['name']; ?> (x<?php echo $item['quantity']; ?>)</span>
                  <span>Rs.<?php echo number_format($item['price'] * $item['quantity'],2); ?></span>
                </li>
              <?php endwhile; ?>
            </ul>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
<?php else: ?>
  <p class="text-center">You have no orders yet. <a href="shop.php">Shop now</a>.</p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
