<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// Get user info, including profile picture
$stmt = $conn->prepare("SELECT username, email, profile_picture FROM users WHERE id = ?");
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

// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_picture'])) {
  if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    $imgTmp = $_FILES['profile_picture']['tmp_name'];
    $imgName = basename($_FILES['profile_picture']['name']);
    $imgPath = 'Images/profile_' . $user_id . '_' . time() . '_' . $imgName;
    move_uploaded_file($imgTmp, $imgPath);

    $updatePicStmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
    $updatePicStmt->bind_param("si", $imgPath, $user_id);
    $updatePicStmt->execute();
    $updatePicStmt->close();

    header("Location: profile.php");
    exit();
  }
}

// Rental history
$rentals = $conn->query("
  SELECT rentals.*, cars.name AS car_name 
  FROM rentals 
  JOIN cars ON rentals.car_id = cars.id 
  WHERE rentals.user_id = $user_id
");

// Determine which profile picture to show
$profilePic = !empty($user['profile_picture']) ? $user['profile_picture'] : '/RetroRevive/Images/profile.png';
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
      border: 4px solid #1c1c1c;
      object-fit: cover;
      transition: transform 0.3s;
    }
    .profile-img:hover {
      transform: scale(1.05);
    }
  </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="profile-box text-center">
  <h2 class="mb-3">Welcome, <?= htmlspecialchars($_SESSION['username']) ?></h2>

  <!-- Profile Picture Display -->
  <div class="mb-4">
    <img src="<?= htmlspecialchars($profilePic) ?>" alt="Profile Picture" class="profile-img mb-2">
  </div>

  <!-- Profile Picture Upload -->
  <form method="POST" enctype="multipart/form-data" class="mb-4">
    <input type="hidden" name="update_picture" value="1">
    <div class="mb-3">
      <label for="profilePicture" class="form-label">Update Profile Picture:</label>
      <input type="file" class="form-control" name="profile_picture" id="profilePicture" accept="image/*">
    </div>
    <button class="btn btn-outline-dark" type="submit">Upload</button>
  </form>

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

  <!-- Rental History -->
  <h4 class="text-start mt-5 mb-3">Rental History</h4>
  <table class="table table-bordered">
    <thead class="table-dark">
      <tr>
        <th>Car</th>
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
