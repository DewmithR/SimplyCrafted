<?php include 'includes/header.php'; ?>
<?php include 'config/db.php'; ?>

<h2 class="text-center mb-4">Our Products</h2>

<div class="row g-4">
<?php
$sql = "SELECT * FROM products ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0):
    while($row = $result->fetch_assoc()):
?>
  <div class="col-md-3">
    <div class="card h-100 shadow-sm">
      <img src="assets/images/<?php echo $row['image']; ?>" 
           class="card-img-top" 
           alt="<?php echo $row['name']; ?>">
      <div class="card-body text-center">
        <h5 class="card-title"><?php echo $row['name']; ?></h5>
        <p class="card-text text-muted">Rs.<?php echo number_format($row['price'], 2); ?></p>
        <a href="product.php?id=<?php echo $row['id']; ?>" class="btn btn-dark btn-sm">View Details</a>
      </div>
    </div>
  </div>
<?php
    endwhile;
else:
    echo "<p class='text-center'>No products available right now.</p>";
endif;
?>
</div>

<?php include 'includes/footer.php'; ?>