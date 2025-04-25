<?php
session_start();
include '../includes/db.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from form
    $name        = trim($_POST['name']);
    $year        = trim($_POST['year']);
    $brand       = trim($_POST['brand']);
    $price       = trim($_POST['price']);
    $status      = $_POST['status'];
    $image       = trim($_POST['image']);
    $description = trim($_POST['description']);

    // Generate slug (e.g. "Ford Mustang" → "fordmustang")
    $slug = strtolower(str_replace([' ', '-', '.', ',', '(', ')', '/'], '', $name));

    // Prepare and execute insert statement
    $stmt = $conn->prepare("INSERT INTO cars (name, year, brand, price, status, image, description, slug) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $name, $year, $brand, $price, $status, $image, $description, $slug);

    if ($stmt->execute()) {
        header("Location: cars.php?success=1");
        exit();
    } else {
        echo "Error inserting data: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
