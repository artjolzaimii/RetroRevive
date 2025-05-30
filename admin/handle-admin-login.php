<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // CSRF token check
  if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Invalid CSRF token.");
  }

  $emailOrUsername = trim($_POST['email']);
  $password = $_POST['password'];

  // Query for admin user (assuming you have a `role` or `is_admin` column in your users table)
  $stmt = $conn->prepare("SELECT * FROM users WHERE (email = ? OR username = ?) AND role = 'Admin' LIMIT 1");
  $stmt->bind_param("ss", $emailOrUsername, $emailOrUsername);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $admin = $result->fetch_assoc();
    if (password_verify($password, $admin['password'])) {
      // Login successful
      $_SESSION['admin_id'] = $admin['id'];
      $_SESSION['admin_username'] = $admin['username'];

      header("Location: dashboard.php");
      exit();
    } else {
      // Wrong password
      echo "❌ Invalid credentials.";
    }
  } else {
    // No admin found
    echo "❌ Admin not found.";
  }

  $stmt->close();
}
?>
