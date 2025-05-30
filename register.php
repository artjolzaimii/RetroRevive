<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - RetroRevive</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    html, body {
      height: 100%;
      margin: 0;
    }

    body {
      display: flex;
      flex-direction: column;
      background: url('Images/logIn.png') no-repeat center center fixed;
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
  margin-top: 80px; /* adjust this to match your header height */
}


    .register-container {
      background-color: rgba(255, 255, 255, 0.95);
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(0,0,0,0.3);
      max-width: 450px;
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
    <div class="register-container">
      <h3 class="text-center mb-4">Create Your Account</h3>
      <form action="handle-register.php" method="POST">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" class="form-control" name="username" id="username" required>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input type="email" class="form-control" name="email" id="email" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" name="password" id="password" required>
        </div>

        <div class="mb-3">
          <label for="confirm_password" class="form-label">Confirm Password</label>
          <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
        </div>

        <div class="mb-3 form-check">
          <input type="checkbox" class="form-check-input" id="terms" required>
          <label class="form-check-label" for="terms">I agree to the <a href="#">terms & conditions</a></label>
        </div>

        <button type="submit" class="btn btn-dark w-100">Register</button>

        <p class="mt-3 text-center text-muted">
          Already have an account? <a href="login.php">Login</a>
        </p>
      </form>
    </div>
  </div>

  

</body>
</html>
