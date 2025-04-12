<?php
include 'config.php';
session_start();

$book_id = $_POST['book_id'];

// Ensure session ID exists
if (!isset($_SESSION['session_id'])) {
    $_SESSION['session_id'] = session_create_id('sess_');
}
$session_id = $_SESSION['session_id'];

// Check due date
$due_query = mysqli_query($conn, "SELECT due_date FROM borrowed_books WHERE book_id = '$book_id' AND session_id = '$session_id'") or die('Query failed');
$due_date = mysqli_fetch_assoc($due_query)['due_date'];

if (strtotime($due_date) < time()) {
    $fine_amount = 5.00; // Example fine amount per overdue book
    mysqli_query($conn, "INSERT INTO fines (session_id, fine_reason, fine_amount) 
                         VALUES ('$session_id', 'Overdue Book Fine', '$fine_amount')") or die('Query failed');
}

echo "Book Returned Successfully!";
?>
