<?php
session_start();

include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the user details
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Query to get user information from the database
$sql = "SELECT full_name, email, first_login, address, phone_number FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($full_name, $email, $first_login, $address, $phone_number);
$stmt->fetch();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'admin_header.php'; ?>

    <div class="container mt-5">
        <h2 style="margin-top: 80px;">User Profile</h2>
        <div class="card">
            <div class="card-header">
                User Details
            </div>
            <div class="card-body">
                <h5 class="card-title">Hello, <?php echo htmlspecialchars($full_name); ?>!</h5>
                <p class="card-text"><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
                <p class="card-text"><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                <p class="card-text"><strong>Role:</strong> <?php echo htmlspecialchars($role); ?></p>
                <p class="card-text"><strong>First Login:</strong> <?php echo $first_login == 1 ? 'Yes' : 'No'; ?></p>
                <p class="card-text"><strong>Address:</strong> <?php echo htmlspecialchars($address); ?></p>
                <p class="card-text"><strong>Phone Number:</strong> <?php echo htmlspecialchars($phone_number); ?></p>

                <?php if ($role == 'admin'): ?>
                    <p class="card-text"><strong>Admin Access:</strong> Yes</p>
                <?php else: ?>
                    <p class="card-text"><strong>User Access:</strong> Yes</p>
                <?php endif; ?>
                
                <a href="admin_dashboard.php" class="btn btn-danger">Return to dashboard</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
