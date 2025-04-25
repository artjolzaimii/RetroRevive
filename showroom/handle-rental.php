<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car_id = $_POST['car_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        die("You must be logged in to book a rental.");
    }

    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    $interval = $start->diff($end);
    $days = $interval->days;

    if ($days <= 0) {
        die("End date must be after start date.");
    }

    // Get car daily rate
    $stmt = $conn->prepare("SELECT daily_rate FROM cars WHERE id = ?");
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $car = $result->fetch_assoc();

    if (!$car) {
        die("Car not found.");
    }

    $total_price = $car['daily_rate'] * $days;

    var_dump($user_id, $car_id, $start_date, $end_date, $total_price);


    // Insert into rentals table
    $insert = $conn->prepare("INSERT INTO rentals (user_id, car_id, start_date, end_date, total_price) VALUES (?, ?, ?, ?, ?)");



    if (!$insert) {
        die("Prepare failed: " . $conn->error);
    }
    
    $insert->bind_param("iisss", $user_id, $car_id, $start_date, $end_date, $total_price);

    if ($insert->execute()) {
        header("Location: thank-you.php");
        exit();
    } else {
        echo "Error processing rental.";
    }
}
?>
