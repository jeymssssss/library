<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT user_id, username, password, role, is_verified, email, full_name, first_login FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username_db, $password_db, $role, $is_verified, $email, $full_name, $first_login);
        $stmt->fetch();

        if (password_verify($password, $password_db)) {
            if ($is_verified == 1) {
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username_db;
                $_SESSION['role'] = $role;

                if ($first_login == 1) {
                    // Generate a 6-digit code
                    $otp = rand(100000, 999999);
                    $_SESSION['otp'] = $otp;
                    $_SESSION['otp_user_id'] = $id;

                    // Send OTP via email
                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host       = 'smtp.gmail.com';
                        $mail->SMTPAuth   = true;
                        $mail->Username   = 'lgulibrarymanagementsystem@gmail.com';
                        $mail->Password   = 'acwn mquy uqfh asyf'; // App Password
                        $mail->SMTPSecure = 'tls';
                        $mail->Port       = 587;

                        $mail->SMTPOptions = [
                            'ssl' => [
                                'verify_peer'       => false,
                                'verify_peer_name'  => false,
                                'allow_self_signed' => true,
                            ],
                        ];

                        $mail->setFrom('lgulibrarymanagementsystem@gmail.com', 'Library Admin');
                        $mail->addAddress($email, $full_name);
                        $mail->isHTML(true);
                        $mail->Subject = 'Your One-Time Code (OTP) for First Login';
                        $mail->Body    = "
                            <h3>Hello $full_name,</h3>
                            <p>Use the following code to complete your login:</p>
                            <h2>$otp</h2>
                            <p>This code is valid for 10 minutes.</p>";

                        $mail->send();

                        // Redirect to OTP page
                        header("Location: verify_otp.php");
                        exit();

                    } catch (Exception $e) {
                        echo "Mailer Error: " . $mail->ErrorInfo;
                        exit();
                    }
                } else {
                    // Not first login, proceed
                    if ($role == 'admin') {
                        $_SESSION['admin_name'] = $username_db;
                        header("Location: admin_dashboard.php");
                    } else {
                        header("Location: index.php");
                    }
                    exit();
                }
            } else {
                header("Location: login_error.php?error=unverified");
                exit();
            }
        } else {
            header("Location: login_error.php?error=invalid");
            exit();
        }
    } else {
        header("Location: login_error.php?error=invalid");
        exit();
    }
}
?>
