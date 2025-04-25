<?php
include 'includes/admin-header.php';

include '../includes/db.php';

// Fetch car options for dropdown
$cars = $conn->query("SELECT id, name FROM cars ORDER BY name");
?>

<div class="container my-5">
  <div class="bg-white p-4 rounded shadow">
    <h2 class="mb-4">Add New Listing</h2>
    <form action="handle-add-listing.php" method="POST">
      <div class="mb-3">
        <label class="form-label">Select Car</label>
        <select class="form-select" name="car_id" required>
          <option disabled selected>Select a car</option>
          <?php while ($car = $cars->fetch_assoc()): ?>
            <option value="<?= $car['id'] ?>"><?= htmlspecialchars($car['name']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Price</label>
          <input type="text" class="form-control" name="price" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Condition (0-100%)</label>
          <input type="number" class="form-control" name="condition" min="0" max="100" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Location</label>
          <input type="text" class="form-control" name="location" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Contact Email</label>
          <input type="email" class="form-control" name="contact_email" required>
        </div>
      </div>

      <div class="text-end">
        <button type="submit" class="btn btn-success">Add Listing</button>
        <a href="listings.php" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<?php include 'includes/admin-footer.php'; ?>
