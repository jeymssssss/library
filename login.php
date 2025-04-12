<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | Library Management System</title>

  <link rel="stylesheet" href="styles/styles.css" />
  <link rel="icon" href="images/logo.png" type="image/icon type" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

  <style>
    * {
      box-sizing: border-box;
    }

    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      overflow: hidden;
      font-family: Arial, sans-serif;
      background-size: cover;
    }

    .login-container {
      background-color: rgba(255, 255, 255, 0.95);
      width: 400px; /* Wider */
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
      position: absolute;
      top: 62%; /* Lower on screen */
      left: 50%;
      transform: translate(-50%, -50%);
      text-align: center;
    }

    .login-container img.logo {
      width: 80px;
      height: 80px;
      margin-bottom: 10px;
    }

    h2 {
      margin-bottom: 25px;
      font-size: 24px;
    }

    input[type="text"], input[type="password"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #aaa;
      border-radius: 5px;
      font-size: 15px;
    }

    .show-password {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
      font-size: 14px;
    }

    .show-password input {
      margin-right: 5px;
    }

    button {
      width: 100%;
      padding: 12px;
      background-color: #0066cc;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }

    button:hover {
      background-color: #004999;
    }

    .footer-credit {
      position: absolute;
      bottom: 5px;
      width: 100%;
      text-align: center;
      font-size: 14px;
      color: black;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }

        /* Ensuring Show Password label works as expected */
    .show-password {
     display: flex;
     justify-content: space-between; /* Spread the elements apart */
     align-items: center;
     margin-bottom: 20px;
    }

    .show-password a.register-link {
     text-decoration: none;
     color: #0066cc;
     font-size: 14px;
     font-weight: 500;
     margin-left: 10px; /* Add space between checkbox and link */
    }

    .show-password a.register-link:hover {
     color: #004999;
     text-decoration: underline;
    }

    /* No need for this second definition */
    .show-password input {
     margin-right: 5px;
    }


  </style>
</head>
<body>

<div class="login-container">
  <img src="images/logo.png" alt="Library Logo" class="logo">
  <h2>Library Login</h2>
  <form action="login_handler.php" method="post">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="password" id="password" name="password" placeholder="Password" required><br>
    
    <div class="show-password">
      <div>
        <input type="checkbox" onclick="togglePassword()">
        <label>Show Password</label>
      </div>
      <a href="register.php" class="register-link">Create Account</a>
    </div>

    <button type="submit">Login</button>
  </form>
</div>

<!-- Footer Credit -->
<footer class="footer-credit">
  <p>© 2025 Library Management System | Developed by LGU 1</p>
</footer>

<script>
  function togglePassword() {
    const pwd = document.getElementById("password");
    // Check if the checkbox is checked or not and toggle password visibility accordingly
    pwd.type = pwd.type === "password" ? "text" : "password";
  }
</script>


</body>
</html>
