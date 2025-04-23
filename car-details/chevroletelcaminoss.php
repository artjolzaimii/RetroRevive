<?php
// chevroleteelcamino.php
$car = [
  "name" => "Chevrolet El Camino SS",
  "year" => "1970",
  "image" => "../Images/img5.jpg",
  "description" => "The 1970 Chevrolet El Camino SS blends the practicality of a pickup truck with the performance and styling of a muscle car. As part of the legendary Chevelle family, the SS model came equipped with high-performance V8 engines, aggressive design features, and sporty upgrades, making it a favorite among classic car collectors and enthusiasts.",
  "brand" => "Chevrolet",
  "category" => "Muscle Car / Pickup Hybrid",
  "status" => "Buyable"
];

$listings = [
  [
    "price" => "$33,000",
    "condition" => 85,
    "location" => "Phoenix, AZ",
    "contact" => "desertmusclecars@example.com"
  ],
  [
    "price" => "$36,500",
    "condition" => 92,
    "location" => "Atlanta, GA",
    "contact" => "atlclassicchevy@gmail.com"
  ],
  [
    "price" => "$30,000",
    "condition" => 78,
    "location" => "Denver, CO",
    "contact" => "milehighmuscle@classics.com"
  ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $car['name'] . ' (' . $car['year'] . ')'; ?> | RetroRevive</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: 'Segoe UI', sans-serif; background-color: #f8f9fa; }
    .car-img { width: 100%; border-radius: 10px; object-fit: cover; }
    .info-label { font-weight: bold; }
    .condition-bar { height: 20px; border-radius: 20px; background: #e9ecef; }
    .condition-fill { background: #28a745; height: 100%; border-radius: 20px; }
  </style>
</head>
<body>

<?php include("../includes/header.html"); ?>

<div class="container mt-5 pt-5">
  <div class="row g-5">
    <!-- Car Info -->
    <div class="col-md-6">
      <img src="<?php echo $car['image']; ?>" class="car-img mb-4" alt="<?php echo $car['name']; ?>">
      <h2><?php echo $car['name']; ?> (<?php echo $car['year']; ?>)</h2>
      <p><?php echo $car['description']; ?></p>
      <p><span class="info-label">Brand:</span> <?php echo $car['brand']; ?></p>
      <p><span class="info-label">Category:</span> <?php echo $car['category']; ?></p>
      <p><span class="info-label">Status:</span> <span class="badge bg-success">Buyable</span></p>
    </div>

    <!-- Owner Listings -->
    <div class="col-md-6">
      <h4>Available Listings</h4>
      <?php foreach ($listings as $listing): ?>
        <div class="border rounded p-3 mb-3 bg-white">
          <p><span class="info-label">Price:</span> <?php echo $listing['price']; ?></p>
          <p><span class="info-label">Location:</span> <?php echo $listing['location']; ?></p>
          <p><span class="info-label">Condition:</span></p>
          <div class="condition-bar mb-2">
            <div class="condition-fill" style="width: <?php echo $listing['condition']; ?>%;"></div>
          </div>
          <a href="mailto:<?php echo $listing['contact']; ?>?subject=Inquiry about <?php echo $car['name']; ?>" class="btn btn-outline-primary btn-sm">Contact Owner</a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<?php include("../includes/footer.html"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
