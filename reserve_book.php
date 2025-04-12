<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}

if (isset($_POST['id']) && isset($_POST['name'])) {
  $user_id = $_SESSION['user_id'];
  $book_id = $_POST['id'];
  $book_name = $_POST['name'];
  $reservation_id = uniqid('RESERVE-');
  $date_reserved = date('Y-m-d');

  // Check if the book exists and is available
  $book_query = mysqli_query($conn, "SELECT * FROM books WHERE id = '$book_id'") or die(mysqli_error($conn));
  if (mysqli_num_rows($book_query) > 0) {
    $book = mysqli_fetch_assoc($book_query);
    if ($book['books_count'] > 0) {
      // Reserve the book
      $reserve_query = "INSERT INTO user_reserved_books (reservation_id, user_id, book_id, book_name, date_reserved, status)
                        VALUES ('$reservation_id', '$user_id', '$book_id', '$book_name', '$date_reserved', 'Pending')";
      
      if (mysqli_query($conn, $reserve_query)) {
        // Optionally, you can also update the book count or add other actions as needed
        echo "<script>alert('Book reserved successfully!');</script>";
      } else {
        echo "<script>alert('Failed to reserve the book. Please try again.');</script>";
      }
    } else {
      echo "<script>alert('Sorry, this book is currently unavailable for reservation.');</script>";
    }
  } else {
    echo "<script>alert('Book not found.');</script>";
  }
} else {
  echo "<script>alert('Invalid request.');</script>";
}
?>
