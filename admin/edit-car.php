<?php
include 'includes/admin-header.php';
include '../includes/db.php';

// Get car data
$car_id = $_GET['id'] ?? null;

if (!$car_id) {
    die("Car ID not provided.");
}

$stmt = $conn->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->bind_param("i", $car_id);
$stmt->execute();
$result = $stmt->get_result();
$car = $result->fetch_assoc();

if (!$car) {
    die("Car not found.");
}
?>

<div class="container my-5">
  <div class="bg-white p-4 rounded shadow">
    <h2 class="mb-4">Edit Car</h2>
    <form action="handle-edit-car.php" method="POST">
      <input type="hidden" name="id" value="<?= $car['id'] ?>">

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Car Name</label>
          <input type="text" class="form-control" name="name" value="<?= $car['name'] ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Year</label>
          <input type="text" class="form-control" name="year" value="<?= $car['year'] ?>" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Brand</label>
          <input type="text" class="form-control" name="brand" value="<?= $car['brand'] ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Price</label>
          <input type="text" class="form-control" name="price" value="<?= $car['price'] ?>" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Status</label>
          <select class="form-select" name="status" required>
            <option value="Buyable" <?= $car['status'] === 'Buyable' ? 'selected' : '' ?>>Buyable</option>
            <option value="Showroom Only" <?= $car['status'] === 'Showroom Only' ? 'selected' : '' ?>>Showroom Only</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Image Filename</label>
          <input type="text" class="form-control" name="image" value="<?= $car['image'] ?>" required>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea class="form-control" name="description" rows="5" required><?= $car['description'] ?></textarea>
      </div>

      <div class="text-end">
        <button type="submit" class="btn btn-primary">Update Car</button>
        <a href="cars.php" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<?php include 'includes/admin-footer.php'; ?>
