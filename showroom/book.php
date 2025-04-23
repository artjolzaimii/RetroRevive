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
      background-color: #198754;
    }
  </style>
</head>
<body>

<?php include("../includes/header.html"); ?>

<div class="container">
  <h1 class="section-title">Book Your Dream Classic</h1>

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

  <div class="row g-4">
    <?php 
    $cars = [
        ["Lamborghini Miura", "1966", "img18.jpg", "Showroom Only", "Lamborghini", "$2 million"],
        ["Aston Martin DB5", "1964", "img1.jpg", "Showroom Only", "Aston Martin", "$990,000"],
        ["Jaguar E-Type", "1961", "img3.jpg", "Showroom Only", "Jaguar", "$125,000"],
        ["Ferrari 250 GTO", "1962", "img2.jpg", "Showroom Only", "Ferrari", "$48 million"],
        ["Mercedes 300SL Gullwing", "1954", "img9.jpg", "Showroom Only", "Mercedes-Benz", "$1.9 million"],
        ["Bugatti Type 57 Atlantic", "1938", "img11.jpg", "Showroom Only", "Bugatti", "$1.4 million"],
        ["Rolls-Royce Dawn Drophead", "1949", "img12.jpg", "Showroom Only", "Rolls-Royce", "$400,000"],
        ["Ford Model T", "1908", "img21.jpg", "Showroom Only", "Ford", "$21,000"],
        ["McLaren F1", "1992", "img22.jpg", "Showroom Only", "McLaren", "$20 million"]
      ];

      $search = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';

      if ($search !== '') {
        $cars = array_filter($cars, function ($car) use ($search) {
          return strpos(strtolower($car[0]), $search) !== false || strpos($car[1], $search) !== false;
        });
        $cars = array_values($cars);
      }

      $section = isset($_GET['section']) ? (int)$_GET['section'] : 1;
      $perPage = 6;
      $start = ($section - 1) * $perPage;

      $visibleCars = array_slice($cars, $start, $perPage);

      foreach ($visibleCars as $car) {
        if ($car[3] === "Showroom Only") 
            {
          echo '<div class="col-md-4">
                  <div class="car-card">
                    <img src="../Images/' . $car[2] . '" alt="' . $car[0] . '">
                    <div class="car-card-body">
                      <span class="badge bg-success badge-buyable mb-2">Showroom Only</span>
                      <h5 class="car-title">' . $car[0] . '</h5>
                      <p class="car-year">' . $car[1] . '</p>
                      <p class="car-info"><strong>Brand:</strong> ' . $car[4] . '</p>
                      <p class="car-info"><strong>Current sale value:</strong> ' . $car[5] . '</p>
                    </div>
                  </div>
                </div>';
        }
      }
    ?>
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

<?php include("../includes/footer.html"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>