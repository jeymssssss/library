<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

// Fetch all approved borrow requests
$history_query = mysqli_query($conn, "SELECT * FROM user_borrowed WHERE status = 'Approved' ORDER BY date_placed DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Admin Dashboard - Approved Borrowed Books</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="images/logo.png" type="image/icon type">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .card {
      border-radius: 1rem;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    .badge {
      font-size: 14px;
    }
  </style>
</head>
<body>

<?php include 'admin_header.php'; ?>
<?php include 'admin_sidebar.php'; ?>

<div class="container mt-5">
  
  <div class="card p-4" style="margin-top: 80px;">
    <h3 class="mb-4 text-center text-primary"> Approved Borrowed Books</h3>

    <?php if (mysqli_num_rows($history_query) > 0): ?>
      <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th scope="col">Borrow ID</th>
              <th scope="col">User</th>
              <th scope="col">Books</th>
              <th scope="col">Quantity</th>
              <th scope="col">Date Requested</th>
              <th scope="col">Date Approved</th>
              <th scope="col">Date Returned</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($history_query)): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['borrow_id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['book_names']); ?></td>
                <td><?php echo htmlspecialchars($row['total_books']); ?></td>
                <td><?php echo htmlspecialchars($row['date_placed']); ?></td>
                <td><?php echo htmlspecialchars($row['date_approved']); ?></td>
                <td>
                <form action="process_return.php" method="post" onsubmit="return confirm('Mark as returned?');">
                  <input type="hidden" name="borrow_id" value="<?php echo $row['borrow_id']; ?>">
                  <input type="hidden" name="user_email" value="<?php echo $row['email']; ?>">
                  <input type="hidden" name="user_name" value="<?php echo $row['name']; ?>">
                  <input type="hidden" name="book_titles" value="<?php echo $row['book_names']; ?>">
                  <input type="hidden" name="total_books" value="<?php echo $row['total_books']; ?>">
                  <button type="submit" class="btn btn-success btn-sm">Returned</button>
                </form>
              </td>
                            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info text-center">No approved borrow requests at the moment.</div>
    <?php endif; ?>
    
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
