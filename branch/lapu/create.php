<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

checkBranchAuth();
$user = $_SESSION['branch_user'];
$page_title = 'New LAPU Transaction';

$db = new Database();
$conn = $db->getConnection();
$branch_id = $user['branch_id'];
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
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">New LAPU Transaction</h5>
                    </div>
                    <div class="card-body">
                        <form action="process.php" method="POST" onsubmit="return validateForm()">
                            <div class="row g-3">
                                <!-- Transaction Details -->
                                <div class="col-md-6">
                                    <label class="form-label">Cash Received</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₹</span>
                                        <input type="number" step="0.01" class="form-control" 
                                               name="cash_received" id="cash_received" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Auto Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₹</span>
                                        <input type="number" step="0.01" class="form-control" 
                                               name="auto_amount" id="auto_amount" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Opening Balance</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₹</span>
                                        <input type="number" step="0.01" class="form-control" 
                                               name="opening_balance" id="opening_balance" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Total Spent</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₹</span>
                                        <input type="number" step="0.01" class="form-control" 
                                               name="total_spent" id="total_spent" required>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Notes</label>
                                    <textarea class="form-control" name="notes" rows="3"></textarea>
                                </div>

                                <!-- Submit Buttons -->
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Create Transaction</button>
                                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

    // Form validation
    function validateForm() {
        const cashReceived = parseFloat(document.getElementById('cash_received').value) || 0;
        const autoAmount = parseFloat(document.getElementById('auto_amount').value) || 0;
        const openingBalance = parseFloat(document.getElementById('opening_balance').value) || 0;
        const totalSpent = parseFloat(document.getElementById('total_spent').value) || 0;

        if (cashReceived <= 0 || autoAmount <= 0 || openingBalance <= 0 || totalSpent <= 0) {
            alert('All amounts must be greater than zero.');
            return false;
        }
        return true;
    }
    </script>
</body>
</html>