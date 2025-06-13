<?php
session_start();
require_once '../config/database.php';

// Get list of branches
try {
    $database = new Database();
    $db = $database->getConnection();
    $query = "SELECT id, branch_name FROM branches WHERE status = 'active' ORDER BY branch_name";
    $result = $db->query($query);
    $branches = $result->fetchAll(PDO::FETCH_ASSOC); // Changed from fetch_all to fetchAll
} catch(Exception $e) {
    $branches = [];
    error_log("Error fetching branches: " . $e->getMessage());
}

$currentDateTime = date('Y-m-d H:i:s');
$currentUser = 'sgpriyom';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Login - Distributor Management</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/bootstrap-icons.css" rel="stylesheet">
    <style>
        .header-info {
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 10px 0;
            font-family: monospace;
            font-size: 14px;
            white-space: pre-line;
        }
        body {
            background: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo-container {
            margin-bottom: 30px;
            text-align: center;
        }
        .logo-container img {
            width: 200px;
            height: 150px;
            object-fit: contain;
        }
        .form-select, .form-control {
            padding: 0.75rem 1rem;
            font-size: 1rem;
            border-radius: 0.375rem;
        }
        .btn-primary {
            padding: 0.75rem 1.5rem;
        }
        .branch-select-container {
            margin-bottom: 1rem;
        }
        .input-group-text {
            background-color: transparent;
        }
    </style>
</head>
<body>
    <!-- Header Info -->
    <div class="header-info">
        <div class="container">
Current Date and Time (UTC - YYYY-MM-DD HH:MM:SS formatted): <?php echo $currentDateTime; ?>
Current User's Login: <?php echo $currentUser; ?></div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="login-container">
            <div class="logo-container">
                <img src="../assets/images/logo.png" alt="Company Logo">
            </div>
            
            <div class="login-header">
                <h2>Branch Login</h2>
            </div>
            
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="auth.php" method="POST" id="loginForm">
                <div class="mb-3 branch-select-container">
                    <label for="branch" class="form-label">Select Branch</label>
                    <select class="form-select" id="branch" name="branch_id" required>
                        <option value="">Choose branch...</option>
                        <?php foreach($branches as $branch): ?>
                            <option value="<?php echo htmlspecialchars($branch['id']); ?>">
                                <?php echo htmlspecialchars($branch['branch_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="text" 
                               class="form-control" 
                               id="username" 
                               name="username" 
                               required 
                               autocomplete="off"
                               placeholder="Enter username">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" 
                               class="form-control" 
                               id="password" 
                               name="password" 
                               required
                               placeholder="Enter password">
                        <button class="btn btn-outline-secondary" 
                                type="button" 
                                id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </button>
                    <a href="../index.php" class="btn btn-outline-secondary">
                        <i class="bi bi-house"></i> Back to Home
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Toggle password visibility
            $('#togglePassword').click(function() {
                const password = $('#password');
                const icon = $(this).find('i');
                
                if (password.attr('type') === 'password') {
                    password.attr('type', 'text');
                    icon.removeClass('bi-eye').addClass('bi-eye-slash');
                } else {
                    password.attr('type', 'password');
                    icon.removeClass('bi-eye-slash').addClass('bi-eye');
                }
            });

            // Form validation
            $('#loginForm').submit(function(e) {
                const branch = $('#branch').val();
                const username = $('#username').val();
                const password = $('#password').val();

                if (!branch || !username || !password) {
                    e.preventDefault();
                    alert('Please fill in all fields');
                    return false;
                }
            });
        });
    </script>
</body>
</html>