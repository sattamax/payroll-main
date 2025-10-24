<?php
session_start();
require 'config.php';

// Only admin can reset
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Access denied.");
}

try {
    // 1️⃣ Move all current logs to attendance_history
    $insertHistory = $pdo->query("
        INSERT INTO attendance_history (user_id, full_name, date, time_in, time_out)
        SELECT a.user_id, u.full_name, a.date, a.time_in, a.time_out
        FROM attendance_logs a
        JOIN users u ON a.user_id = u.id
    ");

    // 2️⃣ Clear attendance_logs after backup
    $pdo->query("DELETE FROM attendance_logs");

    // 3️⃣ Update system_settings last reset date
    $today = date('Y-m-d');
    $update = $pdo->prepare("UPDATE system_settings SET setting_value = ? WHERE setting_key = 'last_attendance_reset'");
    $update->execute([$today]);

    $_SESSION['popup_message'] = "✅ Attendance logs successfully reset and archived.";
} catch (PDOException $e) {
    $_SESSION['popup_message'] = "⚠️ Error resetting logs: " . $e->getMessage();
}

header("Location: attendance_logs.php");
exit;
?>
