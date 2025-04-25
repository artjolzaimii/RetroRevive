<?php
session_start();
include("../includes/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $car_id = $_POST['car_id'];
  $customer_name = trim($_POST['customer_name']);
  $customer_email = trim($_POST['customer_email']);
  $start_date = $_POST['start_date'];
  $end_date = $_POST['end_date'];
  $daily_rate = floatval($_POST['daily_rate']);

  // Validate dates
  if (strtotime($end_date) < strtotime($start_date)) {
    die("❌ End date must be after start date.");
  }

  // Calculate number of rental days
  $start = new DateTime($start_date);
  $end = new DateTime($end_date);
  $days = $start->diff($end)->days + 1;

  $total_price = $days * $daily_rate;

  // Insert into rentals table
  $stmt = $conn->prepare("INSERT INTO rentals (car_id, customer_name, customer_email, start_date, end_date, total_price) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("issssd", $car_id, $customer_name, $customer_email, $start_date, $end_date, $total_price);

  if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: book.php?success=1");
    exit();
  } else {
    $stmt->close();
    $conn->close();
    die("❌ Booking failed. Please try again.");
  }
} else {
  header("Location: book.php");
  exit();
}
