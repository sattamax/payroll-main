<?php
require 'config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user']['id'];

// Get deductions
$deductionStmt = $pdo->query("SELECT SUM(amount) AS total_deductions FROM deductions");
$totalDeductions = $deductionStmt->fetch(PDO::FETCH_ASSOC)['total_deductions'] ?? 0;

// Get employee info
$stmt = $pdo->prepare("
    SELECT 
        u.full_name,
        p.rate_per_hour,
        IFNULL(SUM(TIMESTAMPDIFF(MINUTE, a.time_in, a.time_out)) / 60, 0) AS total_hours,
        IFNULL(p.rate_per_hour * SUM(TIMESTAMPDIFF(MINUTE, a.time_in, a.time_out)) / 60, 0) AS regular_pay,
        IFNULL(SUM(ca.amount), 0) AS cash_advance
    FROM users u
    LEFT JOIN positions p ON u.position_id = p.id
    LEFT JOIN attendance_logs a ON u.id = a.user_id
    LEFT JOIN cash_advance ca ON u.id = ca.user_id
    WHERE u.id = ?
    GROUP BY u.full_name, p.rate_per_hour
");
$stmt->execute([$userId]);
$employee = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$employee) {
    die("No payroll data found.");
}

// Example additional pays
$overtimePay = 500.00;
$holidayPay  = 300.00;
$specialPay  = 250.00;
$leavePay    = 400.00;

$gross = ($employee['regular_pay'] ?? 0) + $overtimePay + $holidayPay + $specialPay + $leavePay;
$cashAdvance = $employee['cash_advance'] ?? 0;
$netPay = $gross - $totalDeductions - $cashAdvance;

// Function to center text
function center($text, $width = 32) {
    $pad = floor(($width - strlen($text)) / 2);
    return str_repeat(" ", max(0, $pad)) . $text;
}

header("Content-Type: text/plain");
echo center("EMPLOYEE PAYSLIP") . "\n";
echo str_repeat("-", 32) . "\n";
printf("%-18s %13s\n", "Employee:", $employee['full_name']);
printf("%-18s %13.2f\n", "Total Hours:", $employee['total_hours']);
printf("%-18s %13s\n", "Rate/Hour:", "₱" . number_format($employee['rate_per_hour'], 2));
printf("%-18s %13s\n", "Regular Pay:", "₱" . number_format($employee['regular_pay'], 2));
printf("%-18s %13s\n", "Overtime Pay:", "₱" . number_format($overtimePay, 2));
printf("%-18s %13s\n", "Holiday Pay:", "₱" . number_format($holidayPay, 2));
printf("%-18s %13s\n", "Special Pay:", "₱" . number_format($specialPay, 2));
printf("%-18s %13s\n", "Leave Pay:", "₱" . number_format($leavePay, 2));
printf("%-18s %13s\n", "Deductions:", "₱" . number_format($totalDeductions, 2));
printf("%-18s %13s\n", "Cash Advance:", "₱" . number_format($cashAdvance, 2));
echo str_repeat("-", 32) . "\n";
printf("%-18s %13s\n", "Net Pay:", "₱" . number_format($netPay, 2));
echo str_repeat("-", 32) . "\n";
echo center("Generated on " . date("M d, Y h:i A")) . "\n\n";
?>
