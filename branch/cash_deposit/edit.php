<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

checkBranchAuth();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Transaction ID is required.";
    header('Location: index.php');
    exit;
}

$transaction_id = intval($_GET['id']);
$db = new Database();
$conn = $db->getConnection();
$branch_id = $_SESSION['branch_user']['branch_id'];

// Fetch transaction details
$stmt = $conn->prepare("
    SELECT * FROM cash_deposits 
    WHERE id = ? AND branch_id = ?
");
$stmt->execute([$transaction_id, $branch_id]);
$transaction = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$transaction) {
    $_SESSION['error'] = "Transaction not found or you don't have permission to edit it.";
    header('Location: index.php');
    exit;
}

$page_title = 'Edit Cash Deposit Transaction';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deposit_date = isset($_POST['deposit_date']) ? trim($_POST['deposit_date']) : '';
    $bank_account_id = isset($_POST['bank_account_id']) ? intval($_POST['bank_account_id']) : 0;
    $notes_2000 = isset($_POST['notes_2000']) ? intval($_POST['notes_2000']) : 0;
    $notes_500 = isset($_POST['notes_500']) ? intval($_POST['notes_500']) : 0;
    $notes_200 = isset($_POST['notes_200']) ? intval($_POST['notes_200']) : 0;
    $notes_100 = isset($_POST['notes_100']) ? intval($_POST['notes_100']) : 0;
    $notes_50 = isset($_POST['notes_50']) ? intval($_POST['notes_50']) : 0;
    $notes_20 = isset($_POST['notes_20']) ? intval($_POST['notes_20']) : 0;
    $notes_10 = isset($_POST['notes_10']) ? intval($_POST['notes_10']) : 0;
    $notes_5 = isset($_POST['notes_5']) ? intval($_POST['notes_5']) : 0;
    $notes_2 = isset($_POST['notes_2']) ? intval($_POST['notes_2']) : 0;
    $notes_1 = isset($_POST['notes_1']) ? intval($_POST['notes_1']) : 0;

    // Calculate total amount
    $total_amount = ($notes_2000 * 2000) + ($notes_500 * 500) + ($notes_200 * 200) + ($notes_100 * 100) +
                    ($notes_50 * 50) + ($notes_20 * 20) + ($notes_10 * 10) + ($notes_5 * 5) +
                    ($notes_2 * 2) + ($notes_1 * 1);

    if (empty($deposit_date) || $bank_account_id === 0) {
        $_SESSION['error'] = "Deposit Date and Bank Account are required.";
        header('Location: edit.php?id=' . $transaction_id);
        exit;
    }

    // Update transaction in database
    try {
        $stmt = $conn->prepare("
            UPDATE cash_deposits 
            SET deposit_date = :deposit_date,
                bank_account_id = :bank_account_id,
                notes_2000 = :notes_2000,
                notes_500 = :notes_500,
                notes_200 = :notes_200,
                notes_100 = :notes_100,
                notes_50 = :notes_50,
                notes_20 = :notes_20,
                notes_10 = :notes_10,
                notes_5 = :notes_5,
                notes_2 = :notes_2,
                notes_1 = :notes_1,
                total_amount = :total_amount
            WHERE id = :id AND branch_id = :branch_id
        ");
        $stmt->execute([
            ':deposit_date' => $deposit_date,
            ':bank_account_id' => $bank_account_id,
            ':notes_2000' => $notes_2000,
            ':notes_500' => $notes_500,
            ':notes_200' => $notes_200,
            ':notes_100' => $notes_100,
            ':notes_50' => $notes_50,
            ':notes_20' => $notes_20,
            ':notes_10' => $notes_10,
            ':notes_5' => $notes_5,
            ':notes_2' => $notes_2,
            ':notes_1' => $notes_1,
            ':total_amount' => $total_amount,
            ':id' => $transaction_id,
            ':branch_id' => $branch_id,
        ]);

        $_SESSION['success'] = "Cash deposit transaction updated successfully.";
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to update transaction: " . $e->getMessage();
        header('Location: edit.php?id=' . $transaction_id);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <h4><?php echo $page_title; ?></h4>
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit Cash Deposit</h5>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Deposit Date</label>
                            <input type="date" class="form-control" name="deposit_date" value="<?php echo htmlspecialchars($transaction['deposit_date']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Bank Account ID</label>
                            <input type="number" class="form-control" name="bank_account_id" value="<?php echo htmlspecialchars($transaction['bank_account_id']); ?>" required>
                        </div>
                        <?php
                        // Generate input fields for notes
                        $notes = [2000, 500, 200, 100, 50, 20, 10, 5, 2, 1];
                        foreach ($notes as $note): ?>
                            <div class="col-md-6">
                                <label class="form-label">Notes <?php echo $note; ?></label>
                                <input type="number" class="form-control" name="notes_<?php echo $note; ?>" min="0" value="<?php echo htmlspecialchars($transaction['notes_' . $note]); ?>">
                            </div>
                        <?php endforeach; ?>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="index.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>