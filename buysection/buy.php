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

<?php include("../includes/header.html"); ?>

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

  <div class="row g-4">
    <?php 
    $cars = [ ['name'=>"Porsche 911", "1963", "img4.jpg", "Buyable", "Porsche", "$101,000"], 
      ["Chevrolet El Camino SS", "1970", "img5.jpg", "Buyable", "Chevrolet", "$33,000"],   
      ["Chevrolet Corvette", "1963", "img10.jpg", "Buyable", "Chevrolet", "$80,000"],      
      ["BMW CSL", "1972", "img16.jpg", "Buyable", "BMW", "$216,000"],   
      ["Ford Thunderbird", "1971", "img13.jpg", "Buyable", "Ford", "$50,000"],
      ["Oldsmobile Starfire", "1962", "img6.jpg", "Buyable", "Oldsmobile", "$26,400"],
      ["British Corporation Mini", "1959", "img7.jpg", "Buyable", "Mini", "$31,000"],
      ["Jaguar XJS", "1989", "img14.jpg", "Buyable", "Jaguar", "$20,000"],
      ["Dodge Viper", "1991", "img15.jpg", "Buyable", "Dodge", "$60,000"],
      ["De Tomaso Pantera", "1970", "img17.jpg", "Buyable", "De Tomaso", "$125,000"],
      ["Land Rover", "1948", "img19.jpg", "Buyable", "Land Rover", "$169,000"],
      ["Volkswagen Beetle", "1938", "img20.jpg", "Buyable", "Volkswagen", "$18,000"],
      ["Chevrolet Bel-Air", "1957", "img23.jpg", "Buyable", "Chevrolet", "$75,000"]
     
  
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
        if ($car[3] === "Buyable") {
          $slug = strtolower(str_replace([' ', '-', '(', ')'], '', $car[0])); // e.g. Porsche 911 → porsche911
          echo '<div class="col-md-4">
          <div class="car-card">
            <a href="../car-details/' . strtolower(str_replace([" ", "-", ".", "/"], "", $car[0])) . '.php">
              <img src="../Images/' . $car[2] . '" alt="' . $car[0] . '">
            </a>
            <div class="car-card-body">
              <span class="badge bg-success badge-buyable mb-2">Buyable</span>
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