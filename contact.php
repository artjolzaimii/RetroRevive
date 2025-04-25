<?php echo "Form is pointing to Formspree"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us | RetroRevive</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f4f4;
      font-family: 'Segoe UI', sans-serif;
    }

    .contact-section {
      max-width: 700px;
      margin: 80px auto;
      background: white;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    .contact-section h2 {
      text-align: center;
      margin-bottom: 30px;
    }
  </style>
</head>
<body>

<?php include("includes/header.html"); ?>

<div class="container">
  <div class="contact-section">
    <h2>Contact Us</h2>
    <form action="https://formspree.io/f/manoylvg" method="POST">
      <div class="mb-3">
        <label for="name" class="form-label">Your Name</label>
        <input type="text" class="form-control" name="name" id="name" required>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Your Email</label>
        <input type="email" class="form-control" name="email" id="email" required>
      </div>

      <div class="mb-3">
        <label for="message" class="form-label">Your Message</label>
        <textarea class="form-control" name="message" id="message" rows="5" required></textarea>
      </div>

      <!-- Optional: Redirect to your thank-you page -->
      <input type="hidden" name="_redirect" value="http://localhost/RetroRevive/thankyou.php" />

      <button type="submit" class="btn btn-dark">Send Message</button>
    </form>
  </div>
</div>

<?php include("includes/footer.html"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
