<?php
include 'config.php';

session_start();
$user_id = $_SESSION['user_id']; // Get the logged-in user ID

// Update notifications as read
$query = "UPDATE user_borrowed SET notification_read = 1 WHERE user_id = '$user_id' AND notification_read = 0";
$result = mysqli_query($conn, $query);

if ($result) {
    echo 'Notifications marked as read';
} else {
    echo 'Failed to mark notifications as read';
}
?>
