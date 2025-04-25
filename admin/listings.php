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
?>

<?php
include 'includes/admin-header.php';

$stmt = $conn->prepare("
    SELECT car_listings.*, cars.name AS car_name 
    FROM car_listings 
    JOIN cars ON car_listings.car_id = cars.id 
    ORDER BY car_listings.id DESC
");
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container my-5">
  <div class="bg-white p-4 rounded shadow">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">Car Listings</h2>
      <a href="add-listing.php" class="btn btn-success">+ Add New Listing</a>
    </div>

    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search by car name...">

    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle table-bordered">
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
