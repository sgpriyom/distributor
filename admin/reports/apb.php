<?php
session_start();
$currentDateTime = date('Y-m-d H:i:s');
$currentUser = 'sgpriyom';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APB Reports</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/bootstrap-icons.css" rel="stylesheet">
    <style>
        .header-info {
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 10px 0;
            font-family: monospace;
            font-size: 14px;
            white-space: pre-line;
        }
    </style>
</head>
<body>
    <!-- Header Info -->
    <div class="header-info">
        <div class="container">
Current Date and Time (UTC - YYYY-MM-DD HH:MM:SS formatted): <span id="current-datetime"><?php echo $currentDateTime; ?></span>
Current User's Login: <span id="current-user"><?php echo $currentUser; ?></span>
The remaining report pages (apb.php, dth.php, cash_deposit.php)</div>
    </div>

    <!-- APB Report Content -->
    <div class="container mt-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">APB Transaction Report</h4>
                <div>
                    <button class="btn btn-success me-2">
                        <i class="bi bi-file-excel"></i> Export Report
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-2">
                        <label>Start Date</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label>End Date</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label>Transaction Type</label>
                        <select class="form-select">
                            <option>All Types</option>
                            <option>Credit</option>
                            <option>Debit</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Account Status</label>
                        <select class="form-select">
                            <option>All Status</option>
                            <option>Active</option>
                            <option>Blocked</option>
                            <option>Suspended</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Branch</label>
                        <select class="form-select">
                            <option>All Branches</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button class="btn btn-primary d-block w-100">Generate</button>
                    </div>
                </div>

                <!-- Summary Stats -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6>Total Transactions</h6>
                                <h3>₹45,67,890</h3>
                                <small class="text-muted">Last 30 days</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6>Total Credit</h6>
                                <h3>₹32,45,670</h3>
                                <small class="text-success">↑ 12.5%</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6>Total Debit</h6>
                                <h3>₹13,22,220</h3>
                                <small class="text-danger">↓ 5.2%</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6>Active Accounts</h6>
                                <h3>1,234</h3>
                                <small class="text-muted">Current month</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transactions Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Transaction ID</th>
                                <th>Date & Time</th>
                                <th>Account Number</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Balance</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>APB123456</td>
                                <td>2025-03-11 16:41:09</td>
                                <td>1234567890</td>
                                <td>Credit</td>
                                <td>₹10,000.00</td>
                                <td>₹25,000.00</td>
                                <td><span class="badge bg-success">Success</span></td>
                                <td>
                                    <button class="btn btn-sm btn-info">View</button>
                                    <button class="btn btn-sm btn-secondary">Print</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav class="mt-3">
                    <ul class="pagination justify-content-end">
                        <li class="page-item disabled">
                            <a class="page-link" href="#">Previous</a>
                        </li>
                        <li class="page-item active">
                            <a class="page-link" href="#">1</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">3</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <script>
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