<?php
include 'config.php';

$user_id = $_SESSION['user_id'];

$notification_query = mysqli_query($conn, "SELECT * FROM user_borrowed WHERE user_id = '$user_id' AND status != 'Pending' AND notification_read = 0 ORDER BY date_approved DESC LIMIT 10");
$unread_count = mysqli_num_rows($notification_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Masambong Library</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


  <!-- Boxicons CSS -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

  <style>
    body {
      margin: 0;
      font-family: 'Roboto', sans-serif;
      background-color: #f4f7fc;
    }

    /* Header Styling */
    .header {
      background-color: #1B4965;
      color: #fff;
      padding: 15px 20px;
      display: flex;
      justify-content: flex-end;
      align-items: center;
      position: fixed;
      width: 100%;
      top: 0;
      z-index: 1000;
    }

    .header .logo {
      width: 30px; /* Set size of the logo */
      height: auto;
      margin-right: 15px;
      border-radius: 35px;
    }

  </style>
</head>
<body>

<!-- Header -->
<div class="header">

    <!-- Notification Bell -->
      <div class="position-relative me-3">
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#notificationModal">
          <i class="bi bi-bell"></i>
          <?php if ($unread_count > 0): ?>
              <span id="notification-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                  <?php echo $unread_count; ?>
              </span>
          <?php endif; ?>
        </button>
      </div>

      <!-- Notification Modal -->
      <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true" data-bs-backdrop="false">
        <div class="modal-dialog modal-dialog-scrollable modal-sm">
          <div class="modal-content">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title" id="notificationModalLabel">Notifications</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <!-- Inside Modal Body -->
            <?php if ($unread_count > 0): ?>
              <ul class="list-group list-group-flush">
                <?php while ($notif = mysqli_fetch_assoc($notification_query)): ?>
                  <li class="list-group-item">
                    <strong><?php echo htmlspecialchars($notif['status']); ?>:</strong>
                    Request ID <?php echo $notif['borrow_id']; ?> for <em><?php echo htmlspecialchars($notif['book_names']); ?></em><br>
                    <small class="text-muted">
                    </small>
                  </li>
                <?php endwhile; ?>
              </ul>
            <?php else: ?>
              <div class="text-muted text-center">No new notifications.</div>
<?php endif; ?>
            </div>
          </div>
        </div>
      </div>

  <!-- Library Logo (replace with your own logo image) -->
  <img src="images/logo.png" alt="Library Logo" class="logo">

  <!-- User Profile Dropdown -->
  <div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="bx bx-user"></i> User
    </button>
    <ul class="dropdown-menu" aria-labelledby="userDropdown">
      <li><a class="dropdown-item" href="profile_user.php">Profile</a></li>
      <li><hr class="dropdown-divider"></li>
      <li><a class="dropdown-item" href="logout.php">Logout</a></li>
    </ul>
  </div>
</div>

<!-- Bootstrap 5 JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// When the notification modal is shown, mark all notifications as read
const notificationModal = document.getElementById('notificationModal');

notificationModal.addEventListener('shown.bs.modal', function () {

    $.ajax({
      url: 'mark_notifications_read.php', // Call the PHP file to update the notifications
      type: 'POST',
      success: function () {
        $('#notification-badge').fadeOut(); // Smoothly remove badge
      },
      error: function () {
        console.log('Error marking notifications as read');
      }
    });
});

</script>




</body>
</html>
