<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

checkBranchAuth();

$page_title = 'Add APB Transaction';
$db = new Database();
$conn = $db->getConnection();
$branch_id = $_SESSION['branch_user']['branch_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transaction_date = isset($_POST['transaction_date']) ? trim($_POST['transaction_date']) : '';
    $quantity_received = isset($_POST['quantity_received']) ? intval($_POST['quantity_received']) : 0;
    $opening_stock = isset($_POST['opening_stock']) ? intval($_POST['opening_stock']) : 0;
    $total_sold = isset($_POST['total_sold']) ? intval($_POST['total_sold']) : 0;

    if (empty($transaction_date) || $quantity_received < 0 || $opening_stock < 0 || $total_sold < 0) {
        $_SESSION['error'] = "All fields are required and must be valid.";
        header('Location: create.php');
        exit;
    }

    // Calculations
    $total_available = $quantity_received + $opening_stock;
    $closing_stock = $total_available - $total_sold;

    // Insert into database
    try {
        $stmt = $conn->prepare("
            INSERT INTO apb 
            (branch_id, transaction_date, quantity_received, opening_stock, total_available, total_sold, closing_stock, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$branch_id, $transaction_date, $quantity_received, $opening_stock, $total_available, $total_sold, $closing_stock]);

        $_SESSION['success'] = "APB transaction added successfully.";
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to add transaction: " . $e->getMessage();
        header('Location: create.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <h4><?php echo $page_title; ?></h4>
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Add APB Transaction</h5>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Transaction Date</label>
                            <input type="date" class="form-control" name="transaction_date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Quantity Received</label>
                            <input type="number" class="form-control" name="quantity_received" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Opening Stock</label>
                            <input type="number" class="form-control" name="opening_stock" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Total Sold</label>
                            <input type="number" class="form-control" name="total_sold" min="0" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Add Transaction</button>
                            <a href="index.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>