<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}

$user_id = $_SESSION['user_id'];
$book_id = $_GET['book_id'] ?? '';
$book_name = $_GET['book_name'] ?? '';

// Fetch user info
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$user_id'");
$user = mysqli_fetch_assoc($user_query);

// Optional: Fetch book details for display (not required unless you want to show more info)
$book_query = mysqli_query($conn, "SELECT * FROM books WHERE id = '$book_id'");
$book = mysqli_fetch_assoc($book_query);

// Handle form submission
if (isset($_POST['submit'])) {
  $name = $_POST['name'];
  $number = $_POST['number'];
  $email = $_POST['email'];
  $address = $_POST['address'];
  $quantity = $_POST['quantity']; // Get the quantity of books
  $book_ids = array_fill(0, $quantity, $_POST['book_id']);
  $book_names = array_fill(0, $quantity, $_POST['book_name']);
  $total_books = count($book_ids);
  $borrow_id = uniqid('BORROW-');
  $date_placed = date('Y-m-d');

  // Validate quantity - it should be between 1 and 3
  if ($quantity < 1 || $quantity > 3) {
    echo "<script>alert('You can borrow a maximum of 3 books.');</script>";
    exit();
  }

  $insert = mysqli_query($conn, "INSERT INTO user_borrowed 
    (borrow_id, user_id, name, number, email, address, total_books, book_names, date_placed, status) 
    VALUES 
    ('$borrow_id', '$user_id', '$name', '$number', '$email', '$address', '$total_books', '" . implode(', ', $book_names) . "', '$date_placed', 'Pending')");

  if ($insert) {
    echo "<script>alert('Borrow request submitted successfully!'); window.location.href = 'user_borrow_history.php';</script>";
    exit();
  } else {
    echo "<script>alert('Failed to submit request.');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Borrow Request</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'user_header.php'; ?>

<h2 style="margin-top: 80px;">Confirm Your Borrow Request</h2>

<form method="post" class="row g-3">
  <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
  <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($book_id); ?>">
  <input type="hidden" name="book_name" value="<?php echo htmlspecialchars($book_name); ?>">

  <div class="col-md-6">
    <label>Name</label>
    <input type="text" name="name" class="form-control" required value="<?php echo htmlspecialchars($user['full_name']); ?>" readonly>
  </div>
  <div class="col-md-6">
    <label>Username</label>
    <input type="text" name="username" class="form-control" required value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
  </div>
  <div class="col-md-6">
    <label>Phone Number</label>
    <input type="text" name="number" class="form-control" required value="<?php echo htmlspecialchars($user['phone_number']); ?>" readonly>
  </div>
  <div class="col-md-6">
    <label>Email</label>
    <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
  </div>
  <div class="col-md-12">
    <label>Address</label>
    <textarea name="address" class="form-control" required readonly><?php echo htmlspecialchars($user['address']); ?></textarea>
  </div>
  <div class="col-md-12">
    <label>Book Name</label>
    <input type="text" class="form-control" value="<?php echo htmlspecialchars($book_name); ?>" readonly>
  </div>
  <div class="col-md-12">
    <label>Quantity (Max 3)</label>
    <select name="quantity" class="form-select" required>
      <option value="1" <?php echo (isset($quantity) && $quantity == 1) ? 'selected' : ''; ?>>1</option>
      <option value="2" <?php echo (isset($quantity) && $quantity == 2) ? 'selected' : ''; ?>>2</option>
      <option value="3" <?php echo (isset($quantity) && $quantity == 3) ? 'selected' : ''; ?>>3</option>
    </select>
  </div>
  <div class="col-md-12">
    <button type="submit" name="submit" class="btn btn-primary">Confirm Borrow</button>
    <a href="books.php" class="btn btn-secondary">Cancel</a>
  </div>
</form>

</body>
</html>
