<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $car_id        = $_POST['car_id'];
  $price         = trim($_POST['price']);
  $condition     = (int)$_POST['condition'];
  $location      = trim($_POST['location']);
  $contact_email = trim($_POST['contact_email']);

  $stmt = $conn->prepare("INSERT INTO car_listings (car_id, price, conditions, location, contact_email) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("isiss", $car_id, $price, $condition, $location, $contact_email);

  if ($stmt->execute()) {
    header("Location: listings.php?success=1");
    exit();
  } else {
    echo "Error: " . $conn->error;
  }

  $stmt->close();
  $conn->close();
}
?>
