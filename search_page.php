<?php

include 'config.php';

session_start();

// Generate a unique session ID if not already set
if (!isset($_SESSION['session_id'])) {
  $_SESSION['session_id'] = session_create_id('sess_');
}

$session_id = $_SESSION['session_id']; // Assign session ID


if(isset($_POST['add_to_inventory'])){  

  $name = $_POST['name'];
  $book_image = $_POST['book_image'];
  $book_quantity = $_POST['book_quantity'];

  $check_reservations_numbers = mysqli_query($conn, "SELECT * FROM `reservations` WHERE name = '$name' and session_id = '$session_id'") or die('Query failed');

  if(mysqli_num_rows($check_reservations_numbers) > 0){
    $message[] = 'Already added to';
  }else{
    mysqli_query($conn, "INSERT INTO `reservations`(session_id, name, quantity, image) VALUES('$session_id', '$name', '$book_quantity', '$book_image')") or die('Query failed');
    $message[] = 'Book added';
  }

}

if (isset($_GET['type'])) {
  $type = $_GET['type'];

  if ($type == 'books') {
      // Fetch books data
      $sql = "SELECT name, author, tags, books_count, pages, format, genre FROM books";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
          // Output data for books
          while($row = $result->fetch_assoc()) {
              echo "Name: " . $row['name'] . "<br>";
              echo "Author: " . $row['author'] . "<br>";
              echo "Tags: " . $row['tags'] . "<br>";
              echo "Books Count: " . $row['books_count'] . "<br>";
              echo "Pages: " . $row['pages'] . "<br>";
              echo "Format: " . $row['format'] . "<br>";
              echo "Genre: " . $row['genre'] . "<br><br>";
          }
      } else {
          echo "No books found.";
      }
  } elseif ($type == 'users') {
      // Fetch users data
      $sql = "SELECT full_name, email, phone_number, address FROM users";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
          // Output data for users
          while($row = $result->fetch_assoc()) {
              echo "Full Name: " . $row['full_name'] . "<br>";
              echo "Email: " . $row['email'] . "<br>";
              echo "Phone Number: " . $row['phone_number'] . "<br>";
              echo "Address: " . $row['address'] . "<br><br>";
          }
      } else {
          echo "No users found.";
      }
  } else {
      echo "Invalid type parameter.";
  }
} else {
  echo "No type specified.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search</title>

    <!-- BOX ICONS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="styles/styles.css">
    <link rel="icon" href="images/web_logo.png" type="image/icon type">
    
</head>
<body>

<?php include 'header.php';?>

<?php include 'sidebar.php'; ?>

  <section class="search-books">
    
    <form action="" method="post">
      <select name="search_option" required>
        <option value="name">Book Name</option>
        <option value="author">Author</option>
        <option value="pages">Book Pages</option>
        <option value="genre">Genre</option>
        <option value="format">Format</option>
      </select>
      <input type="text" name="search" placeholder="Search" class="box">
      <input type="submit" name="submit" value="search" class="btn">
    </form>

  </section>

  <section class="books" style="padding-top: 0;">

    <div class="box-container">

    <?php
      if (isset($_POST['submit'])) {
          $search_item = mysqli_real_escape_string($conn, $_POST['search']);
          $search_option = mysqli_real_escape_string($conn, $_POST['search_option']);

          // Update SQL query to use the sanitized variable
          $_select_books = mysqli_query($conn, "SELECT * FROM `books` WHERE $search_option LIKE '%$search_item%'") or die('Query failed');

          if (mysqli_num_rows($_select_books) > 0) {
              while ($fetch_books = mysqli_fetch_assoc($_select_books)) {
    ?>
            <form action="" method="post" class="box">
                <img src="uploaded_img/<?php echo htmlspecialchars($fetch_books['image']); ?>" alt="">
                <div class="name"><?php echo htmlspecialchars($fetch_books['name']); ?></div>
                <div class="genre"><?php echo htmlspecialchars($fetch_books['genre']); ?></div>
                <div class="format"><?php echo htmlspecialchars($fetch_books['format']); ?></div>
                <div class="pages"><?php echo htmlspecialchars($fetch_books['pages']); ?></div>
                <div class="books_count"><?php echo htmlspecialchars($fetch_books['books_count']); ?>x</div>
                <div class="author"><?php echo htmlspecialchars($fetch_books['author']); ?></div>
                <div class="tags"><?php echo htmlspecialchars($fetch_books['tags']); ?></div>
                <input type="number" min="1" name="book_quantity" value="1" class="quantity">
                <input type="hidden" name="name" value="<?php echo htmlspecialchars($fetch_books['name']); ?>">
                <input type="hidden" name="book_image" value="<?php echo htmlspecialchars($fetch_books['image']); ?>">
                <input type="submit" value="Add to" name="add_to_inventory" class="btn">
            </form>

        <?php
            }
        } else {
            echo '<p class="empty">No books found</p>';
        }
    }

?>


    </div>

  </section>

  <!-- JS FILE -->
   <script src="js/script.js"></script> 
  
</body>
</html>