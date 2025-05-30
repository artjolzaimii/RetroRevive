<?php
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_search'])) {
    $search = trim($_POST['ajax_search']);
    $searchTerm = "%$search%";

    $stmt = $conn->prepare("
        SELECT car_listings.*, cars.name AS car_name 
        FROM car_listings 
        JOIN cars ON car_listings.car_id = cars.id 
        WHERE cars.name LIKE ?
        ORDER BY car_listings.id DESC
    ");
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo '<tr>
            <td>' . htmlspecialchars($row['car_name']) . '</td>
            <td>' . $row['price'] . '</td>
            <td>' . $row['conditions'] . '%</td>
            <td>' . $row['location'] . '</td>
            <td>' . $row['contact_email'] . '</td>
            <td>' . $row['exterior_color'] . '</td>
            <td>' . $row['interior_color'] . '</td>
            <td>' . $row['transmission'] . '</td>
            <td>' . $row['mileage'] . '</td>
            <td>' . $row['title_status'] . '</td>
            <td>' . $row['restoration_status'] . '</td>
            <td>' . ucfirst($row['engine_condition']) . '</td>
            <td>' . ($row['air_conditioning'] === 'Yes' ? '✔️' : '❌') . '</td>
            <td>' . ($row['power_steering'] === 'Yes' ? '✔️' : '❌') . '</td>
            <td>' . ($row['power_brakes'] === 'Yes' ? '✔️' : '❌') . '</td>
            <td>
                <a href="edit-listing.php?id=' . $row['id'] . '" class="btn btn-sm btn-warning">Edit</a>
                <a href="delete-listing.php?id=' . $row['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</a>
            </td>
        </tr>';
    }
    exit();
}

// Pagination
$rows_per_page = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $rows_per_page;

// Count total rows
$count_stmt = $conn->prepare("SELECT COUNT(*) AS total FROM car_listings");
$count_stmt->execute();
$total_rows = $count_stmt->get_result()->fetch_assoc()['total'];
$count_stmt->close();
$total_pages = ceil($total_rows / $rows_per_page);

// Fetch data for current page
$stmt = $conn->prepare("
    SELECT car_listings.*, cars.name AS car_name 
    FROM car_listings 
    JOIN cars ON car_listings.car_id = cars.id 
    ORDER BY car_listings.id DESC
    LIMIT ?, ?
");
$stmt->bind_param("ii", $offset, $rows_per_page);
$stmt->execute();
$result = $stmt->get_result();

include 'includes/admin-header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Admin Listings | RetroRevive</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
/* Sticky column for last column (Actions) */
.sticky-actions th:last-child,
.sticky-actions td:last-child {
  position: sticky;
  right: 0;
  background: #fff; /* default for data cells */
  z-index: 2;
  box-shadow: -2px 0 5px rgba(0,0,0,0.1);
  white-space: nowrap;
}

.sticky-actions th:last-child {
  z-index: 3;
  background: #212529; /* dark header background for header cell */
  color: #fff;
}

/* Black pagination buttons with white text */
.pagination .page-link {
  color: #fff; /* White text */
  background-color: #000; /* Black background */
  border: 1px solid #000;
}

.pagination .page-link:hover {
  background-color: #333; /* Darker gray on hover */
  color: #fff;
}

.pagination .page-item.active .page-link {
  background-color: #000; /* Black for active page */
  border-color: #000;
  color: #fff;
}

</style>
</head>
<body>

<div class="container my-5">
  <div class="bg-white p-4 rounded shadow">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">Car Listings</h2>
      <a href="add-listing.php" class="btn btn-success">+ Add New Listing</a>
    </div>

    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search by car name...">

    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle table-bordered sticky-actions">
        <thead class="table-dark text-center">
          <tr>
            <th>Car</th>
            <th>Price</th>
            <th>Condition</th>
            <th>Location</th>
            <th>Contact</th>
            <th>Exterior</th>
            <th>Interior</th>
            <th>Transmission</th>
            <th>Mileage</th>
            <th>Title</th>
            <th>Restoration</th>
            <th>Engine</th>
            <th>AC</th>
            <th>Steering</th>
            <th>Brakes</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="listingsContainer">
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr class="text-center">
              <td><?= htmlspecialchars($row['car_name']) ?></td>
              <td><?= $row['price'] ?></td>
              <td><?= $row['conditions'] ?>%</td>
              <td><?= $row['location'] ?></td>
              <td><?= $row['contact_email'] ?></td>
              <td><?= $row['exterior_color'] ?></td>
              <td><?= $row['interior_color'] ?></td>
              <td><?= $row['transmission'] ?></td>
              <td><?= $row['mileage'] ?></td>
              <td><?= $row['title_status'] ?></td>
              <td><?= $row['restoration_status'] ?></td>
              <td><?= $row['engine_condition'] ?></td>
              <td><?= $row['air_conditioning'] === 'Yes' ? '✔️' : '❌' ?></td>
              <td><?= $row['power_steering'] === 'Yes' ? '✔️' : '❌' ?></td>
              <td><?= $row['power_brakes'] === 'Yes' ? '✔️' : '❌' ?></td>
              <td>
                <a href="edit-listing.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="delete-listing.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <!-- Pagination controls -->
    <nav aria-label="Page navigation" class="mt-4">
      <ul class="pagination justify-content-center">
        <?php if ($page > 1): ?>
          <li class="page-item">
            <a class="page-link" href="?page=<?= $page - 1 ?>">&laquo;</a>
          </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <li class="page-item <?= $i == $page ? 'active' : '' ?>">
            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
          <li class="page-item">
            <a class="page-link" href="?page=<?= $page + 1 ?>">&raquo;</a>
          </li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</div>

<script>
document.getElementById("searchInput").addEventListener("keyup", function () {
  const query = this.value;
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onload = function () {
    if (xhr.status === 200) {
      document.getElementById("listingsContainer").innerHTML = xhr.responseText;
    }
  };
  xhr.send("ajax_search=" + encodeURIComponent(query));
});
</script>

<?php include 'includes/admin-footer.php'; ?>
</body>
</html>
