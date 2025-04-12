<?php
// Include config.php for database connection
include 'config.php';

// Check if the status is passed in the URL
$status = isset($_GET['status']) ? $_GET['status'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Verified</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

  <div class="container mt-5">
    <?php if ($status == 'success') { ?>
      <div class="alert alert-success text-center" role="alert">
        <h4 class="alert-heading">User Verified Successfully!</h4>
        <p>The user has been verified and is now active.</p>
        <hr>
        <a href="user_credentials.php" class="btn btn-primary">Go back to User Management</a>
      </div>
    <?php } else { ?>
      <div class="alert alert-danger text-center" role="alert">
        <h4 class="alert-heading">Verification Failed</h4>
        <p>There was an error verifying the user. Please try again.</p>
        <hr>
        <a href="user_credentials.php" class="btn btn-primary">Go back to User Management</a>
      </div>
    <?php } ?>
  </div>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
