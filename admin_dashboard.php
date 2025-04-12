<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_name'])) {
    header('Location: login.php');
    exit();
}

$admin_name = $_SESSION['admin_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard - Library System</title>
  <link rel="icon" href="images/logo.png" type="image/icon type">

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Boxicons -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles/sidebar_styles.css">

  <style>
  html, body {
    height: 100%;
    margin: 0;
    padding: 0;
  }

  .card {
    border-radius: 1rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
  }

  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
  }

  .card i {
    font-size: 2.5rem;
  }
  </style>
</head>
<body>

<!-- Inside <body> -->
<?php include 'admin_sidebar.php'; ?>
<?php include 'admin_header.php'; ?>

<div class="container mt-5 mb-5">

  <h2 class="mb-4 text-center" style="margin-top: 80px;">Admin Dashboard Overview</h2>

  <div class="row g-4">

    <!-- Total Books -->
    <div class="col-md-4">
      <div class="card text-center p-3">
        <i class='bx bx-library text-primary'></i>
        <h4 class="text-dark mt-2">
          <?php
            $total_books_in_system = 0;
            $result = mysqli_query($conn, "SELECT SUM(books_count) AS total FROM `books`");
            if ($row = mysqli_fetch_assoc($result)) {
              $total_books_in_system = $row['total'];
            }
            echo $total_books_in_system;
          ?>
        </h4>
        <p class="mb-0">Total Books</p>
      </div>
    </div>

    <!-- Total Users -->
    <div class="col-md-4">
      <div class="card text-center p-3">
        <i class='bx bx-user text-info'></i>
        <h4 class="text-info mt-2">
          <?php
            $total_users = 0;
            $result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM `users`");
            if ($row = mysqli_fetch_assoc($result)) {
              $total_users = $row['total'];
            }
            echo $total_users;
          ?>
        </h4>
        <p class="mb-0">Total Users</p>
      </div>
    </div>

    <!-- Verified Users -->
    <div class="col-md-4">
      <div class="card text-center p-3">
        <i class='bx bx-check-circle text-success'></i>
        <h4 class="text-success mt-2">
          <?php
            $verified_users = 0;
            $result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM `users` WHERE is_verified = 1");
            if ($row = mysqli_fetch_assoc($result)) {
              $verified_users = $row['total'];
            }
            echo $verified_users;
          ?>
        </h4>
        <p class="mb-0">Verified Users</p>
      </div>
    </div>

    <!-- Not Verified Users -->
    <div class="col-md-4">
      <div class="card text-center p-3">
        <i class='bx bx-error-circle text-warning'></i>
        <h4 class="text-warning mt-2">
          <?php
            $not_verified_users = 0;
            $result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM `users` WHERE is_verified = 0");
            if ($row = mysqli_fetch_assoc($result)) {
              $not_verified_users = $row['total'];
            }
            echo $not_verified_users;
          ?>
        </h4>
        <p class="mb-0">Not Verified Users</p>
      </div>
    </div>

    <!-- Books Borrowed -->
    <div class="col-md-4">
      <div class="card text-center p-3">
        <i class='bx bx-book text-success'></i>
        <h4 class="text-success mt-2">
          <?php 
            $stmt = $conn->prepare("SELECT SUM(total_books) AS total_borrowed FROM user_borrowed WHERE status = 'Approved'");
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $total_borrowed_books = $result['total_borrowed'] ?? 0;
            echo $total_borrowed_books;
          ?>
        </h4>
        <p class="mb-0">Books Borrowed</p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card text-center p-3">
        <i class='bx bx-book text-warning'></i>
        <h4 class="text-warning mt-2">
          <?php 
            $stmt = $conn->prepare("SELECT SUM(total_books) AS total_pending FROM user_borrowed WHERE status = 'Pending'");
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $total_pending_books = $result['total_pending'] ?? 0;
            echo $total_pending_books;
          ?>
        </h4>
        <p class="mb-0">Books Pending</p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card text-center p-3">
        <i class='bx bx-book-bookmark text-success'></i>
        <h4 class="text-success mt-2">
          <?php 
            $stmt = $conn->prepare("SELECT SUM(total_books) AS total_returned FROM user_borrowed WHERE status = 'Returned'");
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $total_returned_books = $result['total_returned'] ?? 0;
            echo $total_returned_books;
          ?>
        </h4>
        <p class="mb-0">Books Returned</p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card text-center p-3">
        <i class='bx bx-book text-danger'></i>
        <h4 class="text-danger mt-2">
          <?php 
            $stmt = $conn->prepare("SELECT SUM(total_books) AS total_declined FROM user_borrowed WHERE status = 'Declined'");
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $total_declined_books = $result['total_declined'] ?? 0;
            echo $total_declined_books;
          ?>
        </h4>
        <p class="mb-0">Books Declined</p>
      </div>
    </div>

  </div>
</div>

  <!-- Bootstrap JS (for optional interactive features) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
