<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['user_id'])) {
    header("Location: payroll.php");
    exit;
}

$user_id = intval($_POST['user_id']);

try {
    $pdo->beginTransaction();

    // ✅ Only apply non-regular items (Pending → Applied)
    $tables = ['holiday_pay', 'special_pay', 'leave_pay', 'cash_advance', 'deductions'];
    
    foreach ($tables as $table) {
        $column = ($table === 'deductions') ? 'employee_id' : 'user_id';

        // Update all pending items to 'Applied'
        $stmt = $pdo->prepare("UPDATE $table SET status='Applied' WHERE $column=? AND status='Pending'");
        $stmt->execute([$user_id]);
    }

    // ✅ Calculate totals for the payroll record
    // Fetch regular pay separately (calculated dynamically, not applied)
    $stmt = $pdo->prepare("
        SELECT p.rate_per_hour, IFNULL(SUM(TIMESTAMPDIFF(MINUTE, a.time_in, a.time_out))/60,0) AS total_hours
        FROM users u
        LEFT JOIN positions p ON u.position_id = p.id
        LEFT JOIN attendance_logs a ON u.id = a.user_id
        WHERE u.id = ?
        GROUP BY p.rate_per_hour
    ");
    $stmt->execute([$user_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $regularPay = $row ? $row['total_hours'] * $row['rate_per_hour'] : 0;

    // Other applied amounts
    $getAmounts = $pdo->prepare("
        SELECT
            IFNULL((SELECT SUM(amount) FROM holiday_pay WHERE user_id=? AND status='Applied'),0) AS holiday_pay,
            IFNULL((SELECT SUM(amount) FROM special_pay WHERE user_id=? AND status='Applied'),0) AS special_pay,
            IFNULL((SELECT SUM(amount) FROM leave_pay WHERE user_id=? AND status='Applied'),0) AS leave_pay,
            IFNULL((SELECT SUM(amount) FROM cash_advance WHERE user_id=? AND status='Applied'),0) AS cash_advance,
            IFNULL((SELECT SUM(amount) FROM deductions WHERE employee_id=? AND status='Applied'),0) AS total_deductions,
            IFNULL((SELECT SUM(hours*rate) FROM overtime WHERE user_id=?),0) AS overtime_pay
    ");
    $getAmounts->execute([$user_id,$user_id,$user_id,$user_id,$user_id,$user_id]);
    $amounts = $getAmounts->fetch(PDO::FETCH_ASSOC);

    $gross = $regularPay + $amounts['overtime_pay'] + $amounts['holiday_pay'] + $amounts['special_pay'] + $amounts['leave_pay'];
    $net = $gross - $amounts['total_deductions'] - $amounts['cash_advance'];

    // ✅ Insert payroll record
    $insert = $pdo->prepare("
        INSERT INTO payroll_master
        (user_id, regular_pay, overtime_pay, holiday_pay, special_pay, leave_pay, cash_advance, deductions, net_pay, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Applied')
    ");
    $insert->execute([
        $user_id,
        $regularPay,
        $amounts['overtime_pay'],
        $amounts['holiday_pay'],
        $amounts['special_pay'],
        $amounts['leave_pay'],
        $amounts['cash_advance'],
        $amounts['total_deductions'],
        $net
    ]);

    $pdo->commit();

    header("Location: payroll.php");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    die("Error applying payroll: ".$e->getMessage());
}
?>
