<?php
include 'includes/admin-header.php';
include '../includes/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid user ID.");
}

$user_id = intval($_GET['id']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_role = $_POST['role'];

    if ($new_role !== 'admin' && $new_role !== 'customer') {
        die("Invalid role selected.");
    }

    $update_stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $update_stmt->bind_param("si", $new_role, $user_id);

    if ($update_stmt->execute()) {
        header("Location: users.php?updated=1");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error updating user.</div>";
    }
}

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}
?>

<div class="container my-5">
  <div class="bg-white p-4 rounded shadow">
    <h2>Edit User Role</h2>
    <form method="POST" class="mt-4">
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" disabled>
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled>
      </div>

      <div class="mb-3">
        <label for="role" class="form-label">Role</label>
        <select class="form-select" name="role" id="role">
          <option value="customer" <?= $user['role'] === 'customer' ? 'selected' : '' ?>>Customer</option>
          <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary">Update Role</button>
      <a href="users.php" class="btn btn-secondary ms-2">Cancel</a>
    </form>
  </div>
</div>

<?php include 'includes/admin-footer.php'; ?>
