<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Masambong Library</title>

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
      margin-top: 40px;
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
      color: white;
    }

    /* Dropdown Menu */
    .dropdown-menu {
      display: none;
      background-color: #1B4965;
      margin-left: 20px;
    }

    .dropdown-item {
      display: block;
      padding: 10px;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      transition: background-color 0.2s;
    }

    .dropdown-item a {
      color: white;
    }

    .dropdown-item:hover {
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

    /* Animation */
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
<div class="sidebar" id="sidebar">
  <div class="logo-container">
    <img src="images/logo.png" alt="Library Logo">
    <div class="system-name">Masambong Library</div>
  </div>
  <a href="index.php"><i class="bi bi-house-door"></i> Home | Announcement</a>
  <a href="books.php"><i class="bi bi-search"></i> Search | Books</a>
  <a href="user_borrow_history.php" id="viewLogsLink"><i class="bi bi-file-earmark-text"></i> History </a>
</div>

<!-- Main Content -->
<div id="main-content">
  <!-- Your content goes here -->
</div>

<!-- JS -->
<script>
  const toggleBtn = document.getElementById('toggle-btn');
  const sidebar = document.getElementById('sidebar');
  const viewLogsLink = document.getElementById('viewLogsLink');
  const dropdownMenu = document.getElementById('dropdownMenu');

  toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('show');
    toggleBtn.classList.add('animate');
    toggleBtn.addEventListener('animationend', () => {
      toggleBtn.classList.remove('animate');
    }, { once: true });
  });
</script>

</body>
</html>
