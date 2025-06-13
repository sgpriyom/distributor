<?php
session_start();
require_once '../../config/config.php';
require_once '../../config/Database.php';

$currentDateTime = "2025-03-12 07:09:22";
$currentUser = "sgpriyom";

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Get users with branch details and activity info
    $query = "
        SELECT 
            bu.*,
            b.branch_name,
            b.branch_code,
            COALESCE(l.login_count, 0) as login_count,
            COALESCE(t.transaction_count, 0) as transaction_count
        FROM branch_users bu
        LEFT JOIN branches b ON bu.branch_id = b.id
        LEFT JOIN (
            SELECT user_id, COUNT(*) as login_count
            FROM activity_logs
            WHERE action = 'login'
            AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY user_id
        ) l ON bu.id = l.user_id
        LEFT JOIN (
            SELECT created_by, COUNT(*) as transaction_count
            FROM transactions
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY created_by
        ) t ON bu.id = t.created_by
        ORDER BY bu.created_at DESC
    ";
    
    $stmt = $db->query($query);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch(Exception $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
    $users = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Users Management</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="../../assets/css/custom.css" rel="stylesheet">
    <style>
        .header-info {
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 10px 0;
            font-family: monospace;
            font-size: 14px;
            white-space: pre-line;
        }
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #6c757d;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        .badge-role {
            font-size: 0.8rem;
            padding: 0.4em 0.8em;
        }
        .stats-card {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <!-- Header Info -->
    <div class="header-info">
        <div class="container-fluid">
Current Date and Time (UTC - YYYY-MM-DD HH:MM:SS formatted): <?php echo $currentDateTime; ?>
Current User's Login: <?php echo $currentUser; ?>
admin/branch/users.php</div>
    </div>

    <div class="container-fluid mt-4">
        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <h6>Total Users</h6>
                    <h3><?php echo count($users); ?></h3>
                    <small class="text-muted">Across all branches</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h6>Active Users</h6>
                    <h3><?php 
                        echo count(array_filter($users, function($user) {
                            return $user['status'] === 'active';
                        }));
                    ?></h3>
                    <small class="text-success">Currently active</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h6>Today's Logins</h6>
                    <h3><?php 
                        echo array_sum(array_column($users, 'login_count'));
                    ?></h3>
                    <small class="text-primary">Last 30 days</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h6>Transactions</h6>
                    <h3><?php 
                        echo array_sum(array_column($users, 'transaction_count'));
                    ?></h3>
                    <small class="text-info">Last 30 days</small>
                </div>
            </div>
        </div>

        <!-- Users List -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Branch Users</h5>
                <div>
                    <button class="btn btn-success me-2" id="exportBtn">
                        <i class="bi bi-download"></i> Export
                    </button>
                    <a href="add-user.php" class="btn btn-primary">
                        <i class="bi bi-person-plus"></i> Add New User
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php if(isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table id="usersTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Name & Contact</th>
                                <th>Branch</th>
                                <th>Role</th>
                                <th>Activity</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-2">
                                                <?php echo strtoupper(substr($user['full_name'], 0, 2)); ?>
                                            </div>
                                            <div>
                                                <?php echo htmlspecialchars($user['full_name']); ?>
                                                <br>
                                                <small>
                                                    <a href="mailto:<?php echo $user['email']; ?>">
                                                        <?php echo htmlspecialchars($user['email']); ?>
                                                    </a>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($user['branch_name']); ?>
                                        <br>
                                        <small class="text-muted"><?php echo htmlspecialchars($user['branch_code']); ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo getRoleBadgeClass($user['role']); ?> badge-role">
                                            <?php echo ucwords(str_replace('_', ' ', $user['role'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small>
                                            Logins: <?php echo $user['login_count']; ?><br>
                                            Transactions: <?php echo $user['transaction_count']; ?>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $user['status'] === 'active' ? 'success' : 'danger'; ?>">
                                            <?php echo ucfirst($user['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="view-user.php?id=<?php echo $user['id']; ?>" 
                                               class="btn btn-sm btn-info" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="edit-user.php?id=<?php echo $user['id']; ?>" 
                                               class="btn btn-sm btn-warning" title="Edit User">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger"
                                                    onclick="confirmDeactivate(<?php echo $user['id']; ?>, '<?php echo addslashes($user['full_name']); ?>')"
                                                    <?php echo $user['status'] === 'inactive' ? 'disabled' : ''; ?>
                                                    title="Deactivate User">
                                                <i class="bi bi-person-x"></i>
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-sm btn-secondary"
                                                    onclick="resetPassword(<?php echo $user['id']; ?>, '<?php echo addslashes($user['full_name']); ?>')"
                                                    title="Reset Password">
                                                <i class="bi bi-key"></i>
                                            </button>
                                        </div>
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
    <script src="../../assets/js/jquery.dataTables.min.js"></script>
    <script src="../../assets/js/dataTables.bootstrap5.min.js"></script>
    <script src="../../assets/js/xlsx.full.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#usersTable').DataTable({
                "pageLength": 25,
                "order": [[0, "desc"]]
            });

            // Export functionality
            $('#exportBtn').click(function() {
                var wb = XLSX.utils.table_to_book(document.getElementById('usersTable'), {
                    sheet: "Branch Users"
                });
                XLSX.writeFile(wb, 'branch_users_' + new Date().toISOString().slice(0,10) + '.xlsx');
            });
        });

        function confirmDeactivate(userId, userName) {
            if (confirm(`Are you sure you want to deactivate user "${userName}"?`)) {
                window.location.href = `deactivate-user.php?id=${userId}`;
            }
        }

        function resetPassword(userId, userName) {
            if (confirm(`Are you sure you want to reset password for user "${userName}"?`)) {
                window.location.href = `reset-password.php?id=${userId}`;
            }
        }
    </script>

    <?php
    function getRoleBadgeClass($role) {
        return match($role) {
            'branch_manager' => 'primary',
            'cashier' => 'success',
            'operator' => 'info',
            default => 'secondary'
        };
    }
    ?>
</body>
</html>