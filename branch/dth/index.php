<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

checkBranchAuth();

$user = $_SESSION['branch_user'];
$page_title = 'DTH Transactions';

$db = new Database();
$conn = $db->getConnection();
$branch_id = $user['branch_id'];

// Fetch DTH transactions
$stmt = $conn->prepare("
    SELECT * FROM dth 
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
    <div class="container-fluid py-4">
        <h4><?php echo $page_title; ?></h4>
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">DTH Transactions</h5>
                <a href="create.php" class="btn btn-primary float-end">
                    <i class="bi bi-plus-circle"></i> Add New Transaction
                </a>
            </div>
            <div class="card-body">
                <?php if (!empty($transactions)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Transaction Date</th>
                                <th>Amount Received</th>
                                <th>Opening Balance</th>
                                <th>Auto Amount</th>
                                <th>Total Available Fund</th>
                                <th>Total Spent</th>
                                <th>Closing Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $index => $transaction): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($transaction['transaction_date']); ?></td>
                                <td><?php echo number_format($transaction['amount_received'], 2); ?></td>
                                <td><?php echo number_format($transaction['opening_balance'], 2); ?></td>
                                <td><?php echo number_format($transaction['auto_amount'], 2); ?></td>
                                <td><?php echo number_format($transaction['total_available_fund'], 2); ?></td>
                                <td><?php echo number_format($transaction['total_spent'], 2); ?></td>
                                <td><?php echo number_format($transaction['closing_amount'], 2); ?></td>
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
                <p class="text-muted">No DTH transactions found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>