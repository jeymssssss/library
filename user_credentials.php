<?php
// Include config.php for database connection
include 'config.php';

session_start();
$admin_id = $_SESSION['admin_name'];

// Check if the admin is logged in
if (!isset($admin_id)) {
    header('location:login.php');
    exit;
}

// Query to fetch all users
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query) or die('Query failed: ' . mysqli_error($conn));
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Management</title>
  <link rel="icon" href="images/logo.png" type="image/icon type">
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom styles (optional) -->
  <link rel="stylesheet" href="styles/admin_styles.css">
  
</head>
<body>

<?php include 'admin_sidebar.php'; ?>
<?php include 'admin_header.php'; ?>

  <div class="container mt-5" style="padding-top: 40px;">
    <h1 class="text-center">User List</h1>

    <!-- Success/Error message -->
    <?php
    if (isset($_GET['msg'])) {
        echo '<div class="alert alert-success">' . $_GET['msg'] . '</div>';
    }
    ?>
    
    <div class="table-responsive">
  <table class="table table-striped table-bordered table-hover">
    <thead>
      <tr>
        <th>#</th>
        <th>Username</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Phone Number</th>
        <th>Age</th>
        <th>Address</th>
        <th>Created At</th>
        <th>Status</th>
        <th>ID Type</th>
        <th>Valid ID</th>
        <th>Last Login</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // Display all users in a table
      if (mysqli_num_rows($result) > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($result)) {
          $is_verified = $row['is_verified'];
      ?>
        <tr>
          <td class="text-center"><?php echo $count++; ?></td>
          <td class="text-nowrap"><?php echo htmlspecialchars($row['username']); ?></td>
          <td class="text-nowrap"><?php echo htmlspecialchars($row['full_name']); ?></td>
          <td class="text-nowrap"><?php echo htmlspecialchars($row['email']); ?></td>
          <td class="text-center"><?php echo htmlspecialchars($row['role']); ?></td>
          <td class="text-center"><?php echo htmlspecialchars($row['phone_number']); ?></td>
          <td class="text-center"><?php echo htmlspecialchars($row['age']); ?></td>
          <td class="text-nowrap"><?php echo htmlspecialchars($row['address']); ?></td>
          <td class="text-center"><?php echo date('Y-m-d H:i:s', strtotime($row['created_at'])); ?></td>
          <td class="text-center">
            <?php if ($row['is_verified'] == 1) { ?>
              <span class="badge bg-success">Verified</span>
            <?php } else { ?>
              <span class="badge bg-secondary">Not Verified</span>
            <?php } ?>
          </td>
          <td class="text-center"><?php echo htmlspecialchars($row['id_type']); ?></td>
          <td class="text-nowrap">
              <a href="<?php echo htmlspecialchars($row['valid_id']); ?>" target="_blank">
                  View ID
              </a>
          </td>
          <td class="text-center"><?php echo htmlspecialchars($row['last_login']); ?></td>

          <td class="text-center">
            <!-- Check if user is verified -->
            <?php if ($is_verified == 0) { ?>
              <a href="verify_user.php?id=<?php echo $row['user_id']; ?>" class="btn btn-success btn-sm">Verify</a>
            <?php } else { ?>
              <span class="text-success">Verified</span>
            <?php } ?>
          </td>
        </tr>
        
      <?php
        }
      } else {
        echo '<tr><td colspan="12" class="text-center">No users found.</td></tr>';
      }
      ?>
    </tbody>
  </table>
</div>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

  <!-- Status Update with AJAX -->
  <script>
    function updateStatus(userId, status) {
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "update_status.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          console.log("Status updated to: " + status);
        }
      };
      xhr.send("user_id=" + userId + "&status=" + status);
    }
  </script>

</body>
</html>
