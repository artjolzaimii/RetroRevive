<?php echo "Form is pointing to Formspree"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us | RetroRevive</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f4f4; /* fallback background color */
      margin: 0;
      padding: 0;
    }

    .contact-bg {
      background: url('Images/../Images/logIn.png') no-repeat center center fixed; 
      background-size: cover;
      min-height: 100vh; /* Full screen height */
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .contact-section {
      max-width: 700px;
      background: rgba(255, 255, 255, 0.95); /* semi-transparent white */
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

<?php include("includes/header.php"); ?>

<div class="contact-bg"> <!-- Background container -->
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
</div>

<?php include("includes/footer.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
