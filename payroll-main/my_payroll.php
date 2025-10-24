<?php
require 'config.php';
require 'includes/header.php';

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user']['id'];

// ✅ Fetch deductions
$deductionListStmt = $pdo->prepare("
    SELECT type, amount 
    FROM deductions 
    WHERE employee_id = ? AND status='Applied'
");
$deductionListStmt->execute([$userId]);
$deductionList = $deductionListStmt->fetchAll(PDO::FETCH_ASSOC);
$totalDeductions = array_sum(array_column($deductionList, 'amount'));

// ✅ Fetch employee info
$stmt = $pdo->prepare("
    SELECT 
        u.full_name,
        p.rate_per_hour,
        IFNULL(SUM(TIMESTAMPDIFF(MINUTE, a.time_in, a.time_out)) / 60, 0) AS total_hours,
        IFNULL((SELECT SUM(amount) FROM cash_advance WHERE user_id=u.id AND status='Applied'), 0) AS cash_advance
    FROM users u
    LEFT JOIN positions p ON u.position_id = p.id
    LEFT JOIN attendance_logs a ON u.id = a.user_id
    WHERE u.id = ?
    GROUP BY u.full_name, p.rate_per_hour
");
$stmt->execute([$userId]);
$employee = $stmt->fetch(PDO::FETCH_ASSOC);

if ($employee) {
    $regularPay = $employee['total_hours'] * $employee['rate_per_hour'];

    $holidayPay = $pdo->query("SELECT IFNULL(SUM(amount), 0) AS total FROM holiday_pay WHERE user_id = $userId AND status='Applied'")->fetch()['total'] ?? 0;
    $specialPay = $pdo->query("SELECT IFNULL(SUM(amount), 0) AS total FROM special_pay WHERE user_id = $userId AND status='Applied'")->fetch()['total'] ?? 0;
    $leavePay = $pdo->query("SELECT IFNULL(SUM(amount), 0) AS total FROM leave_pay WHERE user_id = $userId AND status='Applied'")->fetch()['total'] ?? 0;
    $overtimePay = $pdo->query("SELECT IFNULL(SUM(hours * rate), 0) AS total FROM overtime WHERE user_id = $userId")->fetch()['total'] ?? 0;

    $gross = $regularPay + $holidayPay + $specialPay + $leavePay + $overtimePay;
    $cashAdvance = $employee['cash_advance'] ?? 0;
    $netPay = $gross - $totalDeductions - $cashAdvance;
} else {
    $regularPay = $holidayPay = $specialPay = $leavePay = $overtimePay = $gross = $cashAdvance = $netPay = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Payroll</title>
<style>
body {
    font-family: 'Courier New', monospace;
    background-color: #f0f2f5;
    margin: 0;
    padding: 0;
}
.container {
    max-width: 700px;
    margin: 40px auto;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    padding: 30px;
}
.header {
    text-align: center;
    font-weight: bold;
    font-size: 18px;
    margin-bottom: 10px;
}
.details th {
    text-align: left;
    padding-right: 10px;
}
.payslip-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    font-size: 14px;
}
.payslip-table th {
    background: #f8f8f8;
    border-bottom: 1px solid #ddd;
    padding: 6px;
    text-align: center;
}
.payslip-table td {
    padding: 6px;
    text-align: left;
}
.total {
    font-weight: bold;
    border-top: 1px dashed #999;
}
.net-pay {
    text-align: right;
    font-size: 16px;
    font-weight: bold;
    color: #000;
    margin-top: 20px;
}
hr {
    border: none;
    border-top: 1px dashed #000;
    margin: 10px 0;
}
.back-button {
    text-align: center;
    margin-top: 20px;
}
button, a {
    padding: 10px 15px;
    margin: 5px;
    border-radius: 5px;
    font-weight: bold;
    text-decoration: none;
    border: none;
    cursor: pointer;
}
button {
    background-color: #2ecc71;
    color: white;
}
a {
    background-color: #3498db;
    color: white;
}
button:hover { background-color: #27ae60; }
a:hover { background-color: #2980b9; }
@media print {
    .no-print { display: none; }
    body { background: white; }
}
</style>
</head>
<body>

<div class="container" id="payslip">
    <div class="header">PAYSLIP</div>
    <hr>
    <?php if ($employee): ?>
        <table class="details">
            <tr><th>Employee Name:</th><td><?= htmlspecialchars($employee['full_name']) ?></td></tr>
            <tr><th>Rate per Hour:</th><td>₱<?= number_format($employee['rate_per_hour'], 2) ?></td></tr>
            <tr><th>Total Hours:</th><td><?= number_format($employee['total_hours'], 2) ?> hrs</td></tr>
        </table>

        <table class="payslip-table">
            <tr>
                <th>EARNINGS</th>
                <th>AMOUNT</th>
                <th>DEDUCTIONS</th>
                <th>AMOUNT</th>
            </tr>
            <tr>
                <td>Regular Pay</td><td>₱<?= number_format($regularPay, 2) ?></td>
                <td>Cash Advance</td><td>₱<?= number_format($cashAdvance, 2) ?></td>
            </tr>
            <tr>
                <td>Holiday Pay</td><td>₱<?= number_format($holidayPay, 2) ?></td>
                <td colspan="2" rowspan="<?= count($deductionList) + 1 ?>">
                    <table style="width:100%;">
                        <?php foreach ($deductionList as $d): ?>
                            <tr>
                                <td><?= htmlspecialchars($d['type']) ?></td>
                                <td style="text-align:right;">₱<?= number_format($d['amount'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </td>
            </tr>
            <tr><td>Special Pay</td><td>₱<?= number_format($specialPay, 2) ?></td></tr>
            <tr><td>Leave Pay</td><td>₱<?= number_format($leavePay, 2) ?></td></tr>
            <tr><td>Overtime</td><td>₱<?= number_format($overtimePay, 2) ?></td></tr>
            <tr class="total">
                <td>GROSS PAY</td><td>₱<?= number_format($gross, 2) ?></td>
                <td>TOTAL DEDUCTIONS</td><td>₱<?= number_format($totalDeductions + $cashAdvance, 2) ?></td>
            </tr>
        </table>

        <div class="net-pay">NET PAY: ₱<?= number_format($netPay, 2) ?></div>
        <hr>
        <p style="text-align:center;">Thank you for your hard work!</p>
    <?php else: ?>
        <p>No payroll data found.</p>
    <?php endif; ?>
</div>

<div class="back-button no-print">
    <button onclick="window.print();">🖨️ Print Payslip</button>
    <button onclick="exportPDF()">💾 Export to PDF</button>
    <a href="dashboard.php">← Back to Dashboard</a>
</div>  

<script>
// ✅ Pure JavaScript export to PDF (no external library)
function exportPDF() {
    const htmlContent = `
        <html>
        <head>
            <title>Payslip</title>
            <style>
                body { font-family: 'Courier New', monospace; margin: 20px; }
                .container { border: 1px solid #000; padding: 20px; }
            </style>
        </head>
        <body>${document.getElementById('payslip').outerHTML}</body>
        </html>
    `;

    const blob = new Blob([htmlContent], { type: 'application/pdf' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'payslip_<?= htmlspecialchars($employee['full_name'] ?? "employee") ?>.pdf';
    a.click();
    URL.revokeObjectURL(url);
}
</script>

</body>
</html>
