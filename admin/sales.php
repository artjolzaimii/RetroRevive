<?php
include 'includes/admin-header.php';
include '../includes/db.php';

// Fetch all sales
$stmt = $conn->prepare("
    SELECT sales.*, users.username, users.email, cars.name AS car_name
    FROM sales
    JOIN users ON sales.user_id = users.id
    JOIN cars ON sales.car_id = cars.id
    ORDER BY sales.purchase_date DESC
");
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container my-5">
  <div class="bg-white p-4 rounded shadow">
    <h2 class="mb-4">Sales Transactions</h2>

    <div class="table-responsive">
      <table class="table table-hover table-bordered align-middle">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Car</th>
            <th>Price</th>
            <th>Purchase Date</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['username']) ?> (<?= htmlspecialchars($row['email']) ?>)</td>
                <td><?= htmlspecialchars($row['car_name']) ?></td>
                <td><?= $row['price'] ?></td>
                <td><?= date('Y-m-d H:i', strtotime($row['purchase_date'])) ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="text-center text-muted">No sales found yet.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div>
</div>

<?php include 'includes/admin-footer.php'; ?>
