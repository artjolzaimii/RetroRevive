<?php
include 'includes/admin-header.php';
include '../includes/db.php';

if (!isset($_GET['id'])) {
    die("Listing ID missing.");
}

$id = (int) $_GET['id'];

// Get listing data
$stmt = $conn->prepare("SELECT * FROM car_listings WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$listing = $result->fetch_assoc();

// Get car list for dropdown
$cars = $conn->query("SELECT id, name FROM cars ORDER BY name");

?>

<div class="container my-5">
  <div class="bg-white p-4 rounded shadow">
    <h2 class="mb-4">Edit Listing</h2>

    <form action="handle-edit-listing.php" method="POST">
      <input type="hidden" name="id" value="<?= $listing['id'] ?>">

      <div class="mb-3">
        <label class="form-label">Select Car</label>
        <select class="form-select" name="car_id" required>
          <option disabled>Select a car</option>
          <?php while ($car = $cars->fetch_assoc()): ?>
            <option value="<?= $car['id'] ?>" <?= $listing['car_id'] == $car['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($car['name']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Price</label>
          <input type="text" class="form-control" name="price" value="<?= $listing['price'] ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Condition (%)</label>
          <input type="number" class="form-control" name="condition" min="0" max="100" value="<?= $listing['conditions'] ?>" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Location</label>
          <input type="text" class="form-control" name="location" value="<?= $listing['location'] ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Contact Email</label>
          <input type="email" class="form-control" name="contact_email" value="<?= $listing['contact_email'] ?>" required>
        </div>
      </div>

      <hr>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Exterior Color</label>
          <input type="text" class="form-control" name="exterior_color" value="<?= $listing['exterior_color'] ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Interior Color</label>
          <input type="text" class="form-control" name="interior_color" value="<?= $listing['interior_color'] ?>">
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-4">
          <label class="form-label">Transmission</label>
          <input type="text" class="form-control" name="transmission" value="<?= $listing['transmission'] ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">Mileage</label>
          <input type="text" class="form-control" name="mileage" value="<?= $listing['mileage'] ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">Title Status</label>
          <input type="text" class="form-control" name="title_status" value="<?= $listing['title_status'] ?>">
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-4">
          <label class="form-label">Restoration Status</label>
          <input type="text" class="form-control" name="restoration_status" value="<?= $listing['restoration_status'] ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">Engine Condition</label>
          <input type="text" class="form-control" name="engine_condition" value="<?= $listing['engine_condition'] ?>">
        </div>
      </div>

      <div class="mb-3 form-check form-switch">
        <input class="form-check-input" type="checkbox" name="air_conditioning" value="1" <?= $listing['air_conditioning'] ? 'checked' : '' ?>>
        <label class="form-check-label">Air Conditioning</label>
      </div>

      <div class="mb-3 form-check form-switch">
        <input class="form-check-input" type="checkbox" name="power_steering" value="1" <?= $listing['power_steering'] ? 'checked' : '' ?>>
        <label class="form-check-label">Power Steering</label>
      </div>

      <div class="mb-3 form-check form-switch">
        <input class="form-check-input" type="checkbox" name="power_brakes" value="1" <?= $listing['power_brakes'] ? 'checked' : '' ?>>
        <label class="form-check-label">Power Brakes</label>
      </div>

      <div class="text-end">
        <button type="submit" class="btn btn-primary">Update Listing</button>
        <a href="listings.php" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<?php include 'includes/admin-footer.php'; ?>
