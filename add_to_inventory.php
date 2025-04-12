<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$book_id = $_POST['book_id'];
$book_name = mysqli_real_escape_string($conn, $_POST['book_name']);

// Check if the book is already in the user's inventory
$check = mysqli_query($conn, "SELECT * FROM user_inventory WHERE user_id = '$user_id' AND book_id = '$book_id'");
if (mysqli_num_rows($check) > 0) {
  $existing = mysqli_fetch_assoc($check);
  $current_quantity = $existing['quantity'];

  if ($current_quantity < 3) {
    $new_quantity = $current_quantity + 1;
    mysqli_query($conn, "UPDATE user_inventory SET quantity = '$new_quantity' WHERE user_id = '$user_id' AND book_id = '$book_id'");
    echo "<script>alert('Book quantity increased in your inventory!'); window.history.back();</script>";
  } else {
    echo "<script>alert('Maximum of 3 copies already in inventory.'); window.history.back();</script>";
  }
} else {
  mysqli_query($conn, "INSERT INTO user_inventory (user_id, book_id, book_name, quantity) VALUES ('$user_id', '$book_id', '$book_name', 1)");
  echo "<script>alert('Book added to your inventory!'); window.history.back();</script>";
}
?>
