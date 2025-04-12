<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input_otp = $_POST['otp'];

    if ($_SESSION['otp'] == $input_otp) {
        $user_id = $_SESSION['otp_user_id'];
        $update = $conn->prepare("UPDATE users SET first_login = 0 WHERE user_id = ?");
        $update->bind_param("i", $user_id);
        $update->execute();

        $role = $_SESSION['role'];
        if ($role == 'admin') {
            $_SESSION['admin_name'] = $_SESSION['username'];
            header("Location: admin_dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        $error = "Invalid code. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OTP Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .otp-container {
            max-width: 400px;
            margin: 80px auto;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #86b7fe;
        }
    </style>
</head>
<body>

<div class="otp-container">
    <h4 class="text-center mb-4">Enter OTP</h4>
    <form method="POST">
        <div class="mb-3">
            <label for="otp" class="form-label">6-Digit Code</label>
            <input type="text" name="otp" id="otp" class="form-control" required maxlength="6" pattern="\d{6}" placeholder="Enter the code here">
        </div>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?= $error ?>
            </div>
        <?php endif; ?>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Verify Code</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
