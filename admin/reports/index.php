<?php
session_start();
require_once '../../includes/auth.php'; // Ensure user is authenticated
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Admin Panel</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .report-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Reports</h1>
        <div class="row">
            <div class="col-md-4">
                <div class="card report-card p-3 text-center">
                    <h5>Staff Report</h5>
                    <p>View and analyze staff-related data.</p>
                    <a href="staff.php" class="btn btn-primary">Generate Report</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card report-card p-3 text-center">
                    <h5>Bank Report</h5>
                    <p>View bank account transactions and summaries.</p>
                    <a href="bank.php" class="btn btn-primary">Generate Report</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card report-card p-3 text-center">
                    <h5>LAPU Report</h5>
                    <p>Analyze LAPU-related operations.</p>
                    <a href="lapu.php" class="btn btn-primary">Generate Report</a>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card report-card p-3 text-center">
                    <h5>SIM Report</h5>
                    <p>View and manage SIM card details.</p>
                    <a href="sim.php" class="btn btn-primary">Generate Report</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card report-card p-3 text-center">
                    <h5>APB Report</h5>
                    <p>Track APB-related operations.</p>
                    <a href="apb.php" class="btn btn-primary">Generate Report</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card report-card p-3 text-center">
                    <h5>DTH Report</h5>
                    <p>Analyze DTH transactions and data.</p>
                    <a href="dth.php" class="btn btn-primary">Generate Report</a>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card report-card p-3 text-center">
                    <h5>Cash Deposit Report</h5>
                    <p>View cash deposit details and summaries.</p>
                    <a href="cash_deposit.php" class="btn btn-primary">Generate Report</a>
                </div>
            </div>
        </div>
    </div>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>