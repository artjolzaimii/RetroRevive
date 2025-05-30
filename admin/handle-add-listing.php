<?php
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize inputs
    $car_id = intval($_POST['car_id']);
    $price = trim($_POST['price']);
    $condition = intval($_POST['condition']);
    $location = trim($_POST['location']);
    $contact_email = trim($_POST['contact_email']);

    $exterior_color = trim($_POST['exterior_color']);
    $interior_color = trim($_POST['interior_color']);
    $transmission = trim($_POST['transmission']);
    $mileage = trim($_POST['mileage']);
    $title_status = trim($_POST['title_status']);
    $restoration_status = trim($_POST['restoration_status']);
    $air_conditioning = ($_POST['air_conditioning'] === 'Yes') ? 'Yes' : 'No';
    $power_steering = ($_POST['power_steering'] === 'Yes') ? 'Yes' : 'No';
    $power_brakes = ($_POST['power_brakes'] === 'Yes') ? 'Yes' : 'No';
    $engine_condition = trim($_POST['engine_condition']);

    // Insert into car_listings
    $stmt = $conn->prepare("
        INSERT INTO car_listings (
            car_id, price, conditions, location, contact_email,
            exterior_color, interior_color, transmission, mileage, title_status,
            restoration_status, air_conditioning, power_steering, power_brakes, engine_condition
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "isissssssssssss",
        $car_id, $price, $condition, $location, $contact_email,
        $exterior_color, $interior_color, $transmission, $mileage, $title_status,
        $restoration_status, $air_conditioning, $power_steering, $power_brakes, $engine_condition
    );

    if ($stmt->execute()) {
        header("Location: listings.php?success=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
