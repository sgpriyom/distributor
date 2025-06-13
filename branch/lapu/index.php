<?php
require_once '../../includes/config.php';
require_once '../../includes/database.php';
require_once '../../includes/auth.php'; // Include auth.php for checkBranchAuth()

// Verify branch authentication
checkBranchAuth();

$user = $_SESSION['branch_user'];
$page_title = 'LAPU Management';

$db = new Database();
$conn = $db->getConnection();
$branch_id = $user['branch_id'];
$today = date('Y-m-d');

// Fetch today's statistics
$stmt = $conn->prepare("
    SELECT 
        SUM(cash_received) as total_cash,
        SUM(opening_balance) as total_opening,
        SUM(auto_amount) as total_auto,
        SUM(total_available_fund) as total_available,
        SUM(total_spent) as total_spent,
        SUM(closing_amount) as total_closing
    FROM lapu 
    WHERE branch_id = ? 
    AND transaction_date = ?
");
$stmt->execute([$branch_id, $today]);
$stats = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Header -->
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

    <!-- Content -->
    <div class="container-fluid">
        <!-- Statistics -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Today's Cash Received</h6>
                        <h3 class="mb-0">₹<?php echo number_format($stats['total_cash'] ?? 0, 2); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Total Available Fund</h6>
                        <h3 class="mb-0">₹<?php echo number_format($stats['total_available'] ?? 0, 2); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Closing Amount</h6>
                        <h3 class="mb-0">₹<?php echo number_format($stats['total_closing'] ?? 0, 2); ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="card mb-4">
            <div class="card-body">
                <a href="create.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> New Transaction
                </a>
                <a href="transactions.php" class="btn btn-info">
                    <i class="bi bi-list"></i> View All Transactions
                </a>
                <a href="reports.php" class="btn btn-secondary">
                    <i class="bi bi-file-text"></i> Generate Report
                </a>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent LAPU Transactions</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Cash Received</th>
                                <th>Opening Balance</th>
                                <th>Auto Amount</th>
                                <th>Total Available</th>
                                <th>Total Spent</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $conn->prepare("
                                SELECT * FROM lapu
                                WHERE branch_id = ?
                                ORDER BY transaction_date DESC, created_at DESC
                                LIMIT 10
                            ");
                            $stmt->execute([$branch_id]);
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                            ?>
                            <tr>
                                <td><?php echo date('Y-m-d H:i:s', strtotime($row['transaction_date'])); ?></td>
                                <td>₹<?php echo number_format($row['cash_received'], 2); ?></td>
                                <td>₹<?php echo number_format($row['opening_balance'], 2); ?></td>
                                <td>₹<?php echo number_format($row['auto_amount'], 2); ?></td>
                                <td>₹<?php echo number_format($row['total_available_fund'], 2); ?></td>
                                <td>₹<?php echo number_format($row['total_spent'], 2); ?></td>
                                <td>
                                    <a href="view.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">
                                        View
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Update datetime
    function updateDateTime() {
        const now = new Date();
        document.getElementById('current-datetime').textContent =
            now.toISOString().replace('T', ' ').split('.')[0] + ' UTC';
    }
    setInterval(updateDateTime, 1000);
    </script>
</body>
</html>