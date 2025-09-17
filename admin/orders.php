<?php
session_start();

// Admin access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../config/db.php';
include 'includes/admin_header.php';

if (isset($_POST['update_order'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();
    header("Location: orders.php");
    exit;
}
?>

<div class="container mt-4">
<h2>Manage Orders</h2>

<table class="table table-bordered align-middle text-center">
    <thead class="table-dark">
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Order Date</th>
            <th>Status</th>
            <th>Items</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $orders = $conn->query("SELECT orders.*, users.username, users.email FROM orders JOIN users ON orders.user_id=users.id ORDER BY orders.id DESC");
    while($order = $orders->fetch_assoc()):

        $items_res = $conn->query("SELECT products.name, order_items.quantity, order_items.price 
                                   FROM order_items 
                                   JOIN products ON order_items.product_id=products.id 
                                   WHERE order_items.order_id=".$order['id']);
        $items = [];
        while($i = $items_res->fetch_assoc()){
            $items[] = $i['name']." (x".$i['quantity'].")";
        }
    ?>
        <tr>
            <td><?php echo $order['id']; ?></td>
            <td><?php echo $order['username']; ?><br><small><?php echo $order['email']; ?></small></td>
            <td><?php echo $order['order_date']; ?></td>
            <td>
                <form method="POST" class="d-flex justify-content-center">
                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                    <select name="status" class="form-select form-select-sm me-2">
                        <?php 
                        $statuses = ['pending','processing','shipped','completed','cancelled'];
                        foreach($statuses as $s){
                            $sel = ($s==$order['status']) ? 'selected' : '';
                            echo "<option value='$s' $sel>$s</option>";
                        }
                        ?>
                    </select>
                    <button type="submit" name="update_order" class="btn btn-sm btn-dark">Update</button>
                </form>
            </td>
            <td><?php echo implode(", ", $items); ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
</div>

<?php include 'includes/admin_footer.php'; ?>