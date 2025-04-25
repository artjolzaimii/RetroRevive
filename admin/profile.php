<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../includes/db.php';

$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email, profile_image FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .profile-container {
      max-width: 600px;
      margin: 80px auto;
      background: #fff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }

    .profile-img {
      width: 120px;
      height: 120px;
      object-fit: cover;
      border-radius: 50%;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

<?php include 'includes/admin-header.php'; ?>

<div class="container">
  <div class="profile-container text-center">
    <h3>Welcome, Admin <?= htmlspecialchars($user['username']) ?> 👑</h3>
    <img src="<?= $user['profile_image'] ?? '../Images/default-avatar.png' ?>" class="profile-img" alt="Profile Picture">
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    
    <a href="../logout.php" class="btn btn-danger mt-3">Logout</a>
  </div>
</div>

<?php include 'includes/admin-footer.php'; ?>
</body>
</html>
