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
    <title>LAPU Reports</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/custom.css" rel="stylesheet">
</head>
<body>
    <!-- Header Info -->
    <div class="header-info">
        <div class="container">
Current Date and Time (UTC - YYYY-MM-DD HH:MM:SS formatted): <span id="current-datetime"><?php echo $currentDateTime; ?></span>
Current User's Login: <span id="current-user"><?php echo $currentUser; ?></span>
lapu.php
transaction.php</div>
    </div>

    <!-- LAPU Report Content -->
    <div class="container mt-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">LAPU Activity Report</h4>
                <div>
                    <button class="btn btn-success me-2" onclick="exportTableToExcel('lapuTable', 'lapu_report.xlsx')">
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
                        <label class="form-label">Activity Type</label>
                        <select class="form-select">
                            <option>All Activities</option>
                            <option>Account Opening</option>
                            <option>KYC Update</option>
                            <option>Transaction</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">LAPU Agent</label>
                        <select class="form-select">
                            <option>All Agents</option>
                            <option>Agent 1</option>
                            <option>Agent 2</option>
                            <option>Agent 3</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select class="form-select">
                            <option>All Status</option>
                            <option>Completed</option>
                            <option>Pending</option>
                            <option>Failed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button class="btn btn-primary d-block w-100">Generate</button>
                    </div>
                </div>

                <!-- Summary Stats -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stats-card">
                            <h6>Total Activities</h6>
                            <h3>1,234</h3>
                            <small class="text-success">↑ 15% vs last month</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <h6>Success Rate</h6>
                            <h3>95.8%</h3>
                            <small class="text-success">↑ 2.3%</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <h6>Active Agents</h6>
                            <h3>45</h3>
                            <small class="text-muted">Currently online</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <h6>Average Time</h6>
                            <h3>12.5m</h3>
                            <small class="text-success">↓ 2.1m</small>
                        </div>
                    </div>
                </div>

                <!-- Performance Chart -->
                <div class="chart-container mb-4">
                    <canvas id="lapuActivityChart"></canvas>
                </div>

                <!-- LAPU Activities Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="lapuTable">
                        <thead>
                            <tr>
                                <th>Activity ID</th>
                                <th>Date & Time</th>
                                <th>Agent Name</th>
                                <th>Activity Type</th>
                                <th>Customer ID</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>LAPU123456</td>
                                <td>2025-03-11 16:58:56</td>
                                <td>John Doe</td>
                                <td>Account Opening</td>
                                <td>CUST123</td>
                                <td>15m 23s</td>
                                <td><span class="badge bg-success">Completed</span></td>
                                <td>
                                    <button class="btn btn-sm btn-info">View</button>
                                    <button class="btn btn-sm btn-secondary">Report</button>
                                </td>
                            </tr>
                            <tr>
                                <td>LAPU123457</td>
                                <td>2025-03-11 16:45:32</td>
                                <td>Jane Smith</td>
                                <td>KYC Update</td>
                                <td>CUST124</td>
                                <td>8m 45s</td>
                                <td><span class="badge bg-warning">Pending</span></td>
                                <td>
                                    <button class="btn btn-sm btn-info">View</button>
                                    <button class="btn btn-sm btn-secondary">Report</button>
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