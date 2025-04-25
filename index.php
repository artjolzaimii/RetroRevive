<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>RetroRevive</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
    }

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

    .hero-overlay {
      position: absolute;
      top: 0; left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5); /* Darken the background image */
      z-index: 1;
    }

    .hero-content {
      position: relative;
      z-index: 2;
    }

    .section {
      padding: 80px 0;
    }

    .section h2 {
      font-size: 2.8rem;
      font-weight: bold;
      margin-bottom: 20px;
    }

    .section p {
      font-size: 1.1rem;
      color: #555;
      max-width: 800px;
      margin: auto;
    }

    .contact-form {
      max-width: 600px;
      margin: auto;
    }

    .bg-about {
      background: #f8f9fa;
    }

    .bg-contact {
      background: #e9ecef;
    }
  </style>
</head>
<body>

<?php include("includes/header.php"); ?>

<!-- HERO SECTION -->
<header class="hero">
  <div class="hero-overlay"></div>
  <div class="hero-content text-center">
    <h1 class="display-3 fw-bold">RetroRevive</h1>
    <p class="lead">Where timeless classics meet modern passion</p>
    <div class="mt-4">
      <a href="buysection/buy.php" class="btn btn-outline-light btn-lg me-3">Explore Collection</a>
      <a href="showroom/book.php" class="btn btn-light btn-lg">Book for Showroom</a>
    </div>
  </div>
</header>

<!-- ABOUT SECTION -->
<section class="section bg-about text-center">
  <h2>About RetroRevive</h2>
  <p>
    RetroRevive is your gateway to the timeless elegance and engineering marvels of vintage cars.
    Whether you’re here to buy a rare classic or showcase a legend at an event, we offer a carefully curated collection backed by passion and authenticity.
    Our mission is to connect enthusiasts and collectors with the most iconic vehicles in automotive history.
  </p>
</section>

<!-- CONTACT SECTION -->
<section class="section bg-contact text-center">
  <h2>Contact Us</h2>
  <p>If you have questions, need support, or want to partner with us — we’d love to hear from you.</p>

  <div class="contact-form mt-4">
    <form action="https://formspree.io/f/manoylvg" method="POST">
      <div class="mb-3">
        <input type="text" name="name" class="form-control" placeholder="Your Name" required>
      </div>
      <div class="mb-3">
        <input type="email" name="email" class="form-control" placeholder="Your Email" required>
      </div>
      <div class="mb-3">
        <textarea name="message" class="form-control" rows="5" placeholder="Your Message" required></textarea>
      </div>
      <input type="hidden" name="_redirect" value="thankyou.php">
      <button type="submit" class="btn btn-dark">Send Message</button>
    </form>
  </div>
</section>

<?php include("includes/footer.php"); ?>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
