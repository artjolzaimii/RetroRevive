<?php
include 'includes/db.php';

$username = "artjol";
$email = "artjol@retrorevive.com";
$password = "artjol123"; // choose a strong one later

$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$role = "admin";

// Check if admin already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "Admin already exists.";
} else {
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);
    
    if ($stmt->execute()) {
        echo " Admin user created successfully!";
    } else {
        echo " Error creating admin: " . $stmt->error;
    }
}

$stmt->close();
$conn->close();
?>
