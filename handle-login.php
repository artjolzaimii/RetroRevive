<?php
session_start();
include 'includes/db.php'; 

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  // Prepare SQL to prevent SQL injection
  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  // Check if user exists
  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
      // Login successful, set session
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      $_SESSION['role'] = $user['role'];

      // Redirect (for now, send both to homepage)
      header("Location: index.php");
      exit();
    } else {
      echo " Incorrect password.";
    }
  } else {
    echo " No user found with that email.";
  }

  $stmt->close();
  $conn->close();
}
?>
