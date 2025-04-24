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

// Fetch listings for this car
$stmt2 = $conn->prepare("SELECT * FROM car_listings WHERE car_id = ?");
$stmt2->bind_param("i", $car['id']);
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

<?php include("includes/header.html"); ?>

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
      <?php if (count($listings) > 0): ?>
        <?php foreach ($listings as $listing): ?>
          <div class="border rounded p-3 mb-3 bg-white shadow-sm">
            <p><span class="info-label">Price:</span> <?= $listing['price'] ?></p>
            <p><span class="info-label">Location:</span> <?= $listing['location'] ?></p>
            <p><span class="info-label">Condition:</span></p>
            <div class="condition-bar mb-2">
              <div class="condition-fill" style="width: <?= $listing['conditions'] ?>%;"></div>
            </div>
            <a href="mailto:<?= $listing['contact_email'] ?>?subject=Interest in <?= $car['name'] ?>" class="btn btn-outline-primary btn-sm">Contact Seller</a>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-muted">No listings available for this car yet.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include("includes/footer.html"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
