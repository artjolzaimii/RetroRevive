<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>RetroRevive</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/css/style.css" />
  <style>
      .hero {
    background: url("Images/background.png") no-repeat center center/cover;
    height: 100vh;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    overflow: hidden;
    text-shadow: 1px 1px 5px rgba(0,0,0,0.7);
  }
  </style>
</head>
<body>

  <?php include("includes/header.html"); ?>

  <!-- HERO SECTION -->
  <header class="hero">
  <div class="hero-overlay"></div>
  <div class="hero-content text-center">
    <h1 class="display-3 fw-bold animate-fade-in">RetroRevive</h1>
    <p class="lead animate-fade-in delay-1">Where timeless classics meet modern passion</p>
    <div class="mt-4 animate-fade-in delay-2">
      <a href="buysection/buy.php" class="btn btn-outline-light btn-lg me-3">Explore Collection</a>
      <a href="showroom/book.php" class="btn btn-light btn-lg">Book for Showroom</a>
    </div>
  </div>
</header>



  <?php include("includes/footer.html"); ?>

  <!-- Bootstrap Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
