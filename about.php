<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>About Us | RetroRevive</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body, html {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: 'Segoe UI', sans-serif;
    }

    .hero-section {
      background: url('Images/about.png') no-repeat center center/cover;
      height: 100vh;
      position: relative;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .overlay {
      position: absolute;
      top: 0; left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      z-index: 1;
    }

    .content {
      position: relative;
      z-index: 2;
      max-width: 700px;
      text-align: center;
      padding: 20px;
    }

    .content h1 {
      font-size: 3rem;
      margin-bottom: 20px;
      font-weight: 700;
    }

    .content p {
      font-size: 1.2rem;
      line-height: 1.8;
    }

    @media (max-width: 768px) {
      .content h1 {
        font-size: 2rem;
      }

      .content p {
        font-size: 1rem;
      }
    }
  </style>
</head>
<body>

<?php include("includes/header.html"); ?>

<section class="hero-section">
  <div class="overlay"></div>
  <div class="content">
    <h1>About RetroRevive</h1>
    <h3>Driven by Legacy</h3>
    <p>
      RetroRevive is more than a showroom — it's a time machine for automotive enthusiasts. 
      Our mission is to preserve the beauty, power, and elegance of history’s most iconic classic cars. 
      Whether you're looking to own a piece of history or simply admire the craftsmanship of decades past, 
      RetroRevive brings vintage dreams to life.
    </p>
  </div>
</section>

<?php include("includes/footer.html"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
