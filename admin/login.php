<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Distributor Management</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* [Same styles as index.php] */
    </style>
</head>
<body>
    <!-- Header Info -->
    

    <div class="container">
        <div class="login-container">
            <center>
                <center><div class="logo-container">
                    <img src="../assets/images/logo.png" alt="Company Logo"style="width:200px;height:150px;">
                </div></center>
            </center>
            <div class="login-header">
                <h2>Admin Login</h2>
            </div>
            
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger text-center">
                    <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <form action="auth.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Admin Username</label>
                    <input type="text" class="form-control" id="username" name="username" required autocomplete="off">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Login</button>
                    <a href="../index.php" class="btn btn-outline-secondary">Back to Home</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>