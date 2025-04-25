<?php
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_search'])) {
    $search = '%' . trim($_POST['ajax_search']) . '%';

    $stmt = $conn->prepare("
        SELECT rentals.*, users.username, users.email, cars.name AS car_name 
        FROM rentals 
        JOIN users ON rentals.user_id = users.id 
        JOIN cars ON rentals.car_id = cars.id 
        WHERE users.email LIKE ? OR cars.name LIKE ?
        ORDER BY rentals.id DESC
    ");
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>" . htmlspecialchars($row['car_name']) . "</td>
            <td>" . htmlspecialchars($row['username']) . "</td>
            <td>" . $row['email'] . "</td>
            <td>" . $row['start_date'] . "</td>
            <td>" . $row['end_date'] . "</td>
            <td>$" . number_format($row['total_price'], 2) . "</td>
        </tr>";
    }
    exit();
}

include 'includes/admin-header.php';

$stmt = $conn->prepare("
    SELECT rentals.*, users.username, users.email, cars.name AS car_name 
    FROM rentals 
    JOIN users ON rentals.user_id = users.id 
    JOIN cars ON rentals.car_id = cars.id 
    ORDER BY rentals.id DESC
");
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container my-5">
  <div class="bg-white p-4 rounded shadow">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">Rental Bookings</h2>
    </div>

    <input type="text" id="searchRental" class="form-control mb-3" placeholder="Search by car name or email...">

    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle">
        <thead class="table-dark">
          <tr>
            <th>Car</th>
            <th>User</th>
            <th>Email</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Total Price</th>
          </tr>
        </thead>
        <tbody id="rentalsContainer">
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['car_name']) ?></td>
              <td><?= htmlspecialchars($row['username']) ?></td>
              <td><?= $row['email'] ?></td>
              <td><?= $row['start_date'] ?></td>
              <td><?= $row['end_date'] ?></td>
              <td>$<?= number_format($row['total_price'], 2) ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
document.getElementById("searchRental").addEventListener("keyup", function () {
  const query = this.value;
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onload = function () {
    if (xhr.status === 200) {
      document.getElementById("rentalsContainer").innerHTML = xhr.responseText;
    }
  };
  xhr.send("ajax_search=" + encodeURIComponent(query));
});
</script>

<?php include 'includes/admin-footer.php'; ?>
