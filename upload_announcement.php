<?php
include 'config.php';
include 'admin_header.php';
include 'admin_sidebar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $photo_url = null;

    // Handle file upload
    if (isset($_FILES['announcement_image']) && $_FILES['announcement_image']['error'] == 0) {
        $fileTmpPath = $_FILES['announcement_image']['tmp_name'];
        $fileName = basename($_FILES['announcement_image']['name']);
        $uploadDir = 'uploads/announcements/';
        $destPath = $uploadDir . $fileName;

        // Make sure the directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $photo_url = $destPath;

            // Insert into the database (no message anymore)
            $query = "INSERT INTO `announcements` (photo_url) VALUES ('$photo_url')";
            if (mysqli_query($conn, $query)) {
                echo "<div class='alert alert-success text-center mt-4'>Announcement image uploaded successfully!</div>";
            } else {
                echo "<div class='alert alert-danger text-center mt-4'>Database Error: " . mysqli_error($conn) . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger text-center mt-4'>Error uploading the file.</div>";
        }
    } else {
        echo "<div class='alert alert-warning text-center mt-4'>No image file selected.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Announcement Image</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="images/logo.png" type="image/icon type">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4" style="margin-top: 70px;">Upload Announcement Image</h2>
        <form action="upload_announcement.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="announcementImage" class="form-label">Upload Image <span class="text-danger">*</span></label>
                <input class="form-control" type="file" id="announcementImage" name="announcement_image" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
