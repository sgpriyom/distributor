<?php
session_start();
require_once '../../config/config.php';
require_once '../../config/database.php';

$currentDateTime = date('Y-m-d H:i:s');
$currentUser = 'subhanmimi';

// Fetching staff performance data dynamically
try {
    $db = (new Database())->getConnection();

    // Fetch total staff count
    $stmt = $db->query("SELECT COUNT(*) AS total_staff FROM staff WHERE status = 'active'");
    $staffSummary = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch top performers and those needing training
    $stmt = $db->query("
        SELECT
            COUNT(CASE WHEN performance_score > 90 THEN 1 END) AS top_performers,
            COUNT(CASE WHEN performance_score < 70 THEN 1 END) AS training_needed,
            AVG(performance_score) AS avg_performance
        FROM staff
        WHERE status = 'active'
    ");
    $performanceSummary = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch detailed staff performance data
    $stmt = $db->query("
        SELECT
            s.id AS employee_id,
            s.full_name AS name,
            d.name AS department,
            s.transaction_count AS transactions,
            s.success_rate,
            s.customer_rating,
            s.performance_score,
            s.status
        FROM staff s
        LEFT JOIN departments d ON s.department_id = d.id
        WHERE s.status = 'active'
        ORDER BY s.performance_score DESC
    ");
    $staffDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $_SESSION['error'] = "Error fetching staff data: " . $e->getMessage();
    $staffSummary = ['total_staff' => 0];
    $performanceSummary = ['top_performers' => 0, 'training_needed' => 0, 'avg_performance' => 0];
    $staffDetails = [];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Reports</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/custom.css" rel="stylesheet">
</head>
<body>
    <!-- Header Info -->
    <div class="header-info">
        <div class="container">
            Current Date and Time (UTC - YYYY-MM-DD HH:MM:SS formatted): <span id="current-datetime"><?php echo $currentDateTime; ?></span>
            Current User's Login: <span id="current-user"><?php echo $currentUser; ?></span>
        </div>
    </div>

    <!-- Staff Report Content -->
    <div class="container mt-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Staff Performance Report</h4>
                <div>
                    <button class="btn btn-success me-2" onclick="exportTableToExcel('staffTable', 'staff_report.xlsx')">
                        <i class="bi bi-file-excel"></i> Export Excel
                    </button>
                    <button class="btn btn-info" onclick="printElement('reportContent')">
                        <i class="bi bi-printer"></i> Print Report
                    </button>
                </div>
            </div>
            <div class="card-body" id="reportContent">
                <!-- Performance Summary -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stats-card">
                            <h6>Total Staff</h6>
                            <h3><?php echo $staffSummary['total_staff']; ?></h3>
                            <small class="text-muted">Active employees</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <h6>Average Performance</h6>
                            <h3><?php echo number_format($performanceSummary['avg_performance'], 2); ?>%</h3>
                            <small class="text-success">↑ vs last month</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <h6>Top Performers</h6>
                            <h3><?php echo $performanceSummary['top_performers']; ?></h3>
                            <small class="text-muted">>90% rating</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <h6>Training Needed</h6>
                            <h3><?php echo $performanceSummary['training_needed']; ?></h3>
                            <small class="text-warning"><70% rating</small>
                        </div>
                    </div>
                </div>

                <!-- Staff Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="staffTable">
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Transactions</th>
                                <th>Success Rate</th>
                                <th>Customer Rating</th>
                                <th>Performance Score</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($staffDetails as $staff) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($staff['employee_id']); ?></td>
                                    <td><?php echo htmlspecialchars($staff['name']); ?></td>
                                    <td><?php echo htmlspecialchars($staff['department']); ?></td>
                                    <td><?php echo htmlspecialchars($staff['transactions']); ?></td>
                                    <td><?php echo htmlspecialchars($staff['success_rate']); ?>%</td>
                                    <td><?php echo htmlspecialchars($staff['customer_rating']); ?>/5</td>
                                    <td><?php echo htmlspecialchars($staff['performance_score']); ?>%</td>
                                    <td>
                                        <?php if ($staff['performance_score'] > 90) : ?>
                                            <span class="badge bg-success">Excellent</span>
                                        <?php elseif ($staff['performance_score'] >= 70) : ?>
                                            <span class="badge bg-primary">Good</span>
                                        <?php else : ?>
                                            <span class="badge bg-warning">Needs Improvement</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../../assets/js/jquery.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/custom.js"></script>
</body>
</html>