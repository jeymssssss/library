<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_name'])) {
    header('Location: login.php');
    exit();
}

// Default filter is set to "all"
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Build the query based on the selected filter
$query = "SELECT * FROM user_borrowed";

$status_filters = ['pending', 'approved', 'declined', 'reserved', 'borrowed'];

if (in_array($filter, $status_filters)) {
    if ($filter === 'borrowed') {
        $query .= " WHERE status = 'Approved'";
    } else {
        $query .= " WHERE status = '" . ucfirst($filter) . "'";
    }
}

$query .= " ORDER BY date_placed DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Transaction History - Library System</title>
  <link rel="icon" href="images/logo.png" type="image/icon type">

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Boxicons -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles/sidebar_styles.css">

  <style>
  table th, table td {
    text-align: center;
  }
  </style>
</head>
<body>

<!-- Inside <body> -->
<?php include 'admin_sidebar.php'; ?>
<?php include 'admin_header.php'; ?>

<div class="container mt-5 mb-5">
  <h2 class="mb-4 text-center" style="margin-top: 80px;">Transaction History</h2>

  <!-- Filter Form -->
  <form method="GET" action="transaction_history.php" class="mb-4">
    <div class="row">
      <div class="col-md-4">
        <label for="filter" class="form-label">Filter by</label>
        <select name="filter" id="filter" class="form-select">
        <option value="all" <?php echo ($filter === 'all') ? 'selected' : ''; ?>>All</option>
        <option value="pending" <?php echo ($filter === 'pending') ? 'selected' : ''; ?>>Pending</option>
        <option value="approved" <?php echo ($filter === 'approved') ? 'selected' : ''; ?>>Approved</option>
        <option value="declined" <?php echo ($filter === 'declined') ? 'selected' : ''; ?>>Declined</option>
        <option value="reserved" <?php echo ($filter === 'reserved') ? 'selected' : ''; ?>>Reserved</option>
        <option value="borrowed" <?php echo ($filter === 'borrowed') ? 'selected' : ''; ?>>Borrowed</option>
        <option value="returned" <?php echo ($filter === 'returned') ? 'selected' : ''; ?>>Returned</option>
      </select>
      </div>
      <div class="col-md-4">
        <button type="submit" class="btn btn-primary mt-4">Filter</button>
      </div>
    </div>
  </form>

  <?php if (mysqli_num_rows($result) > 0): ?>
    <table class="table table-bordered table-hover">
      <thead class="table-dark">
        <tr>
          <th>Borrow ID</th>
          <th>User</th>
          <th>Books</th>
          <th>Total Books</th>
          <th>Status</th>
          <th>Date Requested</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['borrow_id']); ?></td>
            <td>
              <?php
                $user_id = $row['user_id'];
                $user_query = mysqli_query($conn, "SELECT full_name FROM users WHERE user_id = '$user_id'");
                $user = mysqli_fetch_assoc($user_query);
                echo htmlspecialchars($user['full_name']);
              ?>
            </td>
            <td><?php echo htmlspecialchars($row['book_names']); ?></td>
            <td><?php echo htmlspecialchars($row['total_books']); ?></td>
            <td>
            <?php
              $status = $row['status'];
              $badge = match ($status) {
                  'Pending' => 'warning',
                  'Approved' => 'primary',
                  'Collected' => 'success',
                  'Declined' => 'danger',
                  'Reserved' => 'info',
                  'Returned' => 'secondary',
                  default => 'light'
              };
            ?>
            <span class="badge bg-<?php echo $badge; ?>"><?php echo $status; ?></span>
            </td>
            <td><?php echo htmlspecialchars($row['date_placed']); ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info">No transactions found.</div>
  <?php endif; ?>

</div>

<!-- Bootstrap JS (for optional interactive features) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
