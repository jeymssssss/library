<?php

session_start();

include 'config.php';
include 'admin_header.php';

$admin_id = $_SESSION['admin_name'];

if(!isset($admin_id)){
  header('location:login.php');
};

if(isset($_POST['add_book'])){
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $author = mysqli_real_escape_string($conn, $_POST['author']);
  $isbn = $_POST['isbn'];
  $books_count = $_POST['books_count'];  // Ensure books_count is retrieved here
  $image = $_FILES['image']['name'];
  $image_size = $_FILES['image']['size'];
  $image_tmp_name = $_FILES['image']['tmp_name'];
  $image_folder = 'uploaded_img/'.$image;

  $select_book_name = mysqli_query($conn, "SELECT name FROM `books` WHERE name = '$name'") or die ('Query failed');

  if(mysqli_num_rows($select_book_name) > 0){
    $message[] = 'Book name already added';
  }else{
    $add_book_query = mysqli_query($conn, "INSERT INTO `books` (name, author, isbn, books_count, image) VALUES ('$name', '$author', '$isbn', '$books_count', '$image')") or die ('Query failed');
    
    if($add_book_query){
      if($image_size > 2000000){
        $message[] = 'Image size is too large';
      }else{
        move_uploaded_file($image_tmp_name, $image_folder);
        $message[] = 'Book added successfully';
      }
    }else{
      $message[] = 'Book could not be added';
    }
  }
}

if(isset($_GET['delete'])){
  $delete_id = $_GET['delete'];
  $delete_image_query = mysqli_query($conn, "SELECT image FROM `books` WHERE id = '$delete_id'") or die('Query failed');
  $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
  unlink('uploaded_img/'.$fetch_delete_image['image']);
  mysqli_query($conn, "DELETE FROM `books` WHERE id = '$delete_id'") or die ('Query failed');
  header('location:admin_books.php');
}

if(isset($_POST['update_book'])){
  $update_b_id = $_POST['update_b_id'];
  $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
  $update_author = mysqli_real_escape_string($conn, $_POST['update_author']);
  $update_isbn = $_POST['update_isbn'];
  $update_books_count = $_POST['update_books_count']; // Ensure this is captured

  mysqli_query($conn, "UPDATE `books` SET name = '$update_name', author = '$update_author', isbn = '$update_isbn', books_count = '$update_books_count' WHERE id = '$update_b_id'") or die ('Query failed');

  $update_image = $_FILES['update_image']['name'];
  $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
  $update_image_size = $_FILES['update_image']['size'];
  $update_folder = 'uploaded_img/'.$update_image;
  $update_old_image = $_POST['update_old_img'];

  if(!empty($update_image)){
    if($update_image_size > 2000000){
      $message[] = 'Image file size is too large';
    }else{
      mysqli_query($conn, "UPDATE `books` SET image = '$update_image' WHERE id = '$update_b_id'") or die ('Query failed');
      move_uploaded_file($update_image_tmp_name, $update_folder);
      unlink('uploaded_img/'.$update_old_image);
    }
  }

  header('location:admin_books.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book Search</title>

  <link rel="stylesheet" href="styles/admin_styles.css">
  <link rel="icon" href="images/web_logo.png" type="image/png">

  <!-- BOX ICONS -->
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

  <style>
    .show-books {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 20px;
}

.add-books {
  margin-bottom: 20px;  /* Space between the form and the book items */
}

.box-container {
  display: flex;
  flex-wrap: wrap;  /* Ensure items wrap if they don’t fit in one line */
  justify-content: center;  /* Center items horizontally */
  gap: 20px;  /* Space between each box */
}

.box {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  border: 1px solid #ccc;
  padding: 15px;
  text-align: center;  /* Center text inside the box */
  width: 200px;  /* Fixed width for consistency */
  background-color: #f9f9f9;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.box img {
  width: 100%;  /* Make the image responsive */
  max-width: 150px;  /* Prevent the image from getting too large */
  border-radius: 5px;
}

  </style>

</head>
<body>
  
  <!-- SHOW BOOKS -->

  <section class="show-books">

    <section class="add-books">
      <form action="admin_search.php" method="GET">
        <input type="text" name="search" class="box" placeholder="Search">
        <input type="submit" value="Search" class="btn">
      </form>
    </section>

    <div class="box-container">
      

      <?php
        if (isset($_GET['search']) && !empty($_GET['search'])) {
          $search = mysqli_real_escape_string($conn, $_GET['search']);
          $select_book_query = mysqli_query($conn, "SELECT * FROM `books` WHERE name LIKE '%$search%' OR author LIKE '%$search%' OR tags LIKE '%$search%'") or die('Query failed');
        } else {
          $select_book_query = mysqli_query($conn, "SELECT * FROM `books`") or die('Query failed');
        }

        if(mysqli_num_rows($select_book_query) > 0){
          while($fetch_books = mysqli_fetch_assoc($select_book_query)){
      ?>
      <div class="box">
        <img src="uploaded_img/<?php echo $fetch_books['image']?>" alt="">
        <div class="name"><?php echo $fetch_books['name']?></div>
        <div class="tags"><?php echo $fetch_books['tags']?></div>
        <div class="author"><?php echo $fetch_books['author']?></div>
        <div class="books_count"><?php echo $fetch_books['books_count']; ?>x</div>
        <a href="admin_books.php?update=<?php echo $fetch_books['id']?>" class="option-btn">Update</a>
        <a href="admin_books.php?delete=<?php echo $fetch_books['id']?>" class="delete-btn" onclick="return confirm('Delete this book?')">Delete</a>
      </div>
      <?php
          }
        } else {
          echo '<p class="empty">No book found</p>';
        }
      ?>

    </div>
  </section>


  <!-- JAVASCRIPT-->
  <script src="js/admin_script.js"></script>
  
</body>
</html>