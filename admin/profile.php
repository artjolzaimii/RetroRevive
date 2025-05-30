<?php
session_start();
include '../includes/db.php';

// Check if logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user info
$stmt = $conn->prepare("SELECT username, email, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $newUsername = trim($_POST['username']);
  $newEmail = trim($_POST['email']);

  // File upload
  if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
    $filename = "profile_" . $user_id . "_" . time() . "." . $ext;
    $uploadPath = "../Images/" . $filename;

    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadPath)) {
      // Update with profile picture
      $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, profile_picture = ? WHERE id = ?");
      $stmt->bind_param("sssi", $newUsername, $newEmail, $uploadPath, $user_id);
      $stmt->execute();
      $stmt->close();

      $_SESSION['username'] = $newUsername; // Update session
      header("Location: profile.php?updated=1");
      exit();
    }
  } else {
    // Update without profile picture
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $newUsername, $newEmail, $user_id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['username'] = $newUsername; // Update session
    header("Location: profile.php?updated=1");
    exit();
  }
}
?>

<?php include 'includes/admin-header.php'; ?>

<div class="container my-5">
  <div class="bg-white p-4 rounded shadow text-center">
    <h2 class="mb-4">Admin Profile</h2>

    <!-- Display profile picture -->
    <?php if ($user['profile_picture']): ?>
      <img src="<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile Picture" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
    <?php else: ?>
      <img src="../Images/profile.png" alt="Profile Picture" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
    <?php endif; ?>

    <!-- Update form -->
    <form method="POST" enctype="multipart/form-data" class="text-start mx-auto" style="max-width: 400px;">
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Profile Picture</label>
        <input type="file" name="profile_picture" class="form-control">
      </div>
      <button type="submit" class="btn btn-dark w-100">Update Profile</button>
    </form>

    <?php if (isset($_GET['updated'])): ?>
      <div class="alert alert-success mt-3">Profile updated successfully!</div>
    <?php endif; ?>
  </div>
</div>

<?php include 'includes/admin-footer.php'; ?>
