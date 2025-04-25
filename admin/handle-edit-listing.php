<?php
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $car_id = $_POST['car_id'];
    $price = $_POST['price'];
    $conditions = $_POST['condition'];
    $location = $_POST['location'];
    $contact_email = $_POST['contact_email'];

    // Extended fields
    $exterior_color = $_POST['exterior_color'];
    $interior_color = $_POST['interior_color'];
    $transmission = $_POST['transmission'];
    $mileage = $_POST['mileage'];
    $title_status = $_POST['title_status'];
    $restoration_status = $_POST['restoration_status'];
    $engine_condition = $_POST['engine_condition'];

    // Handle checkboxes (they are only sent if checked)
    $air_conditioning = isset($_POST['air_conditioning']) ? 'Yes' : 'No';
    $power_steering = isset($_POST['power_steering']) ? 'Yes' : 'No';
    $power_brakes = isset($_POST['power_brakes']) ? 'Yes' : 'No';

    $stmt = $conn->prepare("UPDATE car_listings SET 
        car_id = ?, price = ?, conditions = ?, location = ?, contact_email = ?, 
        exterior_color = ?, interior_color = ?, transmission = ?, mileage = ?, 
        title_status = ?, restoration_status = ?, air_conditioning = ?, 
        power_steering = ?, power_brakes = ?, engine_condition = ?
        WHERE id = ?");

    $stmt->bind_param("isdssssssssssssi", 
        $car_id, $price, $conditions, $location, $contact_email,
        $exterior_color, $interior_color, $transmission, $mileage,
        $title_status, $restoration_status, $air_conditioning,
        $power_steering, $power_brakes, $engine_condition, $id
    );

    if ($stmt->execute()) {
        header("Location: listings.php?updated=1");
        exit();
    } else {
        echo "Error updating listing: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}
?>
