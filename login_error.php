<?php
// login_error.php
$showModal = false;

if (isset($_GET['error']) && $_GET['error'] === 'invalid') {
    $errorMessage = "Invalid username or password.";
    $showModal = true;
}

$errorMessage = '';
$showModal = false;

if (isset($_GET['error'])) {
    $showModal = true;
    if ($_GET['error'] === 'invalid') {
        $errorMessage = "Invalid username or password.";
    } elseif ($_GET['error'] === 'unverified') {
        $errorMessage = "Your account is not yet verified. Please contact the administrator.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Error</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="errorModalLabel">Login Failed</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <?php echo $errorMessage; ?>
                </div>
                <div class="modal-footer justify-content-center">
                    <a href="login.php" class="btn btn-primary">Back to Login</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <?php if ($showModal): ?>
    <script>
        // Show the modal automatically on page load
        var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        errorModal.show();
    </script>
    <?php endif; ?>
</body>
</html>
