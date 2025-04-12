<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php'); // Redirect to login if not logged in
  exit();
}

if (isset($_POST['borrow_request'])) {
  include 'config.php'; // just in case it's not loaded yet
  $user_id = $_POST['user_id'];
  $book_name = mysqli_real_escape_string($conn, $_POST['book_name']);
  $book_id = $_POST['book_id'];
  $borrow_id = uniqid('BORROW-');
  $date_placed = date('Y-m-d');

  $book_query = mysqli_query($conn, "SELECT * FROM books WHERE id='$book_id'") or die(mysqli_error($conn));

  if (empty($book_id)) {
    echo "<script>alert('No book ID received.');</script>";
  }  


  if (mysqli_num_rows($book_query) > 0) {
    $book = mysqli_fetch_assoc($book_query);

    if ($book['books_count'] > 0) {
      // Optional user info
      $user_query = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$user_id' AND role = 'user'");
      $user = mysqli_fetch_assoc($user_query);
      $username = $user['username'];
      $full_name = $user['full_name'];
      $phone_number = $user['phone_number'];
      $email = $user['email'];
      $address = $user['address'];

      // Insert into user_borrowed
      mysqli_query($conn, "INSERT INTO user_borrowed (borrow_id, user_id, name, number, email, method, address, total_books, book_names, date_placed, status)
        VALUES ('$borrow_id', '$user_id', '$full_name', '$phone_number', '$email', '$address', 1, '$book_name', '$date_placed', 'Pending')")
        or die('Insert Failed');

      echo "<script>alert('Borrow request submitted successfully!');</script>";
    } else {
      echo "<script>alert('Sorry, this book is currently unavailable.');</script>";
    }
  } else {
    echo "<script>alert('Book not found.');</script>";
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Masambong Library - Books</title>
  <link rel="icon" href="images/logo.png" type="image/icon type">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>

</style>
</head>
<body>

<?php include 'user_header.php'; ?>
<?php include 'user_sidebar.php'; ?>

<h1 class="text-center mb-4" style="margin-top: 70px;">Available Books</h1>

<form method="GET" class="row g-2 mb-4 justify-content-center">
  <div class="col-md-4">
    <input type="text" name="search" class="form-control" placeholder="Search books..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
  </div>
  <div class="col-md-3">
    <select name="filter" class="form-select">
      <option value="name" <?php if (isset($_GET['filter']) && $_GET['filter'] == 'name') echo 'selected'; ?>>Name</option>
      <option value="author" <?php if (isset($_GET['filter']) && $_GET['filter'] == 'author') echo 'selected'; ?>>Author</option>
      <option value="tags" <?php if (isset($_GET['filter']) && $_GET['filter'] == 'tags') echo 'selected'; ?>>Tags</option>
      <option value="format" <?php if (isset($_GET['filter']) && $_GET['filter'] == 'format') echo 'selected'; ?>>Format</option>
      <option value="genre" <?php if (isset($_GET['filter']) && $_GET['filter'] == 'genre') echo 'selected'; ?>>Genre</option>
      <option value="dewey_decimal" <?php if (isset($_GET['filter']) && $_GET['filter'] == 'dewey_decimal') echo 'selected'; ?>>Dewey Decimal</option>
    </select>
  </div>
  <div class="col-md-2">
    <button type="submit" class="btn btn-primary w-100">Search</button>
  </div>
</form>

<div class="p-4 border rounded bg-light" style="max-height: 500px; overflow-y: auto;">
  <div class="row g-4">
    <?php
    $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'name';

    if (!empty($search)) {
      $allowed_filters = ['name', 'author', 'tags', 'format', 'genre', 'dewey_decimal'];
      if (in_array($filter, $allowed_filters)) {
        $_select_books = mysqli_query($conn, "SELECT * FROM `books` WHERE `$filter` LIKE '%$search%'") or die('Query failed');
      } else {
        $_select_books = mysqli_query($conn, "SELECT * FROM `books`") or die('Query failed');
      }
    } else {
      $_select_books = mysqli_query($conn, "SELECT * FROM `books`") or die('Query failed');
    }
    ?>
    <?php
    if (mysqli_num_rows($_select_books) > 0) {
      while ($fetch_books = mysqli_fetch_assoc($_select_books)) {
    ?>
    <div class="col-md-4">
      <div class="card shadow-lg rounded border-0 overflow-hidden h-100">
        <img src="uploaded_img/<?php echo $fetch_books['image']; ?>" class="card-img-top" alt="Book Cover" style="height: 250px; object-fit: cover;">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title text-truncate"><?php echo $fetch_books['name']; ?></h5>
          <p class="card-text text-muted">
            <strong>Author:</strong> <?php echo $fetch_books['author']; ?><br>
            <strong>Genre:</strong> <?php echo $fetch_books['genre']; ?><br>
            <strong>Available:</strong> 
            <?php 
              if ($fetch_books['books_count'] > 0) {
                echo $fetch_books['books_count']; 
              } else {
                echo "<span class='text-danger'>Unavailable</span>"; 
              }
            ?>
          </p>

          <div class="mt-auto">
            <button type="button" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#viewMoreModal"
              data-book-id="<?php echo $fetch_books['id']; ?>"
              data-book-name="<?php echo $fetch_books['name']; ?>"
              data-book-author="<?php echo $fetch_books['author']; ?>"
              data-book-tags="<?php echo $fetch_books['tags']; ?>"
              data-book-genre="<?php echo $fetch_books['genre']; ?>"
              data-book-pages="<?php echo $fetch_books['pages']; ?>"
              data-book-format="<?php echo $fetch_books['format']; ?>"
              data-book-count="<?php echo $fetch_books['books_count']; ?>"
              data-book-description="<?php echo $fetch_books['description']; ?>"
              data-book-image="uploaded_img/<?php echo $fetch_books['image']; ?>"
            >
              View Details
            </button>
          </div>
        </div>
      </div>
    </div>
    <?php
      }
    } else {
      echo '<p class="text-center w-100">No books found.</p>';
    }
    ?>
  </div>
</div>

<!-- Modal for Book Details -->
<div class="modal fade" id="viewMoreModal" tabindex="-1" aria-labelledby="viewMoreModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewMoreModalLabel">Book Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-5">
            <img src="" id="modal-book-image" class="img-fluid" alt="Book Image">
          </div>
          <div class="col-md-7">
            <h5 id="modal-book-name"></h5>
            <p><strong>Author:</strong> <span id="modal-book-author"></span></p>
            <p><strong>Tags:</strong> <span id="modal-book-tags"></span></p>
            <p><strong>Genre:</strong> <span id="modal-book-genre"></span></p>
            <p><strong>Pages:</strong> <span id="modal-book-pages"></span></p>
            <p><strong>Format:</strong> <span id="modal-book-format"></span></p>
            <p><strong>Available:</strong> <span id="modal-book-count"></span></p>
            <p><strong>Description:</strong> <span id="modal-book-description"></span></p>
          </div>
        </div>
      </div>
      <div class="modal-footer d-flex justify-content-between">
        <!-- Borrow Form -->
        <form method="GET" action="borrow_request.php" class="d-inline">
          <input type="hidden" name="book_id" id="borrow-book-id">
          <input type="hidden" name="book_name" id="borrow-book-name">
          <button type="submit" class="btn btn-primary" id="borrow-button" disabled>Borrow This Book</button>
        </form>

        <!-- Cancel Button -->
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
    </div>
</div>

<script>
  var viewMoreButtons = document.querySelectorAll('[data-bs-toggle="modal"]');

viewMoreButtons.forEach(function(button) {
  button.addEventListener('click', function() {
    var bookName = this.getAttribute('data-book-name');
    var bookAuthor = this.getAttribute('data-book-author');
    var bookTags = this.getAttribute('data-book-tags');
    var bookGenre = this.getAttribute('data-book-genre');
    var bookPages = this.getAttribute('data-book-pages');
    var bookFormat = this.getAttribute('data-book-format');
    var bookCount = this.getAttribute('data-book-count'); // Get book count
    var bookDescription = this.getAttribute('data-book-description');
    var bookImage = this.getAttribute('data-book-image');
    var bookId = this.getAttribute('data-book-id');

    document.getElementById('modal-book-name').innerText = bookName;
    document.getElementById('modal-book-author').innerText = bookAuthor;
    document.getElementById('modal-book-tags').innerText = bookTags;
    document.getElementById('modal-book-genre').innerText = bookGenre;
    document.getElementById('modal-book-pages').innerText = bookPages;
    document.getElementById('modal-book-format').innerText = bookFormat;
    document.getElementById('modal-book-count').innerText = bookCount;
    document.getElementById('modal-book-description').innerText = bookDescription;
    document.getElementById('modal-book-image').src = bookImage;

    // Set hidden inputs for form
    document.getElementById('borrow-book-name').value = bookName;
    document.getElementById('borrow-book-id').value = bookId;

    // Enable/Disable Borrow Button based on Availability
    var borrowButton = document.getElementById('borrow-button');
    if (bookCount > 0) {
      borrowButton.disabled = false; // Enable if available
    } else {
      borrowButton.disabled = true; // Disable if unavailable
    }
  });
});

</script>


</body>
</html>
