<?php
include("includes/db.php");

// Get car slug from URL
$slug = isset($_GET['car']) ? $_GET['car'] : '';

$stmt = $conn->prepare("SELECT * FROM cars WHERE slug = ?");
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  echo "❌ Car not found.";
  exit();
}

$car = $result->fetch_assoc();

// If not showroom only, prepare listings with dynamic price filter and pagination
$listings = [];
$totalPages = 1;
$page = 1;
$priceRange = isset($_GET['price_range']) ? $_GET['price_range'] : '0-20000000';

if ($car['status'] !== 'Showroom Only') {
  // Determine min and max price
  if ($priceRange === '150000-+150k') {
    $min = 150000;
    $max = 999999999;
  } else {
    [$min, $max] = explode('-', $priceRange);
    $min = (float)$min;
    $max = (float)$max;
  }

  // Pagination
  $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
  $perPage = 1;
  $offset = ($page - 1) * $perPage;

  // Count total listings
  $countStmt = $conn->prepare("SELECT COUNT(*) AS total FROM car_listings WHERE car_id = ? AND price BETWEEN ? AND ?");
  $countStmt->bind_param("idd", $car['id'], $min, $max);
  $countStmt->execute();
  $totalRows = $countStmt->get_result()->fetch_assoc()['total'];
  $countStmt->close();
  $totalPages = ceil($totalRows / $perPage);

  // Fetch listings
  $stmt2 = $conn->prepare("
    SELECT * FROM car_listings 
    WHERE car_id = ? AND price BETWEEN ? AND ?
    LIMIT ?, ?
  ");
  $stmt2->bind_param("iddii", $car['id'], $min, $max, $offset, $perPage);
  $stmt2->execute();
  $listings = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt2->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?= htmlspecialchars($car['name']) ?> (<?= $car['year'] ?>) | RetroRevive</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
  body { font-family: 'Segoe UI', sans-serif; background-color: #f8f9fa; }
  .car-img { width: 100%; border-radius: 10px; max-height: 400px; object-fit: cover; }
  .info-label { font-weight: bold; }
  .condition-bar { height: 20px; border-radius: 20px; background: #e9ecef; }
  .condition-fill { background:rgb(0, 0, 0); height: 100%; border-radius: 20px; }
</style>
</head>
<body>

<?php include("includes/header.php"); ?>

<div class="container mt-5 pt-5">
  <div class="row g-5">
    <!-- Car Details -->
    <div class="col-md-6">
      <img src="Images/<?= htmlspecialchars($car['image']) ?>" class="car-img mb-4" alt="<?= htmlspecialchars($car['name']) ?>">
      <h2><?= htmlspecialchars($car['name']) ?> (<?= $car['year'] ?>)</h2>
      <p><?= htmlspecialchars($car['description']) ?></p>
      <p><span class="info-label">Brand:</span> <?= htmlspecialchars($car['brand']) ?></p>
      <p><span class="info-label">Status:</span> <span class="badge bg-success"><?= htmlspecialchars($car['status']) ?></span></p>
      <p><span class="info-label">Price Estimate:</span> <?= htmlspecialchars($car['price']) ?></p>
    </div>

    <!-- Booking form if showroom only -->
    <?php if ($car['status'] === 'Showroom Only'): ?>
      <div class="col-md-6">
        <h4>Book This Car for Showroom Display</h4>
        <form action="showroom/handle-rental.php" method="POST" class="border rounded p-3 bg-white shadow-sm">
          <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
          <div class="mb-3">
            <label class="form-label">Your Name</label>
            <input type="text" name="customer_name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Your Email</label>
            <input type="email" name="customer_email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">End Date</label>
            <input type="date" name="end_date" class="form-control" required>
          </div>
          <p class="text-muted"><strong>Rate:</strong> $<?= $car['daily_rate'] ?>/day</p>
          <input type="hidden" name="daily_rate" value="<?= $car['daily_rate'] ?>">
          <div class="text-end">
            <button type="submit" class="btn btn-success">Book Now</button>
          </div>
        </form>
      </div>
    <?php else: ?>
      <!-- Listings with price range filter and pagination -->
      <div class="col-md-6">
<h4 class="p-3 mb-4 text-center fw-bold text-white rounded" style="background: linear-gradient(45deg,rgb(0, 0, 0),rgb(111, 112, 113));">
  Below are the listings available for this car. Contact sellers for more details!
</h4>


    

        <?php if (count($listings) > 0): ?>
          <?php foreach ($listings as $listing): ?>
            <div class="border rounded p-3 mb-4 bg-white shadow-sm">
              <p><span class="info-label">Price:</span> <?= htmlspecialchars($listing['price']) ?></p>
              <p><span class="info-label">Location:</span> <?= htmlspecialchars($listing['location']) ?></p>
              <p><span class="info-label">Transmission:</span> <?= htmlspecialchars($listing['transmission']) ?></p>
              <p><span class="info-label">Mileage:</span> <?= htmlspecialchars($listing['mileage']) ?></p>
              <p><span class="info-label">Exterior / Interior:</span> <?= htmlspecialchars($listing['exterior_color']) ?> / <?= htmlspecialchars($listing['interior_color']) ?></p>
              <p><span class="info-label">Condition:</span></p>
              <div class="condition-bar mb-2">
                <div class="condition-fill" style="width: <?= htmlspecialchars($listing['conditions']) ?>%;"></div>
              </div>
              <p><span class="info-label">Restoration:</span> <?= htmlspecialchars($listing['restoration_status']) ?></p>
              <p><span class="info-label">Engine:</span> <?= htmlspecialchars($listing['engine_condition']) ?></p>
              <p>
                <span class="info-label">AC:</span> <?= $listing['air_conditioning'] ? '✅' : '❌' ?>,
                <span class="info-label">Steering:</span> <?= $listing['power_steering'] ? '✅' : '❌' ?>,
                <span class="info-label">Brakes:</span> <?= $listing['power_brakes'] ? '✅' : '❌' ?>
              </p>
              <a href="mailto:<?= htmlspecialchars($listing['contact_email']) ?>?subject=Interest in <?= htmlspecialchars($car['name']) ?>" class="btn btn-outline-primary btn-sm">Contact Seller</a>
            </div>
          <?php endforeach; ?>
          <div class="d-flex justify-content-between">
            <a href="?car=<?= urlencode($slug) ?>&price_range=<?= urlencode($priceRange) ?>&page=<?= max($page - 1, 1) ?>" class="btn btn-dark btn-sm <?= $page <= 1 ? 'disabled' : '' ?>">&#8592; Prev</a>
            <a href="?car=<?= urlencode($slug) ?>&price_range=<?= urlencode($priceRange) ?>&page=<?= min($page + 1, $totalPages) ?>" class="btn btn-dark btn-sm <?= $page >= $totalPages ? 'disabled' : '' ?>">Next &#8594;</a>
          </div>
          <p class="text-center text-muted mt-2">Seller <?= $page ?> of <?= $totalPages ?></p>
        <?php else: ?>
          <p class="text-muted">No listings available for this price range.</p>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include("includes/footer.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
