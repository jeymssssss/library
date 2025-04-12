<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}

$user_id = $_SESSION['user_id'];

// Filter & sort handling
$status_filter = $_GET['status'] ?? 'all';
$date_sort = $_GET['sort'] ?? 'desc';

$search_id = $_GET['search'] ?? '';

$query = "SELECT * FROM user_borrowed WHERE user_id = '$user_id'";

if (!empty($search_id)) {
  $query .= " AND borrow_id LIKE '%$search_id%'";
}

if ($status_filter !== 'all') {
  $query .= " AND status = '$status_filter'";
}

$query .= " ORDER BY date_placed " . ($date_sort === 'asc' ? 'ASC' : 'DESC');

$history_query = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>My History</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .card {
      border-radius: 1rem;
      box-shadow: 0 0 15px rgba(0,0,0,0.05);
    }
    .badge {
      font-size: 0.9rem;
    }
  </style>
</head>
<body>

<?php include 'user_header.php'; ?>
<?php include 'user_sidebar.php'; ?>

<div class="container mt-5">
  <div class="card p-4" style="margin-top: 80px;">
    <h3 class="mb-4 text-primary text-center">My History</h3>

    <!-- Filter + Sort Form -->
    <form method="get" class="row g-3 mb-4">
      <div class="col-md-4">
        <label class="form-label">Filter by Status:</label>
        <select name="status" class="form-select" onchange="this.form.submit()">
          <option value="all" <?= $status_filter === 'all' ? 'selected' : '' ?>>All</option>
          <option value="Pending" <?= $status_filter === 'Pending' ? 'selected' : '' ?>>Pending</option>
          <option value="Approved" <?= $status_filter === 'Approved' ? 'selected' : '' ?>>Approved</option>
          <option value="Collected" <?= $status_filter === 'Collected' ? 'selected' : '' ?>>Collected</option>
          <option value="Declined" <?= $status_filter === 'Declined' ? 'selected' : '' ?>>Declined</option>
          <option value="Returned" <?= $status_filter === 'Returned' ? 'selected' : '' ?>>Returned</option>
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label">Sort by Date:</label>
        <select name="sort" class="form-select" onchange="this.form.submit()">
          <option value="desc" <?= $date_sort === 'desc' ? 'selected' : '' ?>>Newest First</option>
          <option value="asc" <?= $date_sort === 'asc' ? 'selected' : '' ?>>Oldest First</option>
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label">Search by Borrow ID:</label>
        <input type="text" name="search" class="form-control" placeholder="Enter Borrow ID" value="<?= htmlspecialchars($search_id) ?>" oninput="this.form.submit()">
      </div>
    </form>

    <?php if (mysqli_num_rows($history_query) > 0): ?>
      <div class="table-responsive">
        <table class="table table-striped align-middle">
          <thead class="table-primary">
            <tr>
              <th scope="col">Borrow ID</th>
              <th scope="col">Books</th>
              <th scope="col">Total</th>
              <th scope="col">Status</th>
              <th scope="col">Date Requested</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($history_query)): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['borrow_id']); ?></td>
                <td><?php echo htmlspecialchars($row['book_names']); ?></td>
                <td><?php echo htmlspecialchars($row['total_books']); ?></td>
                <td>
                  <?php
                    $status = $row['status'];
                    $badge = match ($status) {
                      'Pending' => 'warning',
                      'Approved' => 'success',
                      'Collected' => 'info',
                      'Declined' => 'danger',
                      'Returned' => 'secondary',
                      default => 'secondary'
                    };
                  ?>
                  <span class="badge bg-<?php echo $badge; ?>"><?php echo $status; ?></span>

                  <?php if ($status === 'Returned'): ?>
                    <br>
                    <a href="survey.php?borrow_id=<?php echo $row['borrow_id']; ?>" class="btn btn-outline-primary btn-sm mt-2">Give Feedback</a>
                  <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($row['date_placed']); ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info text-center">No borrow requests found with this filter.</div>
    <?php endif; ?>

</div>

</body>
</html>
