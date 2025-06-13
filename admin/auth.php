<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validate input
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "All fields are required";
        header("Location: login.php");
        exit;
    }

    try {
        $database = new Database();
        $db = $database->getConnection();
        
        // Prepare statement using PDO
        $stmt = $db->prepare("
            SELECT 
                a.id,
                a.username,
                a.password,
                a.role,
                a.status,
                a.last_login
            FROM admin_users a
            WHERE a.username = :username 
            AND a.status = 'active'
            LIMIT 1
        ");
        
        $stmt->execute(['username' => $username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {
            // Set session variables
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_role'] = $admin['role'];
            $_SESSION['admin_login_time'] = time();
            $_SESSION['is_admin'] = true;

            // Update last login time
            $updateStmt = $db->prepare("
                UPDATE admin_users 
                SET last_login = NOW(),
                    login_count = login_count + 1
                WHERE id = :admin_id
            ");
            $updateStmt->execute(['admin_id' => $admin['id']]);

            // Log the login
            $logStmt = $db->prepare("
                INSERT INTO admin_login_logs (
                    admin_id, 
                    login_time, 
                    ip_address, 
                    user_agent
                ) VALUES (
                    :admin_id, 
                    NOW(), 
                    :ip_address, 
                    :user_agent
                )
            ");
            
            $logStmt->execute([
                'admin_id' => $admin['id'],
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT']
            ]);

            // Redirect based on role
            switch($admin['role']) {
                case 'super_admin':
                    header("Location: dashboard.php");
                    break;
                case 'manager':
                    header("Location: manager/dashboard.php");
                    break;
                default:
                    header("Location: staff/dashboard.php");
            }
            exit;

        } else {
            // Log failed login attempt
            $attemptStmt = $db->prepare("
                INSERT INTO admin_login_attempts (
                    username,
                    ip_address,
                    attempt_time,
                    status
                ) VALUES (
                    :username,
                    :ip_address,
                    NOW(),
                    'failed'
                )
            ");
            
            $attemptStmt->execute([
                'username' => $username,
                'ip_address' => $_SERVER['REMOTE_ADDR']
            ]);

            // Check for brute force attempts
            $checkStmt = $db->prepare("
                SELECT COUNT(*) as attempt_count 
                FROM admin_login_attempts 
                WHERE ip_address = :ip_address 
                AND attempt_time > DATE_SUB(NOW(), INTERVAL 15 MINUTE)
                AND status = 'failed'
            ");
            
            $checkStmt->execute(['ip_address' => $_SERVER['REMOTE_ADDR']]);
            $attempts = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($attempts['attempt_count'] >= 5) {
                $_SESSION['error'] = "Too many failed attempts. Please try again later.";
            } else {
                $_SESSION['error'] = "Invalid credentials";
            }
            
            header("Location: login.php");
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "System error. Please try again later.";
        header("Location: login.php");
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
?>