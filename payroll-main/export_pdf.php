<?php
require 'config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user']['id'];

// Fetch data (same as in my_payroll.php)
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

if (!$employee) die("No payroll data found");

$deductionList = $pdo->query("SELECT type, amount FROM deductions WHERE employee_id = $userId AND status='Applied'")->fetchAll(PDO::FETCH_ASSOC);
$totalDeductions = array_sum(array_column($deductionList, 'amount'));

$regularPay = $employee['total_hours'] * $employee['rate_per_hour'];
$holidayPay = $pdo->query("SELECT IFNULL(SUM(amount), 0) AS total FROM holiday_pay WHERE user_id = $userId AND status='Applied'")->fetch()['total'] ?? 0;
$specialPay = $pdo->query("SELECT IFNULL(SUM(amount), 0) AS total FROM special_pay WHERE user_id = $userId AND status='Applied'")->fetch()['total'] ?? 0;
$leavePay = $pdo->query("SELECT IFNULL(SUM(amount), 0) AS total FROM leave_pay WHERE user_id = $userId AND status='Applied'")->fetch()['total'] ?? 0;
$overtimePay = $pdo->query("SELECT IFNULL(SUM(hours * rate), 0) AS total FROM overtime WHERE user_id = $userId")->fetch()['total'] ?? 0;

$gross = $regularPay + $holidayPay + $specialPay + $leavePay + $overtimePay;
$cashAdvance = $employee['cash_advance'] ?? 0;
$netPay = $gross - $totalDeductions - $cashAdvance;

// Output HTML as printable page
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Payslip - <?= htmlspecialchars($employee['full_name']) ?></title>
<style>
body { font-family: 'Courier New', monospace; background: #fff; margin: 0; padding: 40px; }
.container { max-width: 700px; margin: 0 auto; border: 1px solid #000; padding: 20px; }
.header { text-align: center; font-weight: bold; font-size: 18px; margin-bottom: 10px; }
.details th { text-align: left; padding-right: 10px; }
table { width: 100%; border-collapse: collapse; font-size: 14px; }
th, td { border: 1px solid #000; padding: 5px; }
.total { font-weight: bold; background: #f8f8f8; }
.net-pay { text-align: right; font-size: 16px; font-weight: bold; margin-top: 20px; }
@media print { .no-print { display: none; } }
</style>
</head>
<body>
<div class="container">
    <div class="header">PAYSLIP</div>
    <hr>
    <table class="details">
        <tr><th>Employee Name:</th><td><?= htmlspecialchars($employee['full_name']) ?></td></tr>
        <tr><th>Rate per Hour:</th><td>₱<?= number_format($employee['rate_per_hour'], 2) ?></td></tr>
        <tr><th>Total Hours:</th><td><?= number_format($employee['total_hours'], 2) ?> hrs</td></tr>
    </table>
    <table>
        <tr><th>EARNINGS</th><th>AMOUNT</th><th>DEDUCTIONS</th><th>AMOUNT</th></tr>
        <tr><td>Regular Pay</td><td>₱<?= number_format($regularPay, 2) ?></td>
            <td>Cash Advance</td><td>₱<?= number_format($cashAdvance, 2) ?></td></tr>
        <tr><td>Holiday Pay</td><td>₱<?= number_format($holidayPay, 2) ?></td>
            <td colspan="2" rowspan="<?= count($deductionList) + 1 ?>">
                <table style="width:100%; border:none;">
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
        <tr class="total"><td>GROSS PAY</td><td>₱<?= number_format($gross, 2) ?></td>
            <td>TOTAL DEDUCTIONS</td><td>₱<?= number_format($totalDeductions + $cashAdvance, 2) ?></td></tr>
    </table>
    <div class="net-pay">NET PAY: ₱<?= number_format($netPay, 2) ?></div>
    <hr>
    <p style="text-align:center;">Thank you for your hard work!</p>
</div>
<script>
window.onload = () => window.print();
</script>
</body>
</html>
