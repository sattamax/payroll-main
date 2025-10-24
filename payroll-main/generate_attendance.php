<?php
session_start();
require 'config.php';

// ✅ Make sure only logged-in users can access
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];
$role = $user['role'];
$user_id = $user['id'];

// ✅ Only admin can use this feature
if ($role !== 'admin') {
    $_SESSION['popup_message'] = "⛔ Access denied. Only admin can generate attendance.";
    header('Location: attendance_logs.php');
    exit;
}

// ✅ Get all employees
$stmt = $pdo->query("SELECT id FROM users WHERE role = 'employee'");
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

$today = new DateTime();

foreach ($employees as $emp) {
    $empId = $emp['id'];

    // Generate 5 days of attendance (8 hours per day)
    for ($i = 0; $i < 5; $i++) {
        $date = (clone $today)->modify("-$i day")->format('Y-m-d');

        // Skip if attendance already exists for that date
        $check = $pdo->prepare("SELECT id FROM attendance_logs WHERE user_id = ? AND date = ?");
        $check->execute([$empId, $date]);

        if ($check->rowCount() === 0) {
            $timeIn  = "$date 08:00:00";
            $timeOut = "$date 16:00:00";

            $insert = $pdo->prepare("
                INSERT INTO attendance_logs (user_id, date, time_in, time_out)
                VALUES (?, ?, ?, ?)
            ");
            $insert->execute([$empId, $date, $timeIn, $timeOut]);
        }
    }
}

// ✅ Success message + redirect
$_SESSION['popup_message'] = "✅ Successfully generated 5 days of attendance for all employees!";
header('Location: attendance_logs.php');
exit;
?>
