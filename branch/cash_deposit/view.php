<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

checkBranchAuth();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Transaction ID is required.";
    header('Location: index.php');
    exit;
}

$transaction_id = intval($_GET['id']);
$db = new Database();
$conn = $db->getConnection();
$branch_id = $_SESSION['branch_user']['branch_id'];

// Fetch transaction details
$stmt = $conn->prepare("
    SELECT * FROM cash_deposits 
    WHERE id = ? AND branch_id = ?
");
$stmt->execute([$transaction_id, $branch_id]);
$transaction = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$transaction) {
    $_SESSION['error'] = "Transaction not found or you don't have permission to view it.";
    header('Location: index.php');
    exit;
}

$page_title = 'View Cash Deposit Transaction';
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
                <h5 class="card-title mb-0">Transaction Details</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">Transaction ID:</dt>
                    <dd class="col-sm-8"><?php echo htmlspecialchars($transaction['id']); ?></dd>

                    <dt class="col-sm-4">Deposit Date:</dt>
                    <dd class="col-sm-8"><?php echo htmlspecialchars($transaction['deposit_date']); ?></dd>

                    <dt class="col-sm-4">Bank Account ID:</dt>
                    <dd class="col-sm-8"><?php echo htmlspecialchars($transaction['bank_account_id']); ?></dd>

                    <?php
                    // Display the note denominations
                    $notes = [2000, 500, 200, 100, 50, 20, 10, 5, 2, 1];
                    foreach ($notes as $note): ?>
                        <dt class="col-sm-4">Notes <?php echo $note; ?>:</dt>
                        <dd class="col-sm-8"><?php echo htmlspecialchars($transaction['notes_' . $note]); ?></dd>
                    <?php endforeach; ?>

                    <dt class="col-sm-4">Total Amount:</dt>
                    <dd class="col-sm-8"><?php echo number_format($transaction['total_amount'], 2); ?></dd>

                    <dt class="col-sm-4">Created At:</dt>
                    <dd class="col-sm-8"><?php echo htmlspecialchars($transaction['created_at']); ?></dd>
                </dl>
                <a href="index.php" class="btn btn-secondary">Back to Transactions</a>
            </div>
        </div>
    </div>
</body>
</html>