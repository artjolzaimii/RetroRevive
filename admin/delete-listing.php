<?php
session_start();
include '../includes/db.php';

if ($_GET['id']) {
  $id = $_GET['id'];

  $stmt = $conn->prepare("DELETE FROM car_listings WHERE id = ?");
  $stmt->bind_param("i", $id);

  if ($stmt->execute()) {
    header("Location: listings.php?deleted=1");
    exit();
  } else {
    echo "Error deleting listing: " . $conn->error;
  }

  $stmt->close();
  $conn->close();
}
?>
