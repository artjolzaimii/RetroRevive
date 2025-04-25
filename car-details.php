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

// Handle price filter safely (even if min = 0)
$min = (isset($_GET['min']) && is_numeric($_GET['min'])) ? (float)$_GET['min'] : 0;
$max = (isset($_GET['max']) && is_numeric($_GET['max'])) ? (float)$_GET['max'] : 999999999;

// Fetch listings for this car filtered by price
$stmt2 = $conn->prepare("
  SELECT * FROM car_listings 
  WHERE car_id = ? AND price BETWEEN ? AND ?
");
$stmt2->bind_param("idd", $car['id'], $min, $max);
$stmt2->execute();
$listings = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $car['name'] ?> (<?= $car['year'] ?>) | RetroRevive</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body { font-family: 'Segoe UI', sans-serif; background-color: #f8f9fa; }
    .car-img { width: 100%; border-radius: 10px; max-height: 400px; object-fit: cover; }
    .info-label { font-weight: bold; }
    .condition-bar { height: 20px; border-radius: 20px; background: #e9ecef; }
    .condition-fill { background: #198754; height: 100%; border-radius: 20px; }
  </style>
</head>
<body>

<?php include("includes/header.php"); ?>

<div class="container mt-5 pt-5">
  <div class="row g-5">
    <!-- Car Details -->
    <div class="col-md-6">
      <img src="Images/<?= $car['image'] ?>" class="car-img mb-4" alt="<?= $car['name'] ?>">
      <h2><?= $car['name'] ?> (<?= $car['year'] ?>)</h2>
      <p><?= $car['description'] ?></p>
      <p><span class="info-label">Brand:</span> <?= $car['brand'] ?></p>
      <p><span class="info-label">Status:</span> <span class="badge bg-success"><?= $car['status'] ?></span></p>
      <p><span class="info-label">Price Estimate:</span> <?= $car['price'] ?></p>
    </div>

    <!-- Listings -->
    <div class="col-md-6">
      <h4>Available Listings</h4>

      <!-- Price Filter -->
      <form method="GET" class="row g-2 mb-3">
        <input type="hidden" name="car" value="<?= htmlspecialchars($slug) ?>">
        <div class="col-md-5">
          <input type="number" name="min" class="form-control" placeholder="Min Price" value="<?= htmlspecialchars($min) ?>">
        </div>
        <div class="col-md-5">
          <input type="number" name="max" class="form-control" placeholder="Max Price" value="<?= $max === 999999999 ? '' : htmlspecialchars($max) ?>">
        </div>
        <div class="col-md-2">
          <button class="btn btn-dark w-100" type="submit">Filter</button>
        </div>
      </form>

      <?php if (count($listings) > 0): ?>
        <?php foreach ($listings as $listing): ?>
          <div class="border rounded p-3 mb-4 bg-white shadow-sm">
            <p><span class="info-label">Price:</span> <?= $listing['price'] ?></p>
            <p><span class="info-label">Location:</span> <?= $listing['location'] ?></p>
            <p><span class="info-label">Transmission:</span> <?= $listing['transmission'] ?></p>
            <p><span class="info-label">Mileage:</span> <?= $listing['mileage'] ?></p>
            <p><span class="info-label">Exterior / Interior:</span> <?= $listing['exterior_color'] ?> / <?= $listing['interior_color'] ?></p>
            <p><span class="info-label">Condition:</span></p>
            <div class="condition-bar mb-2">
              <div class="condition-fill" style="width: <?= $listing['conditions'] ?>%;"></div>
            </div>
            <p><span class="info-label">Restoration:</span> <?= $listing['restoration_status'] ?></p>
            <p><span class="info-label">Engine:</span> <?= $listing['engine_condition'] ?></p>
            <p>
              <span class="info-label">AC:</span> <?= $listing['air_conditioning'] ? '✅' : '❌' ?>,
              <span class="info-label">Steering:</span> <?= $listing['power_steering'] ? '✅' : '❌' ?>,
              <span class="info-label">Brakes:</span> <?= $listing['power_brakes'] ? '✅' : '❌' ?>
            </p>
            <a href="mailto:<?= $listing['contact_email'] ?>?subject=Interest in <?= $car['name'] ?>" class="btn btn-outline-primary btn-sm">Contact Seller</a>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-muted">No listings available for this price range.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include("includes/footer.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
