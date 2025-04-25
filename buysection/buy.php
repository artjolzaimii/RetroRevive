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
    }
    .section-title {
      text-align: center;
      margin: 60px 0 30px;
      font-size: 2.8rem;
      font-weight: 700;
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
      background: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      transition: transform 0.3s;
    }
    .car-card:hover {
      transform: scale(1.03);
    }
    .car-card img {
      width: 100%;
      height: 220px;
      object-fit: cover;
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
      background-color:rgb(16, 85, 164);
    }
  </style>
</head>
<body>

<?php include("../includes/header.php"); ?>
<?php include("../includes/db.php"); ?>

<div class="container">
  <h1 class="section-title">Buy Your Dream Classic</h1>

  <div class="filter-bar">
    <form method="GET" class="row justify-content-center align-items-center gx-2 gy-2">
      <div class="col-md-3">
        <select class="form-select" name="model">
          <option disabled selected>Filter by Model</option>
          <option>Aston Martin</option>
          <option>Ferrari</option>
          <option>Jaguar</option>
          <option>Chevrolet</option>
          <option>Ford</option>
          <option>BMW</option>
          <option>Lamborghini</option>
        </select>
      </div>
      <div class="col-md-3">
        <select class="form-select" name="year">
          <option disabled selected>Filter by Year</option>
          <option>1930s</option>
          <option>1940s</option>
          <option>1950s</option>
          <option>1960s</option>
          <option>1970s</option>
          <option>1980s</option>
          <option>1990s</option>
        </select>
      </div>
      <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="Search by model or year..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
      </div>
      <div class="col-md-2 d-grid">
        <button type="submit" class="btn btn-dark">Search</button>
      </div>
    </form>
  </div>

  <?php
  $model = isset($_GET['model']) ? $_GET['model'] : null;
  $year = isset($_GET['year']) ? $_GET['year'] : null;
  $search = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';

  $sql = "SELECT * FROM cars WHERE status = 'Buyable'";
  $params = [];
  $types = "";

  if ($model) {
    $sql .= " AND brand = ?";
    $params[] = $model;
    $types .= "s";
  }
  if ($year) {
    $sql .= " AND year LIKE ?";
    $params[] = "%" . substr($year, 0, 3) . "%";
    $types .= "s";
  }
  if ($search) {
    $sql .= " AND (LOWER(name) LIKE ? OR year LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= "ss";
  }

  $stmt = $conn->prepare($sql);
  if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
  }
  $stmt->execute();
  $result = $stmt->get_result();

  $cars = [];
  while ($row = $result->fetch_assoc()) {
    $cars[] = $row;
  }

  $stmt->close();
  $conn->close();

  $section = isset($_GET['section']) ? (int)$_GET['section'] : 1;
  $perPage = 6;
  $start = ($section - 1) * $perPage;
  $visibleCars = array_slice($cars, $start, $perPage);
  ?>

  <div class="row g-4">
    <?php foreach ($visibleCars as $car): ?>
      <div class="col-md-4">
        <div class="car-card">
          <a href="../car-details.php?car=<?php echo urlencode($car['slug']); ?>">
            <img src="../Images/<?php echo $car['image']; ?>" alt="<?php echo $car['name']; ?>">
          </a>
          <div class="car-card-body">
            <span class="badge bg-success badge-buyable mb-2">Buyable</span>
            <h5 class="car-title"><?php echo $car['name']; ?></h5>
            <p class="car-year"><?php echo $car['year']; ?></p>
            <p class="car-info"><strong>Brand:</strong> <?php echo $car['brand']; ?></p>
            <p class="car-info"><strong>Current sale value:</strong> <?php echo $car['price']; ?></p>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="text-center my-4">
    <?php 
      $totalSections = ceil(count($cars) / $perPage);
      for ($i = 1; $i <= $totalSections; $i++) {
        $active = $i == $section ? 'btn-dark' : 'btn-outline-dark';
        echo '<a href="?section=' . $i . '" class="btn ' . $active . ' mx-1">' . $i . '</a>';
      }
    ?>
  </div>
</div>

<?php include("../includes/footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>