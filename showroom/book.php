<?php
include("../includes/db.php");
include("../includes/header.php");

// Fetch all available car brands for the dropdown
$brand_result = $conn->query("SELECT DISTINCT brand FROM cars WHERE status = 'Showroom Only'");
$brands = $brand_result->fetch_all(MYSQLI_ASSOC);

// Pagination setup
$per_page = 6;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$start = ($page - 1) * $per_page;

// Search and filter logic
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$brand_filter = isset($_GET['brand']) ? trim($_GET['brand']) : '';
$params = [];
$sql = "SELECT * FROM cars WHERE status = 'Showroom Only'";
$where_clauses = [];

if ($search !== '') {
    $where_clauses[] = "name LIKE ?";
    $params[] = "%$search%";
}
if ($brand_filter !== '') {
    $where_clauses[] = "brand = ?";
    $params[] = $brand_filter;
}
if (count($where_clauses) > 0) {
    $sql .= " AND " . implode(" AND ", $where_clauses);
}

// Count total
$count_sql = "SELECT COUNT(*) AS total FROM ($sql) AS filtered";
$stmt = $conn->prepare($count_sql);
if (count($params) > 0) {
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$total_rows = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();
$total_pages = ceil($total_rows / $per_page);

// Fetch showroom cars for this page
$sql .= " LIMIT ?, ?";
$params[] = $start;
$params[] = $per_page;
$stmt = $conn->prepare($sql);

$types = str_repeat('s', count($params) - 2) . 'ii';
$stmt->bind_param($types, ...$params);
$stmt->execute();
$cars = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Book for Showroom | RetroRevive</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
.car-card { background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
.car-card img { width: 100%; height: 200px; object-fit: cover; }
.car-card-body { padding: 15px; }
.rent-btn { margin-top: 10px; }
.car-card:hover { transform: scale(1.03); cursor: pointer; }

.pagination .page-item .page-link {
  color: #000;
  background-color: #fff;
  border: 1px solid #000;
  border-radius: 6px;
  width: 40px;
  height: 40px;
  line-height: 40px;
  text-align: center;
  padding: 0;
}
.pagination .page-item.active .page-link {
  background-color: #1c1c1c;
  color: #fff;
  border-color: #1c1c1c;
}
.pagination .page-link:hover {
  background-color: #e2e6ea;
  color: #000;
}
</style>
</head>
<body>

<div class="container pt-5 mt-5">
  <h1 class="text-center mb-4">Book a Classic Car for Showroom Display</h1>

  <!-- Search & Filter Form -->
  <form method="GET" class="mb-4">
    <div class="row g-2">
      <div class="col-md-5">
        <input type="text" name="search" class="form-control" placeholder="Search cars by name..." value="<?= htmlspecialchars($search) ?>">
      </div>
      <div class="col-md-4">
        <select name="brand" class="form-select">
          <option value="">All Brands</option>
          <?php foreach ($brands as $brand): ?>
            <option value="<?= htmlspecialchars($brand['brand']) ?>" <?= $brand_filter === $brand['brand'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($brand['brand']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3">
        <button class="btn btn-dark w-100" type="submit">Filter</button>
      </div>
    </div>
  </form>

  <!-- Alert for booking conflict -->
  <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
    <div class="alert alert-danger text-center">
      This car is already booked for the selected dates.
    </div>
  <?php endif; ?>

  <div class="row g-4">
    <?php foreach ($cars as $car): ?>
      <div class="col-md-4">
        <div class="car-card">
          <a href="../car-details.php?car=<?= urlencode($car['slug']) ?>" style="text-decoration: none; color: inherit;">
            <div class="car-card">
              <img src="../Images/<?= htmlspecialchars($car['image']) ?>" alt="<?= htmlspecialchars($car['name']) ?>">
            </div>
          </a>
          <div class="car-card-body">
            <h5 class="car-title"><?= htmlspecialchars($car['name']) ?> (<?= $car['year'] ?>)</h5>
            <p><strong>Brand:</strong> <?= htmlspecialchars($car['brand']) ?></p>
            <p><strong>Rate:</strong> $<?= $car['daily_rate'] ?>/day</p>
            <button class="btn btn-dark rent-btn" data-bs-toggle="modal" data-bs-target="#rentModal<?= $car['id'] ?>">Rent This Car</button>
          </div>
        </div>
      </div>

      <!-- Rental Modal -->
      <div class="modal fade" id="rentModal<?= $car['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <form action="handle-rental.php" method="POST" class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Book <?= htmlspecialchars($car['name']) ?></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
              <div class="mb-3">
                <label class="form-label">Your Name</label>
                <input type="text" name="customer_name" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Your Email</label>
                <input type="email" name="customer_email" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control" required>
              </div>
              <p class="text-muted"><strong>Rate:</strong> $<?= $car['daily_rate'] ?>/day</p>
              <input type="hidden" name="daily_rate" value="<?= $car['daily_rate'] ?>">
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success">Confirm Booking</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Pagination -->
  <nav aria-label="Showroom car pagination" class="mt-4">
    <ul class="pagination justify-content-center">
      <?php if ($page > 1): ?>
        <li class="page-item"><a class="page-link" href="?search=<?= urlencode($search) ?>&brand=<?= urlencode($brand_filter) ?>&page=<?= $page - 1 ?>">&laquo;</a></li>
      <?php endif; ?>
      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
          <a class="page-link" href="?search=<?= urlencode($search) ?>&brand=<?= urlencode($brand_filter) ?>&page=<?= $i ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>
      <?php if ($page < $total_pages): ?>
        <li class="page-item"><a class="page-link" href="?search=<?= urlencode($search) ?>&brand=<?= urlencode($brand_filter) ?>&page=<?= $page + 1 ?>">&raquo;</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</div>

<?php include("../includes/footer.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
