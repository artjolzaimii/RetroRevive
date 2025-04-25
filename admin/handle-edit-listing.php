<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id            = $_POST['id'];
  $car_id        = $_POST['car_id'];
  $price         = trim($_POST['price']);
  $condition     = (int)$_POST['condition'];
  $location      = trim($_POST['location']);
  $contact_email = trim($_POST['contact_email']);

  $stmt = $conn->prepare("UPDATE car_listings SET car_id=?, price=?, conditions=?, location=?, contact_email=? WHERE id=?");
  $stmt->bind_param("isissi", $car_id, $price, $condition, $location, $contact_email, $id);

  if ($stmt->execute()) {
    header("Location: listings.php?updated=1");
    exit();
  } else {
    echo "Error updating listing: " . $conn->error;
  }

  $stmt->close();
  $conn->close();
}
?>
