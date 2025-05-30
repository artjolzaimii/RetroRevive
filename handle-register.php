<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get and sanitize input
  $username = trim($_POST['username']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];

  // Basic validation
  if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    die(" All fields are required.");
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die(" Invalid email format.");
  }

  if ($password !== $confirm_password) {
    die(" Passwords do not match.");
  }

  // Check if email already exists
  $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
  $check_stmt->bind_param("s", $email);
  $check_stmt->execute();
  $check_stmt->store_result();

  if ($check_stmt->num_rows > 0) {
    $check_stmt->close();
    $conn->close();
    die(" Email already registered.");
  }

  $check_stmt->close();

  // Hash password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);
  

  // Insert user (role defaults to 'customer')
  $insert_stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'Customer')");
  $insert_stmt->bind_param("sss", $username, $email, $hashed_password);

  if ($insert_stmt->execute()) {
    // Auto login or redirect to login page
    $_SESSION['user_id'] = $insert_stmt->insert_id;
    $_SESSION['username'] = $username;
    $_SESSION['role'] = 'Customer';

    $insert_stmt->close();
    $conn->close();

    header("Location: index.php");
    exit();
  } else {
    $insert_stmt->close();
    $conn->close();
    die(" Something went wrong while registering.");
  }
}
?>
