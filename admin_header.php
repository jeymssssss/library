<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Library Admin Panel</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Boxicons CSS -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

  <style>
    body {
      margin: 0;
      font-family: 'Roboto', sans-serif;
      background-color: #f4f7fc;
    }

    /* Header Styling */
    .header {
      background-color: #1B4965;
      color: #fff;
      padding: 15px 20px;
      display: flex;
      justify-content: flex-end;
      align-items: center;
      position: fixed;
      width: 100%;
      top: 0;
      z-index: 1000;
    }

    .header .logo {
      width: 30px; /* Set size of the logo */
      height: auto;
      margin-right: 15px;
      border-radius: 35px;
    }

    /* User Profile Dropdown */
    .dropdown-menu {
      right: 0;
      left: auto;
    }
  </style>
</head>
<body>

<!-- Header -->
<div class="header">
  <!-- Library Logo (replace with your own logo image) -->
  <img src="images/logo.png" alt="Library Logo" class="logo">

  <!-- User Profile Dropdown -->
  <div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="bx bx-user"></i> Admin
    </button>
    <ul class="dropdown-menu" aria-labelledby="userDropdown">
      <li><a class="dropdown-item" href="profile_admin.php">Profile</a></li>
      <li><hr class="dropdown-divider"></li>
      <li><a class="dropdown-item" href="logout.php">Logout</a></li>
    </ul>
  </div>
</div>

<!-- Bootstrap 5 JS (no Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

</body>
</html>
