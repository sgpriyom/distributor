<?php
session_start();
require_once '../../config/config.php';
require_once '../../config/database.php';

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $branchId = $_POST['branch_id'] ?? null;
    $bankName = $_POST['bank_name'] ?? '';
    $accountNumber = $_POST['account_number'] ?? '';
    $openingBalance = $_POST['opening_balance'] ?? 0.00;

    // Validate input
    if (empty($branchId) || empty($bankName) || empty($accountNumber)) {
        $message = "All fields (except Opening Balance) are required.";
    } else {
        try {
            // Save bank account details to the database
            $db = (new Database())->getConnection();
            $stmt = $db->prepare("
                INSERT INTO bank_accounts (branch_id, bank_name, account_number, opening_balance, current_balance, created_at)
                VALUES (:branch_id, :bank_name, :account_number, :opening_balance, :current_balance, NOW())
            ");
            $stmt->bindParam(':branch_id', $branchId);
            $stmt->bindParam(':bank_name', $bankName);
            $stmt->bindParam(':account_number', $accountNumber);
            $stmt->bindParam(':opening_balance', $openingBalance);
            $stmt->bindParam(':current_balance', $openingBalance); // Initialize current balance with opening balance

            if ($stmt->execute()) {
                $message = "Bank account added successfully.";
            } else {
                $message = "Failed to add bank account.";
            }
        } catch (Exception $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Bank Account</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Add Bank Account</h1>
        <?php if (!empty($message)) : ?>
            <div class="alert <?php echo strpos($message, 'successfully') !== false ? 'alert-success' : 'alert-danger'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="branch_id" class="form-label">Branch ID</label>
                <input type="number" name="branch_id" id="branch_id" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="bank_name" class="form-label">Bank Name</label>
                <input type="text" name="bank_name" id="bank_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="account_number" class="form-label">Account Number</label>
                <input type="text" name="account_number" id="account_number" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="opening_balance" class="form-label">Opening Balance</label>
                <input type="number" name="opening_balance" id="opening_balance" class="form-control" step="0.01">
            </div>
            <button type="submit" class="btn btn-primary">Add Bank Account</button>
        </form>
    </div>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>