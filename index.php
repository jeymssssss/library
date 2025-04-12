<?php
session_start();
include 'config.php';

// Fetch latest announcement
$announcement_query = "SELECT * FROM `announcements` ORDER BY created_at DESC ";
$announcement_result = mysqli_query($conn, $announcement_query);
$announcement = mysqli_fetch_assoc($announcement_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Library System - Announcement</title>
  <link rel="icon" href="images/logo.png" type="image/icon type">

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <style>
  .announcement-img-container {
    margin-top: 90px;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .fixed-announcement-box {
    width: 1116px;
    height: 500px;
    overflow: hidden;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  }

  .fixed-announcement-box img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* fills the box and crops if necessary */
    border-radius: 15px;
  }
</style>

</head>
<body>

<?php include 'user_header.php'; ?>
<?php include 'user_sidebar.php'; ?>

<div class="container mt-5">
  <?php if ($announcement && !empty($announcement['photo_url'])): ?>
    <div class="announcement-img-container" >
      <div class="fixed-announcement-box" >
        <img src="<?php echo htmlspecialchars($announcement['photo_url']); ?>" alt="Announcement Image">
      </div>
    </div>
  <?php else: ?>
    <div class="alert alert-warning text-center mt-5 p-4 rounded-4">
      <strong>No announcement posted.</strong>
    </div>
  <?php endif; ?>
</div>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
