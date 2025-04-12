<?php
include 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the borrow_id from the URL
if (!isset($_GET['borrow_id'])) {
    echo "<script>alert('Invalid request.'); window.location.href = 'user_borrow_history.php';</script>";
    exit();
}

$borrow_id = $_GET['borrow_id'];

// Fetch the borrow request details
$query = mysqli_query($conn, "SELECT * FROM user_borrowed WHERE borrow_id = '$borrow_id' AND user_id = '{$_SESSION['user_id']}'");
$borrow_details = mysqli_fetch_assoc($query);

if (!$borrow_details) {
    echo "<script>alert('Borrow request not found.'); window.location.href = 'user_borrow_history.php';</script>";
    exit();
}

// Check if the status is 'Returned'
if ($borrow_details['status'] !== 'Returned') {
    echo "<script>alert('You can only provide feedback for returned books.'); window.location.href = 'user_borrow_history.php';</script>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Survey - Book Feedback</title>
    <link rel="icon" href="images/logo.png" type="image/icon type">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'user_header.php'; ?>
    <?php include 'user_sidebar.php'; ?>

    <div class="container mt-5">
        <div class="card p-4" style="margin-top: 80px;">
            <h3 class="text-center mb-4">Provide Your Feedback</h3>
            <p class="text-center">We’d love to hear about your experience with the book you borrowed.</p>

            <form method="POST" action="submit_survey.php">
                <input type="hidden" name="borrow_id" value="<?php echo $borrow_id; ?>">

                <div class="mb-3">
                    <label for="rating" class="form-label">Rate the book</label>
                    <select name="rating" id="rating" class="form-select" required>
                        <option value="1">1 - Poor</option>
                        <option value="2">2 - Fair</option>
                        <option value="3">3 - Good</option>
                        <option value="4">4 - Very Good</option>
                        <option value="5">5 - Excellent</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="comments" class="form-label">Comments</label>
                    <textarea name="comments" id="comments" class="form-control" rows="4" placeholder="Tell us about your experience with the book..." required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Submit Rating</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
