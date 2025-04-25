<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// Get user info
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Handle profile info update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_info'])) {
  $newUsername = trim($_POST['username']);
  $newEmail = trim($_POST['email']);

  if (!empty($newUsername) && !empty($newEmail)) {
    $updateStmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
    $updateStmt->bind_param("ssi", $newUsername, $newEmail, $user_id);
    $updateStmt->execute();
    $updateStmt->close();

    $_SESSION['username'] = $newUsername;
    header("Location: profile.php");
    exit();
  }
}

// Fetch transactions
$sales = $conn->query("
  SELECT sales.*, cars.name AS car_name 
  FROM sales 
  JOIN cars ON sales.car_id = cars.id 
  WHERE sales.user_id = $user_id
");

$rentals = $conn->query("
  SELECT rentals.*, cars.name AS car_name 
  FROM rentals 
  JOIN cars ON rentals.car_id = cars.id 
  WHERE rentals.user_id = $user_id
");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>User Profile | RetroRevive</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f4f4f4;
      font-family: 'Segoe UI', sans-serif;
    }
    .profile-box {
      max-width: 900px;
      margin: 60px auto;
      background: white;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 0 12px rgba(0,0,0,0.1);
    }
    .profile-img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
    }
  </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="profile-box text-center">
  <h2 class="mb-3">Welcome, <?= htmlspecialchars($_SESSION['username']) ?></h2>

  <!-- Profile Picture Display -->
  <div class="mb-4">
  <img src="/RetroRevive/Images/profile.png" alt="Profile Picture" class="profile-img mb-2">

  </div>

  <!-- Profile Info Update -->
  <form method="POST" class="mb-5">
    <input type="hidden" name="update_info" value="1">
    <div class="row justify-content-center">
      <div class="col-md-5 mb-3">
        <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
      </div>
      <div class="col-md-5 mb-3">
        <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
      </div>
    </div>
    <button class="btn btn-outline-dark" type="submit">Update Info</button>
  </form>

  <!-- Purchase History -->
  <h4 class="text-start mt-5 mb-3">Purchase History</h4>
  <table class="table table-bordered">
    <thead class="table-dark">
      <tr>
        <th>Car ID</th>
        <th>Date</th>
        <th>Amount</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($sales && $sales->num_rows > 0): while ($row = $sales->fetch_assoc()): ?>
        <tr>
        <td><?= htmlspecialchars($row['car_name']) ?></td>
          <td><?= $row['purchase_date'] ?></td>
          <td><?= $row['amount'] ?></td>
        </tr>
      <?php endwhile; else: ?>
        <tr><td colspan="3">No purchases found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

  <!-- Rental History -->
  <h4 class="text-start mt-5 mb-3">Rental History</h4>
  <table class="table table-bordered">
    <thead class="table-dark">
      <tr>
        <th>Car ID</th>
        <th>From</th>
        <th>To</th>
        <th>Total Price</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($rentals && $rentals->num_rows > 0): while ($row = $rentals->fetch_assoc()): ?>
        <tr>
        <td><?= htmlspecialchars($row['car_name']) ?></td>
          <td><?= $row['start_date'] ?></td>
          <td><?= $row['end_date'] ?></td>
          <td><?= $row['total_price'] ?></td>
        </tr>
      <?php endwhile; else: ?>
        <tr><td colspan="4">No rentals found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

  <a href="logout.php" class="btn btn-danger mt-4">Logout</a>
</div>

<?php include 'includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
