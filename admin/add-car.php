<?php
include 'includes/admin-header.php';

?>

<div class="container my-5">
  <div class="bg-white p-4 rounded shadow">
    <h2 class="mb-4">Add New Car</h2>
    <form action="handle-add-car.php" method="POST" enctype="multipart/form-data">
      <div class="row mb-3">
        <div class="col-md-6">
          <label for="name" class="form-label">Car Name</label>
          <input type="text" class="form-control" name="name" required>
        </div>
        <div class="col-md-6">
          <label for="year" class="form-label">Year</label>
          <input type="text" class="form-control" name="year" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label for="brand" class="form-label">Brand</label>
          <input type="text" class="form-control" name="brand" required>
        </div>
        <div class="col-md-6">
          <label for="price" class="form-label">Price</label>
          <input type="text" class="form-control" name="price" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label for="status" class="form-label">Status</label>
          <select class="form-select" name="status" required>
            <option value="Buyable">Buyable</option>
            <option value="Showroom Only">Showroom Only</option>
          </select>
        </div>
        <div class="col-md-6">
          <label for="image" class="form-label">Image Filename</label>
          <input type="text" class="form-control" name="image" placeholder="e.g. img24.jpg" required>
        </div>
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Car Description</label>
        <textarea class="form-control" name="description" rows="5" required></textarea>
      </div>

      <div class="text-end">
        <button type="submit" class="btn btn-success">Add Car</button>
        <a href="cars.php" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<?php include 'includes/admin-footer.php'; ?>
