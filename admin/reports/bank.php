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
    <title>Bank Reports</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/custom.css" rel="stylesheet">
</head>
<body>
    <!-- Header Info -->
    <div class="header-info">
        <div class="container">
Current Date and Time (UTC - YYYY-MM-DD HH:MM:SS formatted): <span id="current-datetime"><?php echo $currentDateTime; ?></span>
Current User's Login: <span id="current-user"><?php echo $currentUser; ?></span>
bank.php
lapu.php & transaction.php</div>
    </div>

    <!-- Bank Report Content -->
    <div class="container mt-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Bank Transaction Report</h4>
                <div>
                    <button class="btn btn-success me-2" onclick="exportTableToExcel('bankTable', 'bank_report.xlsx')">
                        <i class="bi bi-file-excel"></i> Export Excel
                    </button>
                    <button class="btn btn-info" onclick="printElement('reportContent')">
                        <i class="bi bi-printer"></i> Print Report
                    </button>
                </div>
            </div>
            <div class="card-body" id="reportContent">
                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-2">
                        <label class="form-label">Start Date</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">End Date</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Bank</label>
                        <select class="form-select">
                            <option>All Banks</option>
                            <option>SBI</option>
                            <option>HDFC</option>
                            <option>ICICI</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Transaction Type</label>
                        <select class="form-select">
                            <option>All Types</option>
                            <option>NEFT</option>
                            <option>RTGS</option>
                            <option>IMPS</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select class="form-select">
                            <option>All Status</option>
                            <option>Success</option>
                            <option>Pending</option>
                            <option>Failed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button class="btn btn-primary d-block w-100">Search</button>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stats-card">
                            <h6>Total Transactions</h6>
                            <h3>₹45,67,890</h3>
                            <small class="text-success">↑ 12.5% vs last month</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <h6>Success Rate</h6>
                            <h3>98.7%</h3>
                            <small class="text-success">↑ 0.5%</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <h6>Average Time</h6>
                            <h3>2.3s</h3>
                            <small class="text-success">↓ 0.1s</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <h6>Active Banks</h6>
                            <h3>15</h3>
                            <small class="text-muted">Connected</small>
                        </div>
                    </div>
                </div>

                <!-- Bank Transactions Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="bankTable">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Date & Time</th>
                                <th>Bank</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Account No.</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>TXN123456</td>
                                <td>2025-03-11 16:57:45</td>
                                <td>SBI</td>
                                <td>NEFT</td>
                                <td>₹25,000.00</td>
                                <td>XXXXXX1234</td>
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

    <!-- Scripts -->
    <script src="../../assets/js/jquery.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/custom.js"></script>
</body>
</html>