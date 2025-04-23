<?php
// chevroletcorvette.php
$car = [
  "name" => "Chevrolet Corvette",
  "year" => "1963",
  "image" => "../Images/img10.jpg",
  "description" => "The 1963 Chevrolet Corvette Sting Ray, known for its iconic split rear window, marked a major redesign of the Corvette. It introduced hidden headlamps, a sleek aerodynamic body, and independent rear suspension for improved handling. This model remains one of the most collectible American cars ever made.",
  "brand" => "Chevrolet",
  "category" => "Sports Car",
  "status" => "Buyable"
];

$listings = [
  [
    "price" => "$80,000",
    "condition" => 85,
    "location" => "Detroit, MI",
    "contact" => "detroitclassics@mail.com"
  ],
  [
    "price" => "$92,000",
    "condition" => 93,
    "location" => "Los Angeles, CA",
    "contact" => "lapremiumcorvettes@example.com"
  ],
  [
    "price" => "$77,500",
    "condition" => 80,
    "location" => "Nashville, TN",
    "contact" => "nashcorvettes@vintagecars.net"
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
    .condition-fill {  background:rgb(0, 95, 143); height: 100%; border-radius: 20px; }
  </style>
</head>
<body>

<?php include("../includes/header.html"); ?>

<div class="container mt-5 pt-5">
  <div class="row g-5">
    <!-- Car Details -->
    <div class="col-md-6">
      <img src="<?php echo $car['image']; ?>" class="car-img mb-4" alt="<?php echo $car['name']; ?>">
      <h2><?php echo $car['name']; ?> (<?php echo $car['year']; ?>)</h2>
      <p><?php echo $car['description']; ?></p>
      <p><span class="info-label">Brand:</span> <?php echo $car['brand']; ?></p>
      <p><span class="info-label">Category:</span> <?php echo $car['category']; ?></p>
      <p><span class="info-label">Status:</span> <span class="badge bg-success">Buyable</span></p>
    </div>

    <!-- Listings -->
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
          <p><span class="info-label">Contact:</span> <a href="mailto:<?php echo $listing['contact']; ?>?subject=Interested in <?php echo $car['name']; ?>" class="btn btn-outline-primary btn-sm">Email Owner</a></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<?php include("../includes/footer.html"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
