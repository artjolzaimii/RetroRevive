<?php
include("../includes/db.php");
include("../includes/header.php");

// Fetch showroom cars
$sql = "SELECT * FROM cars WHERE status = 'Showroom Only'";
$result = $conn->query($sql);
$cars = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book for Showroom | RetroRevive</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
    .car-card { background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    .car-card img { width: 100%; height: 200px; object-fit: cover; }
    .car-card-body { padding: 15px; }
    .rent-btn { margin-top: 10px; }

    .car-card:hover {
  transform: scale(1.03);
  cursor: pointer;
}

  </style>
</head>
<body>

<div class="container pt-5 mt-5">
  <h1 class="text-center mb-4">Book a Classic Car for Showroom Display</h1>
  <div class="row g-4">
    <?php foreach ($cars as $car): ?>
      <div class="col-md-4">
        <div class="car-card">
        <a href="../car-details.php?car=<?php echo urlencode($car['slug']); ?>" style="text-decoration: none; color: inherit;">
  <div class="car-card">
    <img src="../Images/<?php echo $car['image']; ?>" alt="<?php echo $car['name']; ?>">
    <!-- rest of the content -->
  </div>
</a>

          <div class="car-card-body">
            <h5 class="car-title"><?= $car['name'] ?> (<?= $car['year'] ?>)</h5>
            <p><strong>Brand:</strong> <?= $car['brand'] ?></p>
            <p><strong>Rate:</strong> $<?= $car['daily_rate'] ?>/day</p>
            <button class="btn btn-dark rent-btn" data-bs-toggle="modal" data-bs-target="#rentModal<?= $car['id'] ?>">Rent This Car</button>
          </div>
        </div>
      </div>

      <!-- Rental Modal -->
      <div class="modal fade" id="rentModal<?= $car['id'] ?>" tabindex="-1" aria-labelledby="rentModalLabel<?= $car['id'] ?>" aria-hidden="true">
        <div class="modal-dialog">
          <form action="handle-rental.php" method="POST" class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Book <?= $car['name'] ?></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
              <div class="mb-3">
                <label for="customer_name" class="form-label">Your Name</label>
                <input type="text" name="customer_name" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="customer_email" class="form-label">Your Email</label>
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
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success">Confirm Booking</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php include("../includes/footer.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
