<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: admin_dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Smart Count Login</title>
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <b>Smart Count</b> Login
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <?php
                    // Display error message if it exists
                    if (isset($_SESSION['error'])) {
                        echo '<p style="color: red; text-align: center;">' . $_SESSION['error'] . '</p>';
                        unset($_SESSION['error']); // Clear the error message
                    }
                ?>
                <form action="login_process.php" method="post">
                    <div class="input-group mb-3">
                        <input type="text" name="id" class="form-control" placeholder="Username" required>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-outline-primary btn-block">Login</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="dist/js/adminlte.min.js"></script>
</body>
</html>