<?php
// Initialize the session
session_start();



// Include configuration files
require_once '../config/config.php';
require_once '../config/Database.php';

// Set current page and user info
$currentPage = 'dashboard';
$pageTitle = 'Dashboard - ' . SITE_NAME;
$currentUser = $_SESSION['username'] ?? 'Guest';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Include the header
require_once '../includes/header.php';
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/custom.css" rel="stylesheet">
</head>
<body>
    

    <!-- Dashboard Content -->
	
<div class="container mt-4">
    <!-- Today's Branch Cash Summary -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Today's Branch-wise Cash Deposits</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Branch</th>
                                    <th>Opening Balance</th>
                                    <th>Cash In</th>
                                    <th>Cash Deposit</th>
                                    <th>Current Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                try {
                                    $stmt = $db->query("
                                        SELECT 
                                            b.branch_name,
                                            COALESCE(ba.opening_balance, 0) as opening_balance,
                                            COALESCE(cd.total_amount, 0) as cash_deposit,
                                            COALESCE(t.cash_in, 0) as cash_in,
                                            COALESCE(ba.current_balance, 0) as current_balance
                                        FROM branches b
                                        LEFT JOIN bank_accounts ba ON b.id = ba.branch_id
                                        LEFT JOIN (
                                            SELECT branch_id, SUM(total_amount) as total_amount 
                                            FROM cash_deposits 
                                            WHERE DATE(deposit_date) = CURDATE()
                                            GROUP BY branch_id
                                        ) cd ON b.id = cd.branch_id
                                        LEFT JOIN (
                                            SELECT branch_id, SUM(credit) as cash_in 
                                            FROM transactions 
                                            WHERE DATE(transaction_date) = CURDATE()
                                            GROUP BY branch_id
                                        ) t ON b.id = t.branch_id
                                        ORDER BY b.branch_name
                                    ");
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['branch_name']) . "</td>";
                                        echo "<td>₹" . number_format($row['opening_balance'], 2) . "</td>";
                                        echo "<td>₹" . number_format($row['cash_in'], 2) . "</td>";
                                        echo "<td>₹" . number_format($row['cash_deposit'], 2) . "</td>";
                                        echo "<td>₹" . number_format($row['current_balance'], 2) . "</td>";
                                        echo "</tr>";
                                    }
                                } catch(PDOException $e) {
                                    echo "<tr><td colspan='5' class='text-center'>No data available</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Status Summary -->
    <div class="row mb-4">
        <!-- LAPU Summary -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">LAPU Status</h6>
                </div>
                <div class="card-body">
                    <?php
                    try {
                        $stmt = $db->query("
                            SELECT 
                                SUM(opening_balance) as opening_balance,
                                SUM(cash_received) as received,
                                SUM(total_spent) as spent,
                                SUM(closing_amount) as closing
                            FROM lapu 
                            WHERE DATE(transaction_date) = CURDATE()
                        ");
                        $lapu = $stmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Opening Balance:</span>
                        <strong>₹<?php echo number_format($lapu['opening_balance'] ?? 0, 2); ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Today's Sales:</span>
                        <strong>₹<?php echo number_format($lapu['spent'] ?? 0, 2); ?></strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Closing Balance:</span>
                        <strong>₹<?php echo number_format($lapu['closing'] ?? 0, 2); ?></strong>
                    </div>
                    <?php
                    } catch(PDOException $e) {
                        echo "Data unavailable";
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- DTH Summary -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">DTH Status</h6>
                </div>
                <div class="card-body">
                    <?php
                    try {
                        $stmt = $db->query("
                            SELECT 
                                SUM(opening_balance) as opening_balance,
                                SUM(amount_received) as received,
                                SUM(total_spent) as spent,
                                SUM(closing_amount) as closing
                            FROM dth 
                            WHERE DATE(transaction_date) = CURDATE()
                        ");
                        $dth = $stmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Opening Balance:</span>
                        <strong>₹<?php echo number_format($dth['opening_balance'] ?? 0, 2); ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Today's Sales:</span>
                        <strong>₹<?php echo number_format($dth['spent'] ?? 0, 2); ?></strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Closing Balance:</span>
                        <strong>₹<?php echo number_format($dth['closing'] ?? 0, 2); ?></strong>
                    </div>
                    <?php
                    } catch(PDOException $e) {
                        echo "Data unavailable";
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- SIM Card Summary -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">SIM Card Status</h6>
                </div>
                <div class="card-body">
                    <?php
                    try {
                        $stmt = $db->query("
                            SELECT 
                                SUM(opening_stock) as opening_stock,
                                SUM(quantity_received) as received,
                                SUM(total_sold) as sold,
                                SUM(closing_stock) as closing
                            FROM sim_cards 
                            WHERE DATE(transaction_date) = CURDATE()
                        ");
                        $sim = $stmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Opening Stock:</span>
                        <strong><?php echo number_format($sim['opening_stock'] ?? 0); ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Today's Sales:</span>
                        <strong><?php echo number_format($sim['sold'] ?? 0); ?></strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Closing Stock:</span>
                        <strong><?php echo number_format($sim['closing'] ?? 0); ?></strong>
                    </div>
                    <?php
                    } catch(PDOException $e) {
                        echo "Data unavailable";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- APB Status -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">APB Summary</h6>
                </div>
                <div class="card-body">
                    <?php
                    try {
                        $stmt = $db->query("
                            SELECT 
                                b.branch_name,
                                COALESCE(a.opening_stock, 0) as opening_stock,
                                COALESCE(a.quantity_received, 0) as received,
                                COALESCE(a.total_sold, 0) as sold,
                                COALESCE(a.closing_stock, 0) as closing
                            FROM branches b
                            LEFT JOIN apb a ON b.id = a.branch_id 
                            AND DATE(a.transaction_date) = CURDATE()
                            ORDER BY b.branch_name
                        ");
                    ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Branch</th>
                                    <th>Opening Stock</th>
                                    <th>Received</th>
                                    <th>Sold</th>
                                    <th>Closing Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['branch_name']) . "</td>";
                                    echo "<td>" . number_format($row['opening_stock']) . "</td>";
                                    echo "<td>" . number_format($row['received']) . "</td>";
                                    echo "<td>" . number_format($row['sold']) . "</td>";
                                    echo "<td>" . number_format($row['closing']) . "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                    } catch(PDOException $e) {
                        echo "Data unavailable";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

	
    <div class="container mt-4">
        <!-- Stats Row -->
        <div class="row mb-4">
            <!-- Branch Stats -->
            <div class="col-md-3">
                <div class="stats-card">
                    <h6>Branches</h6>
                    <?php
                    try {
                        $stmt = $db->query("SELECT COUNT(*) as total FROM branches");
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        echo "<h3>" . ($result['total'] ?? 0) . "</h3>";
                    } catch(PDOException $e) {
                        echo "<h3>0</h3>";
                    }
                    ?>
                    <small class="text-muted">Total Branches</small>
                </div>
            </div>
            <!-- Staff Stats -->
            <div class="col-md-3">
                <div class="stats-card">
                    <h6>Staff</h6>
                    <?php
                    try {
                        $stmt = $db->query("SELECT COUNT(*) as total FROM staff");
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        echo "<h3>" . ($result['total'] ?? 0) . "</h3>";
                    } catch(PDOException $e) {
                        echo "<h3>0</h3>";
                    }
                    ?>
                    <small class="text-muted">Total Staff</small>
                </div>
            </div>
            <!-- Today's Transactions -->
            <div class="col-md-3">
                <div class="stats-card">
                    <h6>Today's Transactions</h6>
                    <?php
                    try {
                        $stmt = $db->query("SELECT COUNT(*) as total FROM transactions WHERE DATE(transaction_date) = CURDATE()");
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        echo "<h3>" . ($result['total'] ?? 0) . "</h3>";
                    } catch(PDOException $e) {
                        echo "<h3>0</h3>";
                    }
                    ?>
                    <small class="text-muted">Total Today</small>
                </div>
            </div>
            <!-- Bank Accounts -->
            <div class="col-md-3">
                <div class="stats-card">
                    <h6>Bank Accounts</h6>
                    <?php
                    try {
                        $stmt = $db->query("SELECT COUNT(*) as total FROM bank_accounts");
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        echo "<h3>" . ($result['total'] ?? 0) . "</h3>";
                    } catch(PDOException $e) {
                        echo "<h3>0</h3>";
                    }
                    ?>
                    <small class="text-muted">Active Accounts</small>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="branch/add.php" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-building"></i><br>
                            Add Branch
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="branch/add-user.php" class="btn btn-success btn-lg w-100">
                            <i class="bi bi-person-plus-fill"></i><br>
                            Add Branch User
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="staff/add.php" class="btn btn-info btn-lg w-100">
                            <i class="bi bi-people"></i><br>
                            Add Staff
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="bank/add.php" class="btn btn-warning btn-lg w-100">
                            <i class="bi bi-bank"></i><br>
                            Add Bank Account
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="reports/" class="btn btn-danger btn-lg w-100">
                            <i class="bi bi-file-text"></i><br>
                            View Reports
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="branch/users.php" class="btn btn-secondary btn-lg w-100">
                            <i class="bi bi-people-fill"></i><br>
                            Manage Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="branch/add.php" class="btn btn-primary btn-lg w-100">
                                    <i class="bi bi-building"></i><br>
                                    Add Branch
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="staff/add.php" class="btn btn-success btn-lg w-100">
                                    <i class="bi bi-person-plus"></i><br>
                                    Add Staff
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="bank/add.php" class="btn btn-info btn-lg w-100">
                                    <i class="bi bi-bank"></i><br>
                                    Add Bank Account
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="reports/" class="btn btn-warning btn-lg w-100">
                                    <i class="bi bi-file-text"></i><br>
                                    View Reports
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Data -->
        <div class="row">
            <!-- Recent Transactions -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Transactions</h5>
                        <a href="transactions/" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Branch</th>
                                        <th>Staff</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    try {
                                        $stmt = $db->query("
                                            SELECT t.*, b.branch_name, s.name as staff_name
                                            FROM transactions t
                                            LEFT JOIN branches b ON t.branch_id = b.id
                                            LEFT JOIN staff s ON t.staff_id = s.id
                                            ORDER BY t.created_at DESC LIMIT 5
                                        ");
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<tr>";
                                            echo "<td>" . date('Y-m-d', strtotime($row['created_at'])) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['branch_name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['staff_name']) . "</td>";
                                            echo "<td>₹" . number_format($row['credit'] - $row['debit'], 2) . "</td>";
                                            echo "<td><span class='badge bg-success'>Completed</span></td>";
                                            echo "</tr>";
                                        }
                                    } catch(PDOException $e) {
                                        echo "<tr><td colspan='5' class='text-center'>No transactions found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
			

            <!-- System Status -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">System Status</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Database Connection</span>
                                    <span class="badge bg-success">Connected</span>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Last Update</span>
                                    <span class="text-muted"><?php echo date('H:i:s'); ?></span>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>System Status</span>
                                    <span class="badge bg-success">Online</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
		</div>
<?php
if (!defined('SITE_NAME')) {
    exit('Direct script access denied.');
}
?>
    <footer class="footer mt-auto py-3 bg-light">
        <div class="container">
            <span class="text-muted">&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</span>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="<?php echo SITE_URL; ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo SITE_URL; ?>assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo SITE_URL; ?>assets/js/custom.js"></script>
	
</body>

</html>