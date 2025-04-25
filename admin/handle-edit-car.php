<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id          = $_POST['id'];
    $name        = trim($_POST['name']);
    $year        = trim($_POST['year']);
    $brand       = trim($_POST['brand']);
    $price       = trim($_POST['price']);
    $status      = $_POST['status'];
    $image       = trim($_POST['image']);
    $description = trim($_POST['description']);
    $slug        = strtolower(str_replace([' ', '-', '.', ',', '(', ')', '/'], '', $name));

    $stmt = $conn->prepare("UPDATE cars SET name=?, year=?, brand=?, price=?, status=?, image=?, description=?, slug=? WHERE id=?");
    $stmt->bind_param("ssssssssi", $name, $year, $brand, $price, $status, $image, $description, $slug, $id);

    if ($stmt->execute()) {
        header("Location: cars.php?updated=1");
        exit();
    } else {
        echo "Error updating car: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
