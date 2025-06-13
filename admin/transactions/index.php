<?php
session_start();
require_once '../../config/config.php';
require_once '../../config/database.php';

// Fetching cash deposits data
try {
    $db = (new Database())->getConnection();
    $stmt = $db->query("
        SELECT 
            cd.id AS transaction_id,
            cd.deposit_date,
            cd.total_amount,
            cd.notes_2000,
            cd.notes_500,
            cd.notes_200,
            cd.notes_100,
            cd.notes_50,
            cd.notes_20,
            cd.notes_10,
            cd.notes_5,
            cd.notes_2,
            cd.notes_1,
            b.branch_name,
            b.branch_code,
            s.full_name AS staff_name
        FROM cash_deposits cd
        JOIN branches b ON cd.branch_id = b.id
        JOIN staff s ON cd.staff_id = s.id
        ORDER BY cd.deposit_date DESC
    ");
    $cashDeposits = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $_SESSION['error'] = "Error fetching cash deposits: " . $e->getMessage();
    $cashDeposits = [];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cash Deposits Management</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Cash Deposits</h1>
        <?php if (isset($_SESSION['error'])) : ?>
            <div class="alert alert-danger">
                <?php 
                    echo $_SESSION['error']; 
                    unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>
        <div class="card">
            <div class="card-header">
                <h5>List of Cash Deposits</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($cashDeposits)) : ?>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Branch Name</th>
                                <th>Branch Code</th>
                                <th>Deposit Date</th>
                                <th>Total Amount</th>
                                <th>Staff Name</th>
                                <th>Denominations</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cashDeposits as $deposit) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($deposit['transaction_id']); ?></td>
                                    <td><?php echo htmlspecialchars($deposit['branch_name']); ?></td>
                                    <td><?php echo htmlspecialchars($deposit['branch_code']); ?></td>
                                    <td><?php echo htmlspecialchars($deposit['deposit_date']); ?></td>
                                    <td><?php echo htmlspecialchars($deposit['total_amount']); ?></td>
                                    <td><?php echo htmlspecialchars($deposit['staff_name']); ?></td>
                                    <td>
                                        <?php
                                            $denominations = [
                                                '2000' => $deposit['notes_2000'],
                                                '500' => $deposit['notes_500'],
                                                '200' => $deposit['notes_200'],
                                                '100' => $deposit['notes_100'],
                                                '50' => $deposit['notes_50'],
                                                '20' => $deposit['notes_20'],
                                                '10' => $deposit['notes_10'],
                                                '5' => $deposit['notes_5'],
                                                '2' => $deposit['notes_2'],
                                                '1' => $deposit['notes_1']
                                            ];
                                            foreach ($denominations as $note => $count) {
                                                if ($count > 0) {
                                                    echo "₹$note x $count<br>";
                                                }
                                            }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <p>No cash deposits found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>