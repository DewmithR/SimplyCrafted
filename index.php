<?php include 'includes/header.php'; ?>
<?php include 'config/db.php'; ?>

<div id="heroCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
  <div class="carousel-inner rounded-3 shadow">
    <div class="carousel-item active">
      <img src="assets/images/banner1.jpg" class="d-block w-100" alt="Crafted Goods">
      <div class="carousel-caption d-none d-md-block">
        <h2>Welcome to Simply Crafted</h2>
        <p>Handmade & Homemade with Love</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="assets/images/banner2.jpg" class="d-block w-100" alt="Handmade">
      <div class="carousel-caption d-none d-md-block">
        <h2>Unique & Authentic</h2>
        <p>Every piece tells a story</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="assets/images/banner3.jpg" class="d-block w-100" alt="Home Decor">
      <div class="carousel-caption d-none d-md-block">
        <h2>Shop Handmade Treasures</h2>
        <p>Crafted by passionate artisans</p>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>

<h2 class="text-center mb-4">Featured Products</h2>
<div class="row g-4">
<?php
$sql = "SELECT * FROM products LIMIT 8";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()):
?>
  <div class="col-md-3">
    <div class="card h-100 shadow-sm">
      <img src="assets/images/<?php echo $row['image']; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>">
      <div class="card-body text-center">
        <h5 class="card-title"><?php echo $row['name']; ?></h5>
        <p class="card-text">Rs.<?php echo number_format($row['price'], 2); ?></p>
        <a href="product.php?id=<?php echo $row['id']; ?>" class="btn btn-dark">View Details</a>
      </div>
    </div>
  </div>
<?php endwhile; ?>
</div>

<?php include 'includes/footer.php'; ?>
