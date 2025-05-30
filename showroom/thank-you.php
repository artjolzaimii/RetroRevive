<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Thank You | RetroRevive</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f4f4f4;
      font-family: 'Segoe UI', sans-serif;
    }

    .thank-you-box {
      max-width: 600px;
      margin: 100px auto;
      background-color: white;
      padding: 40px;
      text-align: center;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .thank-you-box h1 {
      font-size: 2.5rem;
      margin-bottom: 20px;
      color: #198754;
    }

    .thank-you-box p {
      font-size: 1.1rem;
      color: #555;
    }

    .thank-you-box a {
      margin-top: 20px;
    }
  </style>
</head>
<body>

<?php include("../includes/header.php"); ?>

<div class="thank-you-box">
  <h1>Thank You!</h1>
  <p>Your booking has been received successfully.</p>
  <p>We’ll reach out to confirm the schedule and details soon.</p>
  <a href="/RetroRevive/index.php" class="btn btn-dark mt-3">Back to Home</a>
</div>

<?php include("../includes/footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>