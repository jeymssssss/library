<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Library Admin Panel</title>

  <link rel="icon" href="images/library_logo.png" type="image/icon type">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      margin: 0;
      font-family: 'Roboto', sans-serif;
      background-color: #f4f7fc;
      height: 100vh;
      overflow: hidden;
    }

    .sidebar {
      width: 250px;
      height: 100vh;
      background-color: #1B4965;
      color: #fff;
      position: fixed;
      top: 0;
      left: 0;
      padding: 20px;
      transition: transform 0.3s ease;
      transform: translateX(-100%); /* Initially hidden */
      z-index: 1000;
    }

    .sidebar.show {
      transform: translateX(0); /* Show the sidebar when active */
    }

    .sidebar .logo-container {
      text-align: center;
      margin-bottom: 20px;
    }

    .sidebar .logo-container img {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      object-fit: cover;
      margin-top: 50px;
    }

    .sidebar .system-name {
      font-size: 20px;
      font-weight: bold;
    }

    .sidebar a {
      display: block;
      color: white;
      text-decoration: none;
      margin: 15px 0;
      padding: 10px;
      border-radius: 5px;
      transition: background-color 0.2s;
    }

    .sidebar a:hover {
      background-color: #156080;
    }

    /* Toggle Button */
    #toggle-btn {
      position: absolute;
      top: 18px;
      left: 20px;
      font-size: 24px;
      cursor: pointer;
      color: white;
      z-index: 1001;
    }

      /* Add this to your CSS */
    @keyframes rotateIcon {
    0% {
    transform: rotate(0deg);
    }
    100% {
    transform: rotate(180deg);
    }
  }

  #toggle-btn.animate {
    animation: rotateIcon 0.4s ease forwards;
  }


  </style>
</head>
<body>

<!-- Toggle Button -->
<div id="toggle-btn" class='bx bx-menu'></div>

<!-- Sidebar -->
<style>
  .dropdown-container {
    display: none;
    padding-left: 20px;
    flex-direction: column;
  }

  .dropdown-container a {
    padding: 5px 0;
    font-size: 14px;
  }

  .dropdown-toggle.active + .dropdown-container {
    display: flex;
  }

  .sidebar a {
    display: block;
    padding: 10px;
    text-decoration: none;
    color: white;
    cursor: pointer;
  }

  .sidebar a:hover {
      background-color: #156080;
  }
</style>

<div class="sidebar" id="sidebar">
  <div class="logo-container">
    <img src="images/logo.png" alt="Library Logo">
    <div class="system-name">Masambong Library</div>
  </div>

  <a href="admin_dashboard.php"><i class="bi bi-house-door"></i> Home | Dashboard</a>
  <a href="admin_books.php"><i class="bi bi-book"></i> Books | Search</a>

  <!-- Dropdown Trigger -->
  <a class="dropdown-toggle"><i class="bi bi-file-earmark-text"></i> View Logs </a>
  <div class="dropdown-container">
    <a href="admin_approval.php"><i class="bi bi-journal-arrow-down"></i> To Approve</a>
    <a href="admin_approved_books.php"><i class="bi bi-journal-arrow-down"></i> Borrowed Logs | Returned Logs</a>

    <a href="admin_reservations.php"><i class="bi bi-bookmark-check"></i> Reserved Logs</a>
  </div>

  <a href="user_credentials.php"><i class="bi bi-person-circle"></i> Accounts | Users</a>
  <a href="transaction_history.php"><i class="bi bi-clock-history"></i> History</a>
  <a href="upload_announcement.php"><i class="bi bi-megaphone"></i> Announcement</a>
</div>

<script>
  document.querySelector('.dropdown-toggle').addEventListener('click', function () {
    this.classList.toggle('active');
  });
</script>


<!-- Main Content -->
<div id="main-content">
  <!-- Your content goes here -->
</div>

<!-- JS -->
<script>
  const toggleBtn = document.getElementById('toggle-btn');
  const sidebar = document.getElementById('sidebar');

  toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('show');

    toggleBtn.classList.add('animate');

// Remove it after animation ends (so it can replay next time)
toggleBtn.addEventListener('animationend', () => {
  toggleBtn.classList.remove('animate');
}, { once: true });
});

</script>

</body>
</html>
