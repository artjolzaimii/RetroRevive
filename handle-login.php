<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['email']); // This can now be username or email
    $password = $_POST['password'];

    if (empty($identifier) || empty($password)) {
        die("Email or username and password are required.");
    }

    // Query to check for username OR email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("ss", $identifier, $identifier);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'Admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            die("Incorrect password.");
        }
    } else {
        die("No user found with that email or username.");
    }

    $stmt->close();
    $conn->close();
}
?>