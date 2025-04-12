<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $borrow_id = $_POST['borrow_id'];
    $email = $_POST['user_email'];
    $name = $_POST['user_name'];
    $book_titles = $_POST['book_titles'];
    $total_books = intval($_POST['total_books']);

    // Update user_borrowed table
    $stmt = $conn->prepare("UPDATE user_borrowed SET status = 'Returned', date_returned = NOW() WHERE borrow_id = ?");
    $stmt->bind_param("i", $borrow_id);
    $stmt->execute();
    $stmt->close();

    // Update book quantities
    $books = explode(", ", $book_titles);
    foreach ($books as $book_title) {
        $book_title = trim($book_title);
        $stmt = $conn->prepare("UPDATE books SET books_count = books_count + 1 WHERE name = ?");
        $stmt->bind_param("s", $book_title);
        $stmt->execute();
        $stmt->close();
    }

    // Send Thank You Email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'lgulibrarymanagementsystem@gmail.com';
        $mail->Password   = 'acwn mquy uqfh asyf'; // App password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];

        $mail->setFrom('lgulibrarymanagementsystem@gmail.com', 'Library Admin');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Thank You for Returning the Books';
        $mail->Body    = "
            <h3>Hello $name,</h3>
            <p>Thank you for returning the book(s): <strong>$book_titles</strong>.</p>
            <p>We hope you had a great reading experience!</p>
            <p>Sincerely,<br>Library Team</p>
        ";

        $mail->send();
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        exit;
    }

    header("Location: transaction_history.php?msg=Book marked as returned");
    exit;
}
?>
