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

      <!-- EXTRA VEHICLE DETAILS -->
      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Exterior Color</label>
          <input type="text" class="form-control" name="exterior_color">
        </div>
        <div class="col-md-6">
          <label class="form-label">Interior Color</label>
          <input type="text" class="form-control" name="interior_color">
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Transmission</label>
          <input type="text" class="form-control" name="transmission">
        </div>
        <div class="col-md-6">
          <label class="form-label">Mileage</label>
          <input type="number" class="form-control" name="mileage">
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Title Status</label>
          <input type="text" class="form-control" name="title_status">
        </div>
        <div class="col-md-6">
          <label class="form-label">Restoration Status</label>
          <input type="text" class="form-control" name="restoration_status">
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-4">
          <label class="form-label">Air Conditioning</label>
          <select class="form-select" name="air_conditioning">
            <option value="">Select</option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Power Steering</label>
          <select class="form-select" name="power_steering">
            <option value="">Select</option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Power Brakes</label>
          <select class="form-select" name="power_brakes">
            <option value="">Select</option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
          </select>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Engine Condition</label>
        <input type="text" class="form-control" name="engine_condition">
      </div>

      <div class="text-end">
        <button type="submit" class="btn btn-success">Add Listing</button>
        <a href="listings.php" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<?php include 'includes/admin-footer.php'; ?>
