  <?php
  session_start();
  include 'config.php';

  if (!isset($_SESSION['admin_name'])) {
      header('location:login.php');
      exit();
  }

  $admin_id = $_SESSION['admin_name'];
  

  if (isset($_POST['add_book'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $tags = mysqli_real_escape_string($conn, $_POST['tags']);
    $books_count = mysqli_real_escape_string($conn, $_POST['books_count']);
    $pages = mysqli_real_escape_string($conn, $_POST['pages']);
    $format = mysqli_real_escape_string($conn, $_POST['format']);
    $genre = mysqli_real_escape_string($conn, $_POST['genre']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $dewey_decimal = mysqli_real_escape_string($conn, $_POST['dewey_decimal']);

    // Handle file upload for book image
    $image = $_FILES['image']['name'];
    $image_temp = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;

    if (move_uploaded_file($image_temp, $image_folder)) {
        // Insert the book data into the database
        $insert_query = "INSERT INTO `books` (`name`, `author`, `tags`, `books_count`, `pages`, `format`, `genre`, `description`, `dewey_decimal`, `image`) 
                         VALUES ('$name', '$author', '$tags', '$books_count', '$pages', '$format', '$genre', '$description', '$dewey_decimal', '$image')";
        $insert_result = mysqli_query($conn, $insert_query);

        if ($insert_result) {
            echo "<script>alert('Book added successfully!');</script>";
        } else {
            echo "<script>alert('Error adding book.');</script>";
        }
    } else {
        echo "<script>alert('Image upload failed.');</script>";
    }
}

if (isset($_GET['delete'])) {
  $delete_id = $_GET['delete'];
  $delete_query = mysqli_query($conn, "DELETE FROM `books` WHERE `id` = '$delete_id'") or die('Query failed');
  if ($delete_query) {
      echo "<script>alert('Book deleted successfully!');</script>";
      header("Location: admin_books.php");
  } else {
      echo "<script>alert('Error deleting book.');</script>";
  }
}

  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Manage Books | Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="images/logo.png" type="image/icon type">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
      html, body {
        height: 100%;
        overflow-y: auto;
      }

      .card img {
        max-height: 200px;
        object-fit: cover;
      }
      .form-section {
        margin-top: 30px;
      }
      .scrollable-books-section {
        max-height: 600px;
        overflow-y: auto;
        padding-right: 10px;
        scrollbar-width: thin;
      }

      .scrollable-books-section::-webkit-scrollbar {
        width: 8px;
      }

      .scrollable-books-section::-webkit-scrollbar-thumb {
        background-color: #888;
        border-radius: 4px;
      }

    </style>
  </head>
  <body style="height: 100vh; overflow-y: auto;">

<?php include 'admin_sidebar.php'; ?>
<?php include 'admin_header.php'; ?>

  <div class="container mt-5" style="padding-top: 40px;">
    <h2 class="text-center mb-4">📚 Admin Book Management</h2>

    <!-- Add Book Form -->
    <div class="card mb-5">
      <div class="card-header bg-primary text-white">
        Add a New Book
      </div>
      <div class="card-body">
        <form action="" method="POST" enctype="multipart/form-data">
          <div class="row g-3">
            <div class="col-md-6">
              <input type="text" name="name" class="form-control" placeholder="Book Name" required>
            </div>
            <div class="col-md-6">
              <input type="text" name="author" class="form-control" placeholder="Author Name" required>
            </div>
            <div class="col-md-6">
              <input type="text" name="tags" class="form-control" placeholder="ISBN" pattern="\d{13}" required>
            </div>
            <div class="col-md-6">
              <input type="number" name="books_count" class="form-control" placeholder="Books Count" required>
            </div>
            <div class="col-md-6">
              <input type="number" name="pages" class="form-control" placeholder="Pages" required>
            </div>
            <div class="col-md-6">
              <input type="text" name="format" class="form-control" placeholder="Format (e.g., Reference)" required>
            </div>
            <div class="col-md-6">
              <input type="text" name="genre" class="form-control" placeholder="Genre (e.g., Fiction)" required>
            </div>
            <div class="col-md-6">
              <input type="file" name="image" class="form-control" accept="image/*" required>
            </div>
            <div class="col-md-6">
            <textarea name="description" class="form-control" rows="3" placeholder="Description" required></textarea>
            </div>
            <div class="col-md-6">
              <select name="dewey_decimal" class="form-select" required>
                <option value="" disabled selected>Select Dewey Decimal</option>
                <option value="000 – General Works">000 – General Works</option>
                <option value="100 – Philosophy & Psychology">100 – Philosophy & Psychology</option>
                <option value="200 – Religion">200 – Religion</option>
                <option value="300 – Social Sciences">300 – Social Sciences</option>
                <option value="400 – Language">400 – Language</option>
                <option value="500 – Natural Sciences & Mathematics">500 – Natural Sciences & Mathematics</option>
                <option value="600 – Technology (Applied Sciences)">600 – Technology (Applied Sciences)</option>
                <option value="700 – Arts & Recreation">700 – Arts & Recreation</option>
                <option value="800 – Literature">800 – Literature</option>
                <option value="900 – History & Geography">900 – History & Geography</option>
              </select>
            </div>

          </div>
          <div class="text-end mt-3">
            <button type="submit" name="add_book" class="btn btn-success">Add Book</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Total Books Summary -->
    <?php
    $total_books_query = mysqli_query($conn, "SELECT SUM(books_count) AS total FROM `books`") or die('Query failed');
    $total_books_row = mysqli_fetch_assoc($total_books_query);
    $total_books = $total_books_row['total'] ?? 0;
    ?>

    <div class="alert alert-info text-center">
      Total Books in Library: <strong><?php echo $total_books; ?></strong>
    </div>

    <!-- Display Books -->
  <!-- Display Books (Scrollable) -->
  <div class="scrollable-books-section" style="max-height: 600px; overflow-y: auto;">
    <div class="row">
      <?php
      $result = mysqli_query($conn, "SELECT * FROM `books`") or die('Query failed');
      if (mysqli_num_rows($result) > 0):
        while ($book = mysqli_fetch_assoc($result)):
      ?>
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <img src="uploaded_img/<?php echo $book['image']; ?>" class="card-img-top" alt="Book Cover">
          <div class="card-body">
            <h5 class="card-title"><?php echo $book['name']; ?></h5>
            <p class="card-text">
              <strong>Author:</strong> <?php echo $book['author']; ?><br>
              <strong>Tags:</strong> <?php echo $book['tags']; ?><br>
              <strong>Count:</strong> <?php echo $book['books_count']; ?><br>
              <strong>Pages:</strong> <?php echo $book['pages']; ?><br>
              <strong>Format:</strong> <?php echo $book['format']; ?><br>
              <strong>Genre:</strong> <?php echo $book['genre']; ?>
            </p>
          </div>
          <div class="card-footer text-end">
            <a href="admin_books.php?update=<?php echo $book['id']; ?>" class="btn btn-warning btn-sm">Update</a>
            <a href="admin_books.php?delete=<?php echo $book['id']; ?>" onclick="return confirm('Delete this book?')" class="btn btn-danger btn-sm">Delete</a>
          </div>
        </div>
      </div>
      <?php endwhile; else: ?>
      <div class="col-12">
        <div class="alert alert-warning text-center">No books found in the library.</div>
      </div>
      <?php endif; ?>
    </div>
  </div>


    <!-- Edit Book Form -->
    <?php if (isset($_GET['update'])): ?>
      <?php
        $update_id = $_GET['update'];
        $edit_query = mysqli_query($conn, "SELECT * FROM `books` WHERE id = '$update_id'") or die('Query failed');
        if ($book = mysqli_fetch_assoc($edit_query)):
      ?>
      <div class="card mt-5">
        <div class="card-header bg-warning text-dark">
          Update Book: <?php echo $book['name']; ?>
        </div>
        <div class="card-body">
          <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="update_b_id" value="<?php echo $book['id']; ?>">
            <input type="hidden" name="update_old_img" value="<?php echo $book['image']; ?>">

            <div class="row g-3">
              <div class="col-md-6">
                <input type="text" name="update_name" value="<?php echo $book['name']; ?>" class="form-control" required>
              </div>
              <div class="col-md-6">
                <input type="text" name="update_author" value="<?php echo $book['author']; ?>" class="form-control" required>
              </div>
              <div class="col-md-6">
                <input type="text" name="update_tags" value="<?php echo $book['tags']; ?>" class="form-control" required>
              </div>
              <div class="col-md-6">
                <input type="number" name="update_books_count" value="<?php echo $book['books_count']; ?>" class="form-control" required>
              </div>
              <div class="col-md-6">
                <input type="number" name="update_pages" value="<?php echo $book['pages']; ?>" class="form-control" required>
              </div>
              <div class="col-md-6">
                <input type="text" name="update_format" value="<?php echo $book['format']; ?>" class="form-control" required>
              </div>
              <div class="col-md-6">
                <input type="text" name="update_genre" value="<?php echo $book['genre']; ?>" class="form-control" required>
              </div>
              <div class="col-md-6">
                <input type="file" name="update_image" class="form-control" accept="image/*">
                <img src="uploaded_img/<?php echo $book['image']; ?>" alt="" class="mt-2 img-thumbnail" style="max-height: 120px;">
              </div>
            <div class="col-md-6">
              <textarea name="update_description" class="form-control" rows="3" required><?php echo $book['description']; ?></textarea>
            </div>
            <div class="col-md-6">
              <select name="update_dewey_decimal" class="form-select" required>
                <option value="" disabled>Select Dewey Decimal</option>
                <option value="000 – General Works" <?php if ($book['dewey_decimal'] == "000 – General Works") echo 'selected'; ?>>000 – General Works</option>
                <option value="100 – Philosophy & Psychology" <?php if ($book['dewey_decimal'] == "100 – Philosophy & Psychology") echo 'selected'; ?>>100 – Philosophy & Psychology</option>
                <option value="200 – Religion" <?php if ($book['dewey_decimal'] == "200 – Religion") echo 'selected'; ?>>200 – Religion</option>
                <option value="300 – Social Sciences" <?php if ($book['dewey_decimal'] == "300 – Social Sciences") echo 'selected'; ?>>300 – Social Sciences</option>
                <option value="400 – Language" <?php if ($book['dewey_decimal'] == "400 – Language") echo 'selected'; ?>>400 – Language</option>
                <option value="500 – Natural Sciences & Mathematics" <?php if ($book['dewey_decimal'] == "500 – Natural Sciences & Mathematics") echo 'selected'; ?>>500 – Natural Sciences & Mathematics</option>
                <option value="600 – Technology (Applied Sciences)" <?php if ($book['dewey_decimal'] == "600 – Technology (Applied Sciences)") echo 'selected'; ?>>600 – Technology (Applied Sciences)</option>
                <option value="700 – Arts & Recreation" <?php if ($book['dewey_decimal'] == "700 – Arts & Recreation") echo 'selected'; ?>>700 – Arts & Recreation</option>
                <option value="800 – Literature" <?php if ($book['dewey_decimal'] == "800 – Literature") echo 'selected'; ?>>800 – Literature</option>
                <option value="900 – History & Geography" <?php if ($book['dewey_decimal'] == "900 – History & Geography") echo 'selected'; ?>>900 – History & Geography</option>
              </select>
            </div>
            </div>
            <div class="text-end mt-3">
              <button type="submit" name="update_book" class="btn btn-primary">Update Book</button>
              <a href="admin_books.php" class="btn btn-secondary">Cancel</a>
            </div>
          </form>
          <?php
            if (isset($_POST['update_book'])) {
                // Get the updated form data
                $update_b_id = $_POST['update_b_id'];
                $update_name = $_POST['update_name'];
                $update_author = $_POST['update_author'];
                $update_tags = $_POST['update_tags'];
                $update_books_count = $_POST['update_books_count'];
                $update_pages = $_POST['update_pages'];
                $update_format = $_POST['update_format'];
                $update_genre = $_POST['update_genre'];
                $update_description = $_POST['update_description'];
                $update_dewey_decimal = $_POST['update_dewey_decimal'];

                // Handle image upload if a new image is selected
                if ($_FILES['update_image']['name'] != '') {
                    $update_image = $_FILES['update_image']['name'];
                    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
                    $image_folder = 'uploaded_img/' . $update_image;
                    
                    // Move the uploaded file to the server directory
                    if (move_uploaded_file($update_image_tmp_name, $image_folder)) {
                        // If a new image is uploaded, use the new image
                        $image_to_update = $update_image;
                    }
                } else {
                    // If no new image is uploaded, use the old image
                    $image_to_update = $_POST['update_old_img'];
                }

                // Update the book record in the database
                $update_query = mysqli_query($conn, "UPDATE `books` SET 
                    name = '$update_name', 
                    author = '$update_author', 
                    tags = '$update_tags', 
                    books_count = '$update_books_count', 
                    pages = '$update_pages', 
                    format = '$update_format', 
                    genre = '$update_genre', 
                    description = '$update_description', 
                    dewey_decimal = '$update_dewey_decimal', 
                    image = '$image_to_update' 
                    WHERE id = '$update_b_id'") or die('Query failed');

                // Check if the update was successful
                if ($update_query) {
                    echo '<script>alert("Book updated successfully!"); window.location.href="admin_books.php";</script>';
                } else {
                    echo '<script>alert("Failed to update the book. Please try again.");</script>';
                }
            }
            ?>
        </div>
      </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
  </html>
