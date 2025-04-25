<?php
include '../includes/db.php';

// Handle search logic
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
  $stmt = $conn->prepare("SELECT * FROM cars WHERE name LIKE ? ORDER BY id ASC");
  $term = "%$search%";
  $stmt->bind_param("s", $term);
} else {
  $stmt = $conn->prepare("SELECT * FROM cars ORDER BY id ASC");
}

$stmt->execute();
$result = $stmt->get_result();

// AJAX-only response
if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      echo "<tr>
        <td>{$row['id']}</td>
        <td>" . htmlspecialchars($row['name']) . "</td>
        <td>{$row['year']}</td>
        <td>{$row['brand']}</td>
        <td><span class='badge bg-" . ($row['status'] === 'Buyable' ? 'success' : 'info') . "'>{$row['status']}</span></td>
        <td>{$row['price']}</td>
        <td>{$row['slug']}</td>
        <td>
          <a href='edit-car.php?id={$row['id']}' class='btn btn-sm btn-primary'>Edit</a>
          <a href='delete-car.php?id={$row['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure?')\">Delete</a>
        </td>
      </tr>";
    }
  } else {
    echo "<tr><td colspan='8' class='text-center text-muted'>No cars found.</td></tr>";
  }
  exit;
}
?>
<?php
include 'includes/admin-header.php';

 if (isset($_GET['success'])): ?>
  <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
     New car added successfully!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<?php if (isset($_GET['updated'])): ?>
  <div class="alert alert-info alert-dismissible fade show mt-3" role="alert">
    Car details updated successfully.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<?php if (isset($_GET['deleted'])): ?>
  <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
     Car deleted successfully.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>


<div class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-black">Manage Cars</h2>
    <a href="add-car.php" class="btn btn-success">+ Add New Car</a>
  </div>

  <div class="mb-3">
    <input type="text" id="searchInput" class="form-control" placeholder="Search by car name...">
  </div>

  <div class="table-responsive bg-white rounded shadow p-3">
    <table class="table table-hover table-bordered">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Year</th>
          <th>Brand</th>
          <th>Status</th>
          <th>Price</th>
          <th>Slug</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="carTableBody">
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= $row['year'] ?></td>
              <td><?= $row['brand'] ?></td>
              <td><span class="badge bg-<?= $row['status'] === 'Buyable' ? 'success' : 'info' ?>"><?= $row['status'] ?></span></td>
              <td><?= $row['price'] ?></td>
              <td><?= $row['slug'] ?></td>
              <td>
                <a href="edit-car.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                <a href="delete-car.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this car?')">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="8" class="text-center text-muted">No cars found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include 'includes/admin-footer.php'; ?>

<script>
  document.getElementById('searchInput').addEventListener('input', function () {
    const query = this.value;
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "cars.php?ajax=1&search=" + encodeURIComponent(query), true);
    xhr.onload = function () {
      if (this.status === 200) {
        document.getElementById("carTableBody").innerHTML = this.responseText;
      }
    };
    xhr.send();
  });
</script>
