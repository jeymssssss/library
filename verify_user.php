<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Make sure you installed via Composer

include 'config.php';
session_start();

$admin_id = $_SESSION['admin_name'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit;
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $checkQuery = "SELECT is_verified, email, full_name FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($is_verified, $email, $full_name);
    $stmt->fetch();
    $stmt->close();

    if ($is_verified == 1) {
        header('location:user_credentials.php?msg=User is already verified');
        exit;
    }

    $query = "UPDATE users SET is_verified = 1 WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {

        // Send verification email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Enable for debug (optional)
            // $mail->SMTPDebug = 2;

            $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'lgulibrarymanagementsystem@gmail.com';
        $mail->Password   = 'acwn mquy uqfh asyf'; // Your actual app password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Skip SSL verification for localhost/dev
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
            ],
        ];

            // Sender
            $mail->setFrom('lgulibrarymanagementsystem@gmail.com', 'Library Admin');
            $mail->addReplyTo('lgulibrarymanagementsystem@gmail.com', 'Library Admin');

            // Recipient
            $mail->addAddress($email, $full_name);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Your Account is Now Verified';
            $mail->Body    = "
                <h3>Hello $full_name,</h3>
                <p>Your account has been successfully verified. You may now use the library system fully.</p>
                <p>Thank you for registering!</p>";

            // Additional headers (optional but can help spam scores)
            $mail->addCustomHeader('X-Mailer', 'PHPMailer');
            $mail->addCustomHeader('Content-Type', 'text/html; charset=UTF-8');

            $mail->send();
        } catch (Exception $e) {
            echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            exit;
        }

        header('location:user_credentials.php?msg=User successfully verified and email sent');
        exit;
    } else {
        echo 'Database error: ' . $stmt->error;
    }
}
?>
