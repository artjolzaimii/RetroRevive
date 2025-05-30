<?php
include("../includes/db.php");
include("../includes/header.php");

// Fetch unique brands for filter
$brand_result = $conn->query("SELECT DISTINCT brand FROM cars WHERE status = 'Buyable'");
$brands = $brand_result->fetch_all(MYSQLI_ASSOC);

// Capture filters
$model_filter = isset($_GET['model']) ? trim($_GET['model']) : '';
$year_filter = isset($_GET['year']) ? trim($_GET['year']) : '';
$search = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';

// Pagination
$per_page = 6;
$section = isset($_GET['section']) && is_numeric($_GET['section']) ? (int) $_GET['section'] : 1;
$start = ($section - 1) * $per_page;

// Build SQL dynamically
$sql = "SELECT * FROM cars WHERE status = 'Buyable'";
$params = [];
$where_clauses = [];

if ($model_filter !== '') {
    $where_clauses[] = "brand = ?";
    $params[] = $model_filter;
}
if ($year_filter !== '') {
    $where_clauses[] = "year LIKE ?";
    $params[] = "%" . substr($year_filter, 0, 3) . "%";
}
if ($search !== '') {
    $where_clauses[] = "(LOWER(name) LIKE ? OR year LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if (count($where_clauses) > 0) {
    $sql .= " AND " . implode(" AND ", $where_clauses);
}

// Count total cars
$count_sql = "SELECT COUNT(*) AS total FROM ($sql) AS filtered";
$stmt = $conn->prepare($count_sql);
if (count($params) > 0) {
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$total_rows = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();
$total_sections = ceil($total_rows / $per_page);

// Get cars for this page
$sql .= " LIMIT ?, ?";
$params[] = $start;
$params[] = $per_page;
$stmt = $conn->prepare($sql);

$types = str_repeat('s', count($params) - 2) . 'ii';
$stmt->bind_param($types, ...$params);
$stmt->execute();
$cars = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Buy Classic Cars | RetroRevive</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
body {
  font-family: 'Segoe UI', sans-serif;
  background-color: #f8f9fa;
  scroll-behavior: smooth;
}



.section-title {
  text-align: center;
  margin: 80px 0 30px;
  font-size: 3rem;
  font-weight: 600;
  
  color: #1c1c1c;
  font-family: 'Segoe UI', sans-serif; /* clean style */
}



.filter-bar {
  margin-bottom: 30px;
  margin-top: 20px;
}

.filter-bar select,
.filter-bar input {
  padding: 10px;
  margin: 0 10px;
  border-radius: 5px;
}

.car-card {
  background: #ffffff;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  transition: transform 0.3s, box-shadow 0.3s;
}

.car-card:hover {
  transform: scale(1.03);
  box-shadow: 0 6px 15px rgba(0,0,0,0.1);
}

.car-card img {
  width: 100%;
  height: 220px;
  object-fit: cover;
  transition: transform 0.4s ease;
}

.car-card:hover img {
  transform: scale(1.05);
}

.car-card-body {
  padding: 15px;
}

.car-title {
  font-size: 1.4rem;
  font-weight: bold;
}

.car-year {
  font-size: 1rem;
  color: gray;
}

.car-info {
  font-size: 0.95rem;
  margin-bottom: 5px;
}

.badge-buyable {
  background-color: rgb(16, 85, 164);
  transition: background-color 0.3s ease;
}

.car-card:hover .badge-buyable {
  background-color: #000;
}

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

:focus {
  outline: 2px solid #1c1c1c;
}
</style>


</head>
<body>
  

<div class="container">
  <h1 class="section-title">Buy Your Dream Classic</h1>

  <!-- Filter bar -->
  <div class="filter-bar">
    <form method="GET" class="row justify-content-center align-items-center gx-2 gy-2">
      <div class="col-md-3">
        <select class="form-select" name="model">
          <option value="">All Brands</option>
          <?php foreach ($brands as $brand): ?>
            <option value="<?= htmlspecialchars($brand['brand']) ?>" <?= $model_filter === $brand['brand'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($brand['brand']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3">
        <select class="form-select" name="year">
          <option value="">All Years</option>
          <?php foreach (['1930s', '1940s', '1950s', '1960s', '1970s', '1980s', '1990s'] as $decade): ?>
            <option value="<?= $decade ?>" <?= $year_filter === $decade ? 'selected' : '' ?>><?= $decade ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="Search by name or year..." value="<?= htmlspecialchars($search) ?>">
      </div>
      <div class="col-md-2 d-grid">
        <button type="submit" class="btn btn-dark">Search</button>
      </div>
    </form>
  </div>

  <div class="row g-4">
    <?php foreach ($cars as $car): ?>
      <div class="col-md-4">
        <div class="car-card">
          <a href="../car-details.php?car=<?= urlencode($car['slug']) ?>">
            <img src="../Images/<?= htmlspecialchars($car['image']) ?>" alt="<?= htmlspecialchars($car['name']) ?>">
          </a>
          <div class="car-card-body">
            <span class="badge bg-success badge-buyable mb-2">Buyable</span>
            <h5 class="car-title"><?= htmlspecialchars($car['name']) ?></h5>
            <p class="car-year"><?= htmlspecialchars($car['year']) ?></p>
            <p class="car-info"><strong>Brand:</strong> <?= htmlspecialchars($car['brand']) ?></p>
            <p class="car-info"><strong>Price:</strong> <?= htmlspecialchars($car['price']) ?></p>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Pagination -->
  <nav aria-label="Car pagination" class="text-center my-4">
    <ul class="pagination justify-content-center">
      <?php if ($section > 1): ?>
        <li class="page-item"><a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['section' => $section - 1])) ?>">&laquo;</a></li>
      <?php endif; ?>
      <?php for ($i = 1; $i <= $total_sections; $i++): ?>
        <li class="page-item <?= $i == $section ? 'active' : '' ?>">
          <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['section' => $i])) ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>
      <?php if ($section < $total_sections): ?>
        <li class="page-item"><a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['section' => $section + 1])) ?>">&raquo;</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</div>

<?php include("../includes/footer.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
