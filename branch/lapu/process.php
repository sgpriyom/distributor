<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/database.php';
require_once '../../includes/auth.php';

checkBranchAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $cash_received = isset($_POST['cash_received']) ? floatval($_POST['cash_received']) : 0;
    $auto_amount = isset($_POST['auto_amount']) ? floatval($_POST['auto_amount']) : 0;
    $opening_balance = isset($_POST['opening_balance']) ? floatval($_POST['opening_balance']) : 0;
    $total_spent = isset($_POST['total_spent']) ? floatval($_POST['total_spent']) : 0;
    $notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';

    // Validate required fields
    if ($cash_received <= 0 || $auto_amount <= 0 || $opening_balance <= 0 || $total_spent <= 0) {
        $_SESSION['error'] = "All numeric fields must be greater than zero.";
        header('Location: create.php');
        exit;
    }

    // Calculate totals
    $total_available_fund = $cash_received + $opening_balance + $auto_amount;
    $closing_amount = $total_available_fund - $total_spent;

    // Insert into database
    $db = new Database();
    $conn = $db->getConnection();
    $branch_id = $_SESSION['branch_user']['branch_id'];

    try {
        $stmt = $conn->prepare("
            INSERT INTO lapu (
                branch_id, transaction_date, cash_received, 
                opening_balance, auto_amount, total_spent, 
                total_available_fund, closing_amount, notes, created_at
            ) VALUES (
                :branch_id, NOW(), :cash_received, 
                :opening_balance, :auto_amount, :total_spent, 
                :total_available_fund, :closing_amount, :notes, NOW()
            )
        ");
        $stmt->execute([
            ':branch_id' => $branch_id,
            ':cash_received' => $cash_received,
            ':opening_balance' => $opening_balance,
            ':auto_amount' => $auto_amount,
            ':total_spent' => $total_spent,
            ':total_available_fund' => $total_available_fund,
            ':closing_amount' => $closing_amount,
            ':notes' => $notes,
        ]);

        $_SESSION['success'] = "New LAPU transaction added successfully.";
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to add transaction: " . $e->getMessage();
        header('Location: create.php');
        exit;
    }
} else {
    $_SESSION['error'] = "Invalid request method.";
    header('Location: create.php');
    exit;
}
?>