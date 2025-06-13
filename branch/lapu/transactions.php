<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

checkBranchAuth();

$user = $_SESSION['branch_user'];
$page_title = 'LAPU Transactions';

$db = new Database();
$conn = $db->getConnection();
$branch_id = $user['branch_id'];

// Fetch transactions
$stmt = $conn->prepare("
    SELECT * FROM lapu 
    WHERE branch_id = ? 
    ORDER BY transaction_date DESC, created_at DESC
");
$stmt->execute([$branch_id]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- DateTime Header -->
    <div class="container-fluid py-3">
        <div class="row">
            <div class="col-md-6">
                <div class="border rounded p-3 bg-white">
                    Current Date and Time (UTC - YYYY-MM-DD HH:MM:SS formatted):<br>
                    <strong id="current-datetime"><?php echo date('Y-m-d H:i:s'); ?></strong>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <div class="border rounded p-3 bg-white d-inline-block">
                    Current User's Login:<br>
                    <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-between">
            <div class="col-md-6">
                <h4><?php echo $page_title; ?></h4>
            </div>
            <div class="col-md-6 text-end">
                <a href="create.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> New Transaction
                </a>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">All LAPU Transactions</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($transactions)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date & Time</th>
                                <th>Cash Received</th>
                                <th>Opening Balance</th>
                                <th>Auto Amount</th>
                                <th>Total Spent</th>
                                <th>Total Available</th>
                                <th>Closing Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $index => $transaction): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo date('Y-m-d H:i:s', strtotime($transaction['transaction_date'])); ?></td>
                                <td>₹<?php echo number_format($transaction['cash_received'], 2); ?></td>
                                <td>₹<?php echo number_format($transaction['opening_balance'], 2); ?></td>
                                <td>₹<?php echo number_format($transaction['auto_amount'], 2); ?></td>
                                <td>₹<?php echo number_format($transaction['total_spent'], 2); ?></td>
                                <td>₹<?php echo number_format($transaction['total_available_fund'], 2); ?></td>
                                <td>₹<?php echo number_format($transaction['closing_amount'], 2); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="view.php?id=<?php echo $transaction['id']; ?>" class="btn btn-info">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        <a href="edit.php?id=<?php echo $transaction['id']; ?>" class="btn btn-primary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p class="text-muted">No transactions found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="../../assets/js/jquery.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script>
    // Update datetime with exact format
    function updateDateTime() {
        const now = new Date();
        const formatted = now.getUTCFullYear() + '-' + 
                         String(now.getUTCMonth() + 1).padStart(2, '0') + '-' + 
                         String(now.getUTCDate()).padStart(2, '0') + ' ' + 
                         String(now.getUTCHours()).padStart(2, '0') + ':' + 
                         String(now.getUTCMinutes()).padStart(2, '0') + ':' + 
                         String(now.getUTCSeconds()).padStart(2, '0');
        document.getElementById('current-datetime').textContent = formatted;
    }
    setInterval(updateDateTime, 1000);
    updateDateTime();
    </script>
</body>
</html>