<?php
include 'config.php';  // Ensure this contains your correct database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $age = $_POST['age'];
    $id_type = $_POST['id_type'];  // New field for ID type
    
    // Handle file upload for the valid ID
    $valid_id = "";
    if (isset($_FILES['valid_id']) && $_FILES['valid_id']['error'] == 0) {
        // Ensure the uploads directory exists
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);  // Create the folder if it doesn't exist
        }

        // Handle the file upload and store the file path
        $valid_id = "uploads/" . basename($_FILES['valid_id']['name']);
        if (move_uploaded_file($_FILES['valid_id']['tmp_name'], $valid_id)) {
            // File uploaded successfully
        } else {
            echo "Error uploading the file!";
            exit();
        }
    }

    // Prepare SQL query to insert data into the 'users' table
    $sql = "INSERT INTO users (username, password, full_name, email, phone_number, address, valid_id, id_type, age) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("ssssssssi", $username, $password, $full_name, $email, $phone_number, $address, $valid_id, $id_type, $age);
        
        // Execute the statement and check if the query was successful
        if ($stmt->execute()) {
            // If registration is successful, show the modal
            echo '<script>
                    window.onload = function() {
                        var modal = document.getElementById("successModal");
                        modal.style.display = "block"; 
                        setTimeout(function() {
                            modal.style.display = "none"; 
                        }, 3000);
                    };
                  </script>';
        } else {
            echo "Error: " . $stmt->error;  // Handle error
        }
    } else {
        echo "Failed to prepare statement: " . $conn->error;  // Error preparing SQL statement
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Library System - Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      padding: 0;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #FDF6EC;
      font-family: 'Segoe UI', sans-serif;
    }

    .form-container {
      background-color: rgba(255, 255, 255, 0.95);
      padding: 45px;
      width: 400px;
      border-radius: 15px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
      text-align: center;
    }

    .form-container img.logo {
      width: 80px;
      height: 80px;
      margin-bottom: 10px;
    }

    h2 {
      margin-bottom: 25px;
      font-size: 24px;
      color: #2C3E50;
    }

    input[type="text"], input[type="email"], input[type="password"], input[type="number"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #aaa;
      border-radius: 5px;
      font-size: 15px;
    }

    select {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #aaa;
      border-radius: 5px;
      font-size: 15px;
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
      bottom: -80px;
      width: 100%;
      text-align: center;
      font-size: 14px;
      color: black;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }

    .footer-credit a {
      color: #0066cc;
      text-decoration: none;
    }

    .footer-credit a:hover {
      color: #004999;
      text-decoration: underline;
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.4); /* Background color */
      padding-top: 60px;
    }

    .modal-content {
      background-color: #fefefe;
      margin: auto;
      padding: 20px;
      border: 1px solid #888;
      width: 60%;
      text-align: center;
      font-size: 18px;
      border-radius: 5px;
    }

    .back-link {
     text-decoration: none;
     color: black;
     font-size: 14px;
     font-weight: 500;
    }

    .back-link:hover {
     color: #004999;
     text-decoration: underline;
    }
    
  </style>

</head>
<body>

  <div class="form-container">
    <img src="images/logo.png" alt="Library Logo" class="logo">
    <h2>Create Account</h2>
    <form action="register.php" method="POST" enctype="multipart/form-data">
      <input type="text" name="full_name" placeholder="Full Name" required>
      <input type="text" name="username" placeholder="Username" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="text" name="phone_number" placeholder="Phone Number" required>
      <input type="text" name="address" placeholder="Address" required>

      <label for="age" style="font-size: 0.85rem" class="fw-bold small">
        For users below 13 must read the User Registration Policy
      </label>
      <input type="number" name="age" id="age" placeholder="Age" required min="13">

      
      <label for="id_type">Select ID Type</label>
      <select name="id_type" id="id_type" required onchange="toggleOtherID(this)">
        <option value="school_id">School ID</option>
        <option value="qc_id">QCitizen ID</option>
        <option value="others">Other</option>
      </select>

      <!-- Hidden by default -->
      <div id="other_id_div" style="display: none; margin-top: 10px;">
        <label for="other_id_input">Enter Other ID Type:</label>
        <input type="text" name="other_id" id="other_id_input" placeholder="Specify ID Type">
      </div>

      <script>
        function toggleOtherID(selectElement) {
          const otherIDDiv = document.getElementById('other_id_div');
          if (selectElement.value === 'other') {
            otherIDDiv.style.display = 'block';
          } else {
            otherIDDiv.style.display = 'none';
          }
        }
      </script>
      
      <label for="valid_id">Upload Valid ID</label>
      <input type="file" name="valid_id" accept="image/*,application/pdf" required>

      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
        <label class="form-check-label" for="terms">
          I have read and agree to the <a href="policy.php" target="_blank" class="text-decoration-none">User Registration Policy</a>.
        </label>
      </div>

      <button type="submit">Register</button>
      <a href="login.php" class="back-link">Back</a>
    </form>
  </div>

  <div id="successModal" class="modal">
    <div class="modal-content">
      <p>User registered successfully!</p>
    </div>
  </div>

</body>
</html>
