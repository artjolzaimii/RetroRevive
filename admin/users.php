<?php
include '../includes/db.php';

session_start();
$current_user_role = $_SESSION['role'] ?? '';

// Handle AJAX search
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_search'])) {
    $search = '%' . trim($_POST['ajax_search']) . '%';
    $stmt = $conn->prepare("SELECT * FROM users WHERE username LIKE ? OR email LIKE ? ORDER BY id DESC");
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo '<tr>
                <td>' . $row['id'] . '</td>
                <td>' . htmlspecialchars($row['username']) . '</td>
                <td>' . htmlspecialchars($row['email']) . '</td>
                <td><span class="badge bg-' . ($row['role'] === 'admin' ? 'primary' : 'secondary') . '">' . ucfirst($row['role']) . '</span></td>
                <td>';
        if ($current_user_role === 'admin') {
            echo '<a href="edit-user.php?id=' . $row['id'] . '" class="btn btn-sm btn-warning">Edit</a>
                  <a href="delete-user.php?id=' . $row['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</a>';
        } else {
            echo '<span class="text-muted">Restricted</span>';
        }
        echo '</td></tr>';
    }
    exit();
}
?>

<?php
include 'includes/admin-header.php';

// Default data
$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<div class="container my-5">
  <div class="bg-white p-4 rounded shadow">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">User Management</h2>
      <?php if ($current_user_role === 'admin'): ?>
        <a href="add-user.php" class="btn btn-success">+ Add New User</a>
      <?php endif; ?>
    </div>

    <input type="text" id="userSearchInput" class="form-control mb-3" placeholder="Search by username or email...">

    <div class="table-responsive">
      <table class="table table-hover table-bordered">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="usersContainer">
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['username']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><span class="badge bg-<?= $row['role'] === 'admin' ? 'primary' : 'secondary' ?>"><?= ucfirst($row['role']) ?></span></td>
              <td>
                <?php if ($current_user_role === 'admin'): ?>
                  <a href="edit-user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                  <a href="delete-user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                <?php else: ?>
                  <span class="text-muted">Restricted</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
document.getElementById("userSearchInput").addEventListener("keyup", function () {
  const query = this.value;
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onload = function () {
    if (xhr.status === 200) {
      document.getElementById("usersContainer").innerHTML = xhr.responseText;
    }
  };
  xhr.send("ajax_search=" + encodeURIComponent(query));
});
</script>

<?php include 'includes/admin-footer.php'; ?>
