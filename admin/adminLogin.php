<?php
session_start();

// Generate CSRF token if not set
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>RetroRevive Admin Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    html, body {
      height: 100%;
      margin: 0;
    }

    body {
      display: flex;
      flex-direction: column;
      background: url('../Images/logIn.png') no-repeat center center fixed;
      background-size: cover;
      position: relative;
    }

    .main-content {
      flex: 1;
      position: relative;
      z-index: 1;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-container {
      background-color: rgba(255, 255, 255, 0.95);
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(0,0,0,0.3);
      max-width: 400px;
      width: 100%;
    }

    footer {
      background-color: #111;
      color: white;
      text-align: center;
      padding: 15px 0;
      z-index: 1;
      position: relative;
    }
  </style>
</head>
<body>

  <div class="main-content">
    <div class="login-container">
      <h3 class="text-center mb-4 text-dark">Admin Login</h3>
      <form action="handle-admin-login.php" method="POST">
        <div class="mb-3">
          <label for="email" class="form-label">Admin Email or Username</label>
          <input type="text" class="form-control" name="email" id="email" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Admin Password</label>
          <input type="password" class="form-control" name="password" id="password" required>
        </div>

        <!-- CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

         <button type="submit" class="btn btn-dark w-100">Login as Admin</button>
      </form>
    </div>
  </div>

</body>
</html>
