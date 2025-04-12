<?php
include 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $borrow_id = $_POST['borrow_id'];
    $rating = $_POST['rating'];
    $comments = mysqli_real_escape_string($conn, $_POST['comments']);

    // Insert the feedback into the database
    $query = "INSERT INTO book_ratings (borrow_id, rating, comments) VALUES ('$borrow_id', '$rating', '$comments')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Thank you for your feedback!'); window.location.href = 'user_borrow_history.php';</script>";
    } else {
        echo "<script>alert('Error submitting feedback. Please try again later.'); window.location.href = 'survey.php?borrow_id=$borrow_id';</script>";
    }
}
?>
