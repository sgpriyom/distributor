<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

checkBranchAuth();

$user = $_SESSION['branch_user'];
$page_title = 'SIM Card Transactions';

$db = new Database();
$conn = $db->getConnection();
$branch_id = $user['branch_id'];

// Fetch SIM card transactions
$stmt = $conn->prepare("
    SELECT * FROM sim_cards 
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
                <h5 class="card-title mb-0">SIM Card Transactions</h5>
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
                                <th>Quantity Received</th>
                                <th>Opening Stock</th>
                                <th>Auto Quantity</th>
                                <th>Total Available</th>
                                <th>Total Sold</th>
                                <th>Closing Stock</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $index => $transaction): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($transaction['transaction_date']); ?></td>
                                <td><?php echo htmlspecialchars($transaction['quantity_received']); ?></td>
                                <td><?php echo htmlspecialchars($transaction['opening_stock']); ?></td>
                                <td><?php echo htmlspecialchars($transaction['auto_quantity']); ?></td>
                                <td><?php echo htmlspecialchars($transaction['total_available']); ?></td>
                                <td><?php echo htmlspecialchars($transaction['total_sold']); ?></td>
                                <td><?php echo htmlspecialchars($transaction['closing_stock']); ?></td>
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
                <p class="text-muted">No SIM card transactions found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>