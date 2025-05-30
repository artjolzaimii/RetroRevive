<?php
include '../includes/db.php';

// Handle search with pagination
$search = isset($_POST['ajax_search']) ? '%' . trim($_POST['ajax_search']) . '%' : '';
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 5;
$offset = ($page - 1) * $perPage;

// Count total rows
if ($search !== '') {
    $stmtCount = $conn->prepare("
        SELECT COUNT(*) AS total
        FROM rentals
        JOIN users ON rentals.user_id = users.id
        JOIN cars ON rentals.car_id = cars.id
        WHERE users.email LIKE ? OR cars.name LIKE ?
    ");
    $stmtCount->bind_param("ss", $search, $search);
} else {
    $stmtCount = $conn->prepare("SELECT COUNT(*) AS total FROM rentals");
}
$stmtCount->execute();
$totalRows = $stmtCount->get_result()->fetch_assoc()['total'];
$stmtCount->close();
$totalPages = ceil($totalRows / $perPage);

// Fetch paginated rentals
if ($search !== '') {
    $stmt = $conn->prepare("
        SELECT rentals.*, users.username, users.email, cars.name AS car_name
        FROM rentals
        JOIN users ON rentals.user_id = users.id
        JOIN cars ON rentals.car_id = cars.id
        WHERE users.email LIKE ? OR cars.name LIKE ?
        ORDER BY rentals.id DESC
        LIMIT ?, ?
    ");
    $stmt->bind_param("ssii", $search, $search, $offset, $perPage);
} else {
    $stmt = $conn->prepare("
        SELECT rentals.*, users.username, users.email, cars.name AS car_name
        FROM rentals
        JOIN users ON rentals.user_id = users.id
        JOIN cars ON rentals.car_id = cars.id
        ORDER BY rentals.id DESC
        LIMIT ?, ?
    ");
    $stmt->bind_param("ii", $offset, $perPage);
}
$stmt->execute();
$result = $stmt->get_result();

// If AJAX search, return only table rows
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_search'])) {
    ob_start();
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>" . htmlspecialchars($row['car_name']) . "</td>
            <td>" . htmlspecialchars($row['username']) . "</td>
            <td>" . htmlspecialchars($row['email']) . "</td>
            <td>" . $row['start_date'] . "</td>
            <td>" . $row['end_date'] . "</td>
            <td>$" . number_format($row['total_price'], 2) . "</td>
        </tr>";
    }

    // Pagination for AJAX
    echo "<tr><td colspan='6' class='text-center mt-2'>";
    for ($i = 1; $i <= $totalPages; $i++) {
        $active = ($i == $page) ? 'btn-dark' : 'btn-outline-dark';
        echo "<a href='#' class='btn $active btn-sm mx-1 pagination-link' data-page='$i'>$i</a>";
    }
    echo "</td></tr>";

    ob_end_flush();
    exit;
}

include 'includes/admin-header.php';
?>

<div class="container my-5">
  <div class="bg-white p-4 rounded shadow">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">Rental Bookings</h2>
      <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#statsModal">View Statistics</button>
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
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= $row['start_date'] ?></td>
              <td><?= $row['end_date'] ?></td>
              <td>$<?= number_format($row['total_price'], 2) ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <!-- Static pagination initially (will be replaced by AJAX) -->
    <div class="text-center mt-3" id="paginationContainer">
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="#" class="btn <?= $i == $page ? 'btn-dark' : 'btn-outline-dark' ?> btn-sm mx-1 pagination-link" data-page="<?= $i ?>"><?= $i ?></a>
      <?php endfor; ?>
    </div>
  </div>
</div>

<!-- Statistics Modal (no change) -->
<div class="modal fade" id="statsModal" tabindex="-1" aria-labelledby="statsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="statsModalLabel">Rental Statistics</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <canvas id="mostRentedChart" class="mb-4" height="250"></canvas>
        <canvas id="profitBrandChart" class="mb-4" height="250"></canvas>
        <canvas id="monthlyRevenueChart" height="250"></canvas>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.getElementById("searchRental").addEventListener("input", function () {
  loadRentals(1);
});

document.addEventListener("click", function (e) {
  if (e.target.classList.contains("pagination-link")) {
    e.preventDefault();
    const page = e.target.dataset.page;
    loadRentals(page);
  }
});

function loadRentals(page = 1) {
  const query = document.getElementById("searchRental").value;
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "rentals.php?page=" + page, true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onload = function () {
    if (xhr.status === 200) {
      document.getElementById("rentalsContainer").innerHTML = xhr.responseText;
      document.getElementById("paginationContainer").style.display = "none"; // Hide static pagination
    }
  };
  xhr.send("ajax_search=" + encodeURIComponent(query));
}

// Chart rendering logic unchanged
document.getElementById('statsModal').addEventListener('shown.bs.modal', function () {
  fetch('rental-stats.php')
    .then(res => res.json())
    .then(data => {
      // Re-render charts
      document.getElementById('mostRentedChart').remove();
      document.getElementById('profitBrandChart').remove();
      document.getElementById('monthlyRevenueChart').remove();

      const body = document.querySelector('.modal-body');
      body.innerHTML = `
        <canvas id="mostRentedChart" class="mb-4" height="250"></canvas>
        <canvas id="profitBrandChart" class="mb-4" height="250"></canvas>
        <canvas id="monthlyRevenueChart" height="250"></canvas>
      `;

      new Chart(document.getElementById('mostRentedChart').getContext('2d'), {
        type: 'bar',
        data: { labels: data.mostRented.labels, datasets: [{ label: 'Most Rented Cars', data: data.mostRented.data, backgroundColor: '#1c1c1c' }] }
      });

      new Chart(document.getElementById('profitBrandChart').getContext('2d'), {
        type: 'bar',
        data: { labels: data.profitPerBrand.labels, datasets: [{ label: 'Total Profit ($)', data: data.profitPerBrand.data, backgroundColor: '#1c1c1c' }] }
      });

      new Chart(document.getElementById('monthlyRevenueChart').getContext('2d'), {
        type: 'line',
        data: { labels: data.monthlyRevenue.labels, datasets: [{ label: 'Monthly Revenue ($)', data: data.monthlyRevenue.data, borderColor: '#1c1c1c', fill: false }] }
      });
    });
});
</script>

<?php include 'includes/admin-footer.php'; ?>
