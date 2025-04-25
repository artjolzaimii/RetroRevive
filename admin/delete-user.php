<?php
include '../includes/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid user ID.");
}

$user_id = intval($_GET['id']);

// Prevent deletion of the currently logged-in admin (optional)
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user_id) {
    die("You cannot delete your own admin account while logged in.");
}

// Proceed with deletion
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    header("Location: users.php?deleted=1");
    exit();
} else {
    echo "Error deleting user.";
}
?>
