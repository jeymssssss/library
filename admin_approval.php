<?php
include 'config.php';
session_start();

require 'vendor/autoload.php'; // Move outside to be available regardless of condition
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$user_id = $_SESSION['user_id'];

// Fetch all borrow requests
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'All';

$valid_statuses = ['Pending', 'Approved', 'Collected', 'Declined', 'All'];
$status_filter_safe = in_array($status_filter, $valid_statuses) ? $status_filter : 'Pending';

if ($status_filter_safe === 'All') {
  $history_query = mysqli_query($conn, "SELECT * FROM user_borrowed ORDER BY date_placed DESC");
} else {
  $history_query = mysqli_query($conn, "SELECT * FROM user_borrowed WHERE status = '$status_filter_safe' ORDER BY date_placed DESC");
}


// Handle status update for borrow request
if (isset($_GET['action']) && isset($_GET['borrow_id'])) {
  $borrow_id = $_GET['borrow_id'];
  $action = $_GET['action'];

  if (in_array($action, ['approve', 'Declined'])) {
    $status = $action === 'approve' ? 'Approved' : 'Declined';

    $borrow_query = mysqli_query($conn, "SELECT * FROM user_borrowed WHERE borrow_id = '$borrow_id'");
    $borrow_details = mysqli_fetch_assoc($borrow_query);

    if ($action === 'approve') {
      $status = 'Approved';
      $approval_date = date('Y-m-d H:i:s'); // Get the current date and time
    
      $borrow_query = mysqli_query($conn, "SELECT * FROM user_borrowed WHERE borrow_id = '$borrow_id'");
      $borrow_details = mysqli_fetch_assoc($borrow_query);
    
      if ($borrow_details) {
        $book_names = explode(', ', $borrow_details['book_names']);
        $total_books = $borrow_details['total_books'];
    
        foreach ($book_names as $book_name) {
          $book_query = mysqli_query($conn, "SELECT * FROM books WHERE name = '$book_name'");
          $book = mysqli_fetch_assoc($book_query);
    
          if ($book) {
            $new_quantity = $book['books_count'] - 1;
            if ($new_quantity >= 0) {
              mysqli_query($conn, "UPDATE books SET books_count = $new_quantity WHERE name = '$book_name'");
            } else {
              echo "<script>alert('Not enough stock available for the requested book(s).'); window.location.href = 'admin_approval.php';</script>";
              exit();
            }
          }
        }
    
        // Send email notification
        $user_email = $borrow_details['email'];
        $user_name = $borrow_details['name'];
        $book_list = $borrow_details['book_names'];
        $borrow_id = $borrow_details['borrow_id'];
    
        $mail = new PHPMailer(true);
        try {
          $mail->isSMTP();
          $mail->Host = 'smtp.gmail.com';
          $mail->SMTPAuth = true;
          $mail->Username = 'lgulibrarymanagementsystem@gmail.com';
          $mail->Password = 'acwn mquy uqfh asyf';
          $mail->SMTPSecure = 'tls';
          $mail->Port = 587;
    
          $mail->SMTPOptions = [
            'ssl' => [
              'verify_peer' => false,
              'verify_peer_name' => false,
              'allow_self_signed' => true,
            ],
          ];
    
          $mail->setFrom('lgulibrarymanagementsystem@gmail.com', 'Library Admin');
          $mail->addReplyTo('lgulibrarymanagementsystem@gmail.com', 'Library Admin');
          $mail->addAddress($user_email, $user_name);
    
          $mail->isHTML(true);
          $mail->Subject = 'Your Book Borrow Request Has Been Approved';
          $mail->Body = "
            <h3>Hello $user_name,</h3>
            <p>We’re pleased to let you know that your borrow request (ID: <strong>$borrow_id</strong>) has been <strong>approved</strong>.</p>
            <p>You may now collect the following book(s):</p>
            <p><strong>$book_list</strong></p>
            <p>Please proceed to the library front desk within 3 days to pick up your books.</p>
            <br>
            <small>Thank you,<br>Library Admin</small>
          ";
    
          $mail->send();
        } catch (Exception $e) {
          error_log("Email Error: {$mail->ErrorInfo}");
        }
    
        // Update borrow request status and add the approval date
        $update_query = mysqli_query($conn, "UPDATE user_borrowed SET status = '$status', date_approved = '$approval_date' WHERE borrow_id = '$borrow_id'");
        if ($update_query) {
          echo "<script>alert('Request status updated to $status'); window.location.href = 'admin_approval.php';</script>";
          exit();
        } else {
          echo "<script>alert('Failed to update status.'); window.location.href = 'admin_approval.php';</script>";
        }
      }
    } else if ($action === 'Declined') {

      $status = 'Declined';
      
      // Send email notification for cancellation
      $user_email = $borrow_details['email'];
      $user_name = $borrow_details['name'];
      $book_list = $borrow_details['book_names'];
      $borrow_id = $borrow_details['borrow_id'];
    
      $mail = new PHPMailer(true);
      try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'lgulibrarymanagementsystem@gmail.com';
        $mail->Password = 'acwn mquy uqfh asyf';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
    
        $mail->SMTPOptions = [
          'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
          ],
        ];
    
        $mail->setFrom('lgulibrarymanagementsystem@gmail.com', 'Library Admin');
        $mail->addReplyTo('lgulibrarymanagementsystem@gmail.com', 'Library Admin');
        $mail->addAddress($user_email, $user_name);
    
        $mail->isHTML(true);
        $mail->Subject = 'Your Book Borrow Request Has Been Declined';
        $mail->Body = "
          <h3>Hello $user_name,</h3>
          <p>We regret to inform you that your borrow request (ID: <strong>$borrow_id</strong>) has been <strong>Declined</strong>.</p>
          <p>The requested book(s): <strong>$book_list</strong></p>
          <p>If you believe this was a mistake or have questions, please contact the library admin.</p>
          <br>
          <small>Thank you,<br>Library Admin</small>
        ";
    
        $mail->send();
      } catch (Exception $e) {
        error_log("Cancellation Email Error: {$mail->ErrorInfo}");
      }
    }
 
    // Update request status
    $declined_date = date('Y-m-d H:i:s');
    $update_query = mysqli_query($conn, "UPDATE user_borrowed SET status = '$status', date_declined = '$declined_date' WHERE borrow_id = '$borrow_id'");
    if ($update_query) {
      echo "<script>alert('Request status updated to $status'); window.location.href = 'admin_approval.php';</script>";
      exit();
    } else {
      echo "<script>alert('Failed to update status.'); window.location.href = 'admin_approval.php';</script>";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Admin Dashboard - Manage Borrow Requests</title>
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

<form method="GET" class="mb-4 d-flex justify-content-center align-items-center gap-2" style="margin-top: 90px;">
  <label for="status" class="form-label m-0">Filter by Status:</label>
  <select name="status" id="status" class="form-select w-auto" onchange="this.form.submit()">
    <?php
      $statuses = ['Pending', 'Approved', 'Collected', 'Declined']; // You can add more status options if needed
      foreach ($statuses as $status) {
        $selected = ($status_filter === $status) ? 'selected' : '';
        echo "<option value=\"$status\" $selected>$status</option>";
      }
    ?>
  </select>
</form>


<div class="container mt-5">
  
  <div class="card p-4" style="margin-top: 80px;">
    <h3 class="mb-4 text-center text-primary"> Manage Borrow Requests</h3>

    <?php if (mysqli_num_rows($history_query) > 0): ?>
      <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th scope="col">Borrow ID</th>
              <th scope="col">User</th>
              <th scope="col">Books</th>
              <th scope="col">Quantity</th>
              <th scope="col">Status</th>
              <th scope="col">Date Requested</th>
              <th scope="col">Decision Date</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($history_query)): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['borrow_id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
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
                      default => 'secondary'
                    };
                  ?>
                  <span class="badge bg-<?php echo $badge; ?>"><?php echo $status; ?></span>
                </td>
                </td>
                <td><?php echo htmlspecialchars($row['date_placed']); ?></td>
                <td>
                  <?php
                    if ($row['status'] === 'Approved') {
                      echo htmlspecialchars($row['date_approved']);
                    } elseif ($row['status'] === 'Declined') {
                      echo htmlspecialchars($row['date_declined']);
                    } else {
                      echo '<span class="text-muted">N/A</span>';
                    }
                  ?>
                </td>
                <td>
                  <?php if ($row['status'] === 'Pending'): ?>
                    <a href="?action=approve&borrow_id=<?php echo $row['borrow_id']; ?>" class="btn btn-outline-success btn-sm">Approve</a>
                    <a href="?action=Declined&borrow_id=<?php echo $row['borrow_id']; ?>" class="btn btn-outline-danger btn-sm">Declined</a>
                  <?php else: ?>
                    <span class="text-muted">No action</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info text-center">No borrow requests at the moment.</div>
    <?php endif; ?>
    
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
