<?php
session_start();
require_once '../config/config.php';
require_once '../config/Database.php';

date_default_timezone_set('UTC');
$currentDateTime = date('Y-m-d H:i:s');

// Initialize variables
$branch = [
    'id' => 0,
    'branch_name' => '',
    'branch_code' => '',
    'address' => '',
    'contact_number' => '',
    'email' => ''
];

$stats = [
    'lapu' => ['count' => 0, 'amount' => 0],
    'sim_cards' => ['count' => 0, 'amount' => 0],
    'apb' => ['count' => 0, 'amount' => 0],
    'dth' => ['count' => 0, 'amount' => 0],
    'cash_deposit' => ['count' => 0, 'amount' => 0]
];

$recent_transactions = [];

// Check if user is logged in
if (!isset($_SESSION['branch_user'])) {
    $_SESSION['error'] = "Please login to continue";
    header('Location: login.php');
    exit;
}

$user = $_SESSION['branch_user'];

// [Previous database queries remain the same]

// Helper functions
function getServiceBadgeClass($service) {
    return match($service) {
        'lapu' => 'primary',
        'sim_cards' => 'success',
        'apb' => 'info',
        'dth' => 'warning',
        'cash_deposit' => 'danger',
        default => 'secondary'
    };
}

function getStatusBadgeClass($status) {
    return match($status) {
        'completed' => 'success',
        'pending' => 'warning',
        'failed' => 'danger',
        'processing' => 'info',
        default => 'secondary'
    };
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($branch['branch_name']); ?> - Branch Dashboard</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --header-height: 60px;
            --nav-height: 50px;
        }
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        .main-header {
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            padding: 0.5rem 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
        }
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .branch-info {
            flex: 1;
            padding-right: 2rem;
        }
        .datetime-display {
            background: #f8f9fa;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-size: 0.9rem;
            line-height: 1.2;
            text-align: center;
            margin-right: 1rem;
        }
        .datetime-display .date {
            font-weight: 600;
            color: #2c3e50;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.5rem 1rem;
            background: #f8f9fa;
            border-radius: 4px;
        }
        .service-card {
            background: #fff;
            border-radius: 10px;
            padding: 1.5rem;
            height: 100%;
            border: 1px solid #e0e0e0;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .nav-item .nav-link {
            padding: 0.8rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .nav-item .nav-link i {
            font-size: 1.1rem;
        }
        .main-content {
            margin-top: calc(var(--header-height) + var(--nav-height));
            padding: 2rem 0;
        }
        .transaction-list {
            max-height: 400px;
            overflow-y: auto;
        }
        .status-badge {
            font-size: 0.85rem;
            padding: 0.35rem 0.65rem;
        }
    </style>
</head>
<body>
    <!-- Main Header -->
    <header class="main-header">
        <div class="container-fluid">
            <div class="header-container">
                <div class="branch-info">
                    <div class="d-flex align-items-center">
                        <img src="../assets/images/logo.png" alt="Logo" height="40" class="me-3">
                        <div>
                            <h4 class="mb-0">
                                <?php echo htmlspecialchars($branch['branch_name']); ?>
                                <small class="text-muted">(<?php echo htmlspecialchars($branch['branch_code']); ?>)</small>
                            </h4>
                            <div class="small text-muted">
                                <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($branch['address']); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="datetime-display">
                    <div class="date">
                        <?php echo date('d M Y', strtotime($currentDateTime)); ?>
                    </div>
                    <div class="time" id="currentTime">
                        <?php echo date('H:i:s', strtotime($currentDateTime)); ?> UTC
                    </div>
                </div>

                <div class="user-info">
                    <div class="d-none d-md-block">
                        <div class="fw-bold"><?php echo htmlspecialchars($user['full_name']); ?></div>
                        <small class="text-muted">@<?php echo htmlspecialchars($user['username']); ?></small>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle fs-4"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="settings.php"><i class="bi bi-gear me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>
	 <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top" style="top: var(--header-height);">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-grid"></i> Services
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="lapu/"><i class="bi bi-phone me-2"></i>LAPU</a></li>
                            <li><a class="dropdown-item" href="sim_cards/"><i class="bi bi-sim me-2"></i>SIM Cards</a></li>
                            <li><a class="dropdown-item" href="apb/"><i class="bi bi-bank me-2"></i>APB</a></li>
                            <li><a class="dropdown-item" href="dth/"><i class="bi bi-broadcast me-2"></i>DTH</a></li>
                            <li><a class="dropdown-item" href="cash_deposit/"><i class="bi bi-cash-stack me-2"></i>Cash Deposit</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="transactions/">
                            <i class="bi bi-credit-card"></i> Transactions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reports/">
                            <i class="bi bi-file-text"></i> Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="customers/">
                            <i class="bi bi-people"></i> Customers
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="settings.php">
                            <i class="bi bi-gear"></i> Settings
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- Service Statistics Overview -->
            <div class="row g-3 mb-4">
                <div class="col-md-4 col-xl-3">
                    <div class="service-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-phone service-icon text-primary me-2"></i>
                                <h5 class="mb-0">LAPU</h5>
                            </div>
                            <span class="badge bg-primary">Today</span>
                        </div>
                        <div class="stats-value">₹<?php echo number_format($stats['lapu']['amount'], 2); ?></div>
                        <small class="text-muted"><?php echo number_format($stats['lapu']['count']); ?> transactions</small>
                        <div class="mt-3">
                            <a href="lapu/" class="btn btn-sm btn-primary w-100">Manage LAPU</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-xl-3">
                    <div class="service-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-sim service-icon text-success me-2"></i>
                                <h5 class="mb-0">SIM Cards</h5>
                            </div>
                            <span class="badge bg-success">Today</span>
                        </div>
                        <div class="stats-value">₹<?php echo number_format($stats['sim_cards']['amount'], 2); ?></div>
                        <small class="text-muted"><?php echo number_format($stats['sim_cards']['count']); ?> activations</small>
                        <div class="mt-3">
                            <a href="sim_cards/" class="btn btn-sm btn-success w-100">Manage SIM Cards</a>
                        </div>
                    </div>
                </div>
				<div class="row g-3 mb-4">
				
       <!-- APB Service Card -->
    <div class="col-md-4 col-xl-3">
        <div class="service-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-bank service-icon text-info me-2"></i>
                    <h5 class="mb-0">APB</h5>
                </div>
                <span class="badge bg-info">Today</span>
            </div>
            <div class="stats-value">₹<?php echo number_format($stats['apb']['amount'], 2); ?></div>
            <small class="text-muted"><?php echo number_format($stats['apb']['count']); ?> transactions</small>
            <div class="mt-3">
                <a href="apb/" class="btn btn-sm btn-info w-100">Manage APB</a>
            </div>
        </div>
    </div>

    <!-- DTH Service Card -->
    <div class="col-md-4 col-xl-3">
        <div class="service-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-broadcast service-icon text-warning me-2"></i>
                    <h5 class="mb-0">DTH</h5>
                </div>
                <span class="badge bg-warning">Today</span>
            </div>
            <div class="stats-value">₹<?php echo number_format($stats['dth']['amount'], 2); ?></div>
            <small class="text-muted"><?php echo number_format($stats['dth']['count']); ?> recharges</small>
            <div class="mt-3">
                <a href="dth/" class="btn btn-sm btn-warning w-100">Manage DTH</a>
            </div>
        </div>
    </div>

    <!-- Cash Deposit Service Card -->
    <div class="col-md-4 col-xl-3">
        <div class="service-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-cash-stack service-icon text-danger me-2"></i>
                    <h5 class="mb-0">Cash Deposit</h5>
                </div>
                <span class="badge bg-danger">Today</span>
            </div>
            <div class="stats-value">₹<?php echo number_format($stats['cash_deposit']['amount'], 2); ?></div>
            <small class="text-muted"><?php echo number_format($stats['cash_deposit']['count']); ?> deposits</small>
            <div class="mt-3">
                <a href="cash_deposit/" class="btn btn-sm btn-danger w-100">New Deposit</a>
            </div>
        </div>
    </div>

    <!-- Total Transactions Today -->
    <div class="col-md-4 col-xl-3">
        <div class="service-card bg-light">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-graph-up service-icon text-dark me-2"></i>
                    <h5 class="mb-0">Total Today</h5>
                </div>
                <span class="badge bg-dark">Summary</span>
            </div>
            <div class="stats-value">₹<?php 
                $total_amount = array_sum(array_column($stats, 'amount'));
                echo number_format($total_amount, 2); 
            ?></div>
            <small class="text-muted"><?php 
                $total_count = array_sum(array_column($stats, 'count'));
                echo number_format($total_count); 
            ?> total transactions</small>
            <div class="mt-3">
                <a href="transactions/" class="btn btn-sm btn-dark w-100">View All Transactions</a>
            </div>
        </div>
    </div>
</div>


                <!-- Similar cards for APB, DTH, and Cash Deposit -->
                <!-- [Previous service cards code with updated styling] -->

            </div>

            <!-- Recent Transactions -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Recent Transactions</h5>
                    <a href="transactions/" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body px-0 pb-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Service</th>
                                    <th>Customer</th>
                                    <th class="text-end">Amount</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($recent_transactions as $trans): ?>
                                <tr>
                                    <td><?php echo date('H:i', strtotime($trans['created_at'])); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo getServiceBadgeClass($trans['service_type']); ?>">
                                            <?php echo ucfirst($trans['service_type']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($trans['customer_name']); ?></td>
                                    <td class="text-end">₹<?php echo number_format($trans['amount'], 2); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo getStatusBadgeClass($trans['status']); ?> status-badge">
                                            <?php echo ucfirst($trans['status']); ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="transactions/view.php?id=<?php echo $trans['id']; ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Update time every second
            function updateTime() {
                const now = new Date();
                let hours = String(now.getUTCHours()).padStart(2, '0');
                let minutes = String(now.getUTCMinutes()).padStart(2, '0');
                let seconds = String(now.getUTCSeconds()).padStart(2, '0');
                $('#currentTime').text(`${hours}:${minutes}:${seconds} UTC`);
            }
            setInterval(updateTime, 1000);

            // Auto-hide alerts after 5 seconds
            $('.alert').delay(5000).fadeOut(500);
        });
    </script>
</body>
</html>