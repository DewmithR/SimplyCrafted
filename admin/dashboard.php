<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../config/db.php';
?>

<?php include 'includes/admin_header.php'; ?>

<div class="container mt-4">
    <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Products</h5>
                    <?php
                    $res = $conn->query("SELECT COUNT(*) as total FROM products");
                    $count = $res->fetch_assoc()['total'];
                    ?>
                    <p class="card-text"><?php echo $count; ?> Products</p>
                    <a href="products.php" class="btn btn-dark btn-sm">Manage Products</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Orders</h5>
                    <?php
                    $res = $conn->query("SELECT COUNT(*) as total FROM orders");
                    $count = $res->fetch_assoc()['total'];
                    ?>
                    <p class="card-text"><?php echo $count; ?> Orders</p>
                    <a href="orders.php" class="btn btn-dark btn-sm">Manage Orders</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Users</h5>
                    <?php
                    $res = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='customer'");
                    $count = $res->fetch_assoc()['total'];
                    ?>
                    <p class="card-text"><?php echo $count; ?> Customers</p>
                    <a href="users.php" class="btn btn-dark btn-sm">Manage Users</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
