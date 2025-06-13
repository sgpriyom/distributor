<?php
session_start();
require_once '../../config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();

    // Get branches with filters
    $where = "WHERE 1=1";
    $params = [];

    if (isset($_GET['status']) && in_array($_GET['status'], ['active', 'inactive'])) {
        $where .= " AND b.status = ?";
        $params[] = $_GET['status'];
    }

    if (isset($_GET['search'])) {
        $where .= " AND (b.branch_code LIKE ? OR b.branch_name LIKE ? OR b.city LIKE ?)";
        $search = "%" . $_GET['search'] . "%";
        $params = array_merge($params, [$search, $search, $search]);
    }

    $stmt = $conn->prepare("
        SELECT 
            b.*,
            COUNT(bu.id) as total_users,
            COALESCE(SUM(CASE WHEN bu.status = 'active' THEN 1 ELSE 0 END), 0) as active_users
        FROM branches b
        LEFT JOIN branch_users bu ON b.id = bu.branch_id
        $where
        GROUP BY b.id
        ORDER BY b.created_at DESC
    ");

    $stmt->execute($params);
    $branches = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
    $branches = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Management - Distributor Management</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/bootstrap-icons.css" rel="stylesheet">
    <link href="../../assets/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
    :root {
        --header-height: 60px;
        --nav-height: 50px;
        --primary-color: #007bff;
        --secondary-color: #6c757d;
        --light-bg: #f8f9fa;
        --card-shadow: rgba(0, 0, 0, 0.1) 0px 5px 15px;
    }
    body {
        background-color: var(--light-bg);
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        color: #343a40;
    }
    .main-header {
        background: #fff;
        border-bottom: 1px solid #dee2e6;
        box-shadow: var(--card-shadow);
        padding: 0.5rem 1rem;
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
    .datetime-display {
        background: var(--light-bg);
        padding: 0.5rem 1rem;
        border-radius: 5px;
        font-size: 0.95rem;
        font-weight: 500;
        display: flex;
        flex-direction: column;
        align-items: center;
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
        box-shadow: var(--card-shadow);
    }
    .stats-value {
        font-size: 1.5rem;
        font-weight: bold;
        color: #495057;
    }
    .transaction-list {
        max-height: 400px;
        overflow-y: auto;
    }
    .status-badge {
        font-size: 0.85rem;
        padding: 0.35rem 0.65rem;
        border-radius: 3px;
    }
    a {
        color: var(--primary-color);
        transition: color 0.3s ease;
    }
    a:hover {
        color: var(--secondary-color);
        text-decoration: none;
    }
    .navbar-dark .navbar-nav .nav-link.active {
        background-color: var(--primary-color);
        color: #fff !important;
        border-radius: 4px;
    }
    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }
    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        color: #fff;
    }
</style>
</head>
<body>
    <div class="container-fluid mt-4">
        <!-- Quick Stats -->
        <div class="row">
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <h6>Total Branches</h6>
                    <h3><?php echo count($branches); ?></h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <h6>Active Branches</h6>
                    <h3><?php echo count(array_filter($branches, fn($b) => $b['status'] === 'active')); ?></h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <h6>Total Users</h6>
                    <h3><?php echo array_sum(array_column($branches, 'total_users')); ?></h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <h6>Active Users</h6>
                    <h3><?php echo array_sum(array_column($branches, 'active_users')); ?></h3>
                </div>
            </div>
        </div>

        <!-- Branch List -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Branch List</h5>
                <a href="add.php" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i> Add New Branch
                </a>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php 
                            echo $_SESSION['success'];
                            unset($_SESSION['success']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <table id="branchTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Branch Code</th>
                            <th>Branch Name</th>
                            <th>Location</th>
                            <th>Contact</th>
                            <th>Users</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($branches as $branch): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($branch['branch_code']); ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($branch['branch_name']); ?></strong>
                                <br>
                                <small class="text-muted">
                                    <?php echo htmlspecialchars($branch['branch_type']); ?>
                                </small>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($branch['city']); ?>
                                <br>
                                <small class="text-muted">
                                    <?php echo htmlspecialchars($branch['postal_code']); ?>
                                </small>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($branch['contact_number']); ?>
                                <br>
                                <small>
                                    <a href="mailto:<?php echo htmlspecialchars($branch['email']); ?>">
                                        <?php echo htmlspecialchars($branch['email']); ?>
                                    </a>
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-primary">
                                    <?php echo $branch['active_users']; ?>/<?php echo $branch['total_users']; ?>
                                </span>
                            </td>
                            <td>
                                <?php echo ucfirst($branch['status']); ?>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="view.php?id=<?php echo $branch['id']; ?>" 
                                       class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="edit.php?id=<?php echo $branch['id']; ?>" 
                                       class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger"
                                            onclick="confirmDelete(<?php echo $branch['id']; ?>)">
                                        <i class="bi bi-trash"></i>
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

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this branch?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" action="delete.php" method="POST" style="display: inline;">
                        <input type="hidden" name="branch_id" id="deleteBranchId">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/jquery.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/jquery.dataTables.min.js"></script>
    <script src="../../assets/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#branchTable').DataTable();

            // Delete confirmation modal
            window.confirmDelete = function(branchId) {
                $('#deleteBranchId').val(branchId);
                $('#deleteModal').modal('show');
            };
        });
    </script>
	
	<script>
    $(document).ready(function () {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(
            document.querySelectorAll("[data-bs-toggle='tooltip']")
        );
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Update time every second
        function updateTime() {
            const now = new Date();
            let hours = String(now.getUTCHours()).padStart(2, "0");
            let minutes = String(now.getUTCMinutes()).padStart(2, "0");
            let seconds = String(now.getUTCSeconds()).padStart(2, "0");
            $("#currentTime").text(`${hours}:${minutes}:${seconds} UTC`);
        }
        setInterval(updateTime, 1000);

        // Auto-hide alerts after 5 seconds
        $(".alert").delay(5000).fadeOut(500);

        // Animate service cards on hover
        $(".service-card").hover(
            function () {
                $(this).css("transform", "translateY(-10px)");
                $(this).css("box-shadow", "0px 10px 20px rgba(0,0,0,0.1)");
            },
            function () {
                $(this).css("transform", "translateY(0px)");
                $(this).css("box-shadow", "var(--card-shadow)");
            }
        );
    });
</script>
</body>
</html>