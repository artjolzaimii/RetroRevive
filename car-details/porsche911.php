<?php
$car = [
  "name" => "Porsche 911",
  "year" => "1963",
  "image" => "../Images/img4.jpg",
  "description" => "The Porsche 911 is one of the most iconic sports cars of all time. First introduced in 1963, it became a symbol of German engineering, precision, and timeless design. With its rear-engine layout and distinctive silhouette, the 911 redefined performance and style, laying the foundation for generations of high-performance Porsche vehicles.",
  "brand" => "Porsche",
  "category" => "Sports Car",
  "status" => "Buyable"
];

$listings = [
  [
    "price" => "$110,000",
    "condition" => 90,
    "location" => "Miami, FL",
    "contact" => "floridaclassics@gmail.com"
  ],
  [
    "price" => "$98,000",
    "condition" => 75,
    "location" => "Austin, TX",
    "contact" => "texasretroauto@example.com"
  ],
  [
    "price" => "$125,000",
    "condition" => 95,
    "location" => "San Diego, CA",
    "contact" => "sandiegoporsches@classics.com"
  ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $car['name'] . ' (' . $car['year'] . ')'; ?> | RetroRevive</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fa;
    }
    .car-img {
      width: 100%;
      border-radius: 10px;
      object-fit: cover;
      max-height: 400px;
    }
    .info-label {
      font-weight: bold;
    }
    .condition-bar {
      height: 20px;
      border-radius: 20px;
      background: #e9ecef;
    }
    .condition-fill {
      background:rgb(0, 95, 143);
      height: 100%;
      border-radius: 20px;
    }
    .btn-contact {
      margin-top: 10px;
    }
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
      <p><span class="info-label">Status:</span> <span class="badge bg-success"><?php echo $car['status']; ?></span></p>
    </div>

    <!-- Listings -->
    <div class="col-md-6">
      <h4>Available Listings</h4>
      <?php foreach ($listings as $listing): ?>
        <div class="border rounded p-3 mb-3 bg-white shadow-sm">
          <p><span class="info-label">Price:</span> <?php echo $listing['price']; ?></p>
          <p><span class="info-label">Location:</span> <?php echo $listing['location']; ?></p>
          <p><span class="info-label">Condition:</span></p>
          <div class="condition-bar mb-2">
            <div class="condition-fill" style="width: <?php echo $listing['condition']; ?>%;"></div>
          </div>
          <a href="mailto:<?php echo $listing['contact']; ?>?subject=Interest in <?php echo $car['name']; ?> (<?php echo $car['year']; ?>)&body=Hello,%0D%0AI'm interested in the <?php echo $car['name']; ?> (<?php echo $car['year']; ?>) listed on RetroRevive.%0D%0ACould you please provide more details?"
             class="btn btn-outline-primary btn-sm btn-contact">
            Contact Seller
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<?php include("../includes/footer.html"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
