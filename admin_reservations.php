<?php
include 'config.php';
session_start();

// Ensure the user is logged in as an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php'); // Redirect to login if not an admin
    exit();
}

// Fetch all reservation requests
$reservation_query = mysqli_query($conn, "SELECT * FROM user_reserved_books WHERE status = 'Pending'") or die(mysqli_error($conn));

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Manage Reservations</title>
  <link rel="icon" href="images/logo.png" type="image/icon type">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'admin_header.php'; ?>
<?php include 'admin_sidebar.php'; ?>

<h1 class="text-center mb-4" style="margin-top: 70px;">Manage Reservation Requests</h1>

<div class="container">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>#</th>
        <th>User</th>
        <th>Book</th>
        <th>Date Reserved</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if (mysqli_num_rows($reservation_query) > 0) {
        $counter = 1;
        while ($reservation = mysqli_fetch_assoc($reservation_query)) {
      ?>
        <tr>
          <td><?php echo $counter++; ?></td>
          <td><?php echo $reservation['user_id']; // You could fetch user details from the users table ?></td>
          <td><?php echo $reservation['book_name']; ?></td>
          <td><?php echo $reservation['date_reserved']; ?></td>
          <td><?php echo $reservation['status']; ?></td>
          <td>
            <a href="approve_reservation.php?reservation_id=<?php echo $reservation['reservation_id']; ?>" class="btn btn-success btn-sm">Approve</a>
            <a href="cancel_reservation.php?reservation_id=<?php echo $reservation['reservation_id']; ?>" class="btn btn-danger btn-sm">Cancel</a>
          </td>
        </tr>
      <?php
        }
      } else {
        echo '<tr><td colspan="6" class="text-center">No pending reservations</td></tr>';
      }
      ?>
    </tbody>
  </table>
</div>

</body>
</html>
