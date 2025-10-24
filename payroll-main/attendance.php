<?php
session_start();
require 'config.php';

$user_id = $_SESSION['user']['id'] ?? null;

if (!$user_id) {
    die("Unauthorized.");
}

$date = date('Y-m-d');

// Get today's attendance record
$stmt = $pdo->prepare("SELECT * FROM attendance_logs WHERE user_id = ? AND date = ?");
$stmt->execute([$user_id, $date]);
$log = $stmt->fetch();

if (!$log) {
    // No log yet → allow Time In
    $stmt = $pdo->prepare("INSERT INTO attendance_logs (user_id, date, time_in) VALUES (?, ?, NOW())");
    $stmt->execute([$user_id, $date]);
    $_SESSION['popup_message'] = "🟢 Time In recorded successfully!";
} elseif (!$log['time_out']) {
    // Has Time In but no Time Out
    $time_in = strtotime($log['time_in']);
    $now = time();
    $hours_diff = ($now - $time_in) / 3600;

    if ($hours_diff > 10) {
        // ❌ Over 10 hours — Do NOT record time out
        $_SESSION['banner_message'] = [
            'type' => 'danger',
            'text' => '⚠ ERROR: You have exceeded 10 hours since Time In. Time Out not recorded. Please contact your admin to fix this record.'
        ];
        $_SESSION['popup_message'] = "⛔ Time Out not recorded (exceeded 10 hours).";
    } else {
        // ✅ Within 10 hours
        if ($hours_diff > 8) {
            // Cap at 8 hours
            $adjusted_time_out = date('Y-m-d H:i:s', $time_in + (8 * 3600));
            $stmt = $pdo->prepare("UPDATE attendance_logs SET time_out = ? WHERE id = ?");
            $stmt->execute([$adjusted_time_out, $log['id']]);

            $_SESSION['popup_message'] = "🔴 Time Out recorded (8-hour limit reached).";
        } else {
            // Normal Time Out (≤ 8 hrs)
            $stmt = $pdo->prepare("UPDATE attendance_logs SET time_out = NOW() WHERE id = ?");
            $stmt->execute([$log['id']]);

            $_SESSION['popup_message'] = "🔴 Time Out recorded successfully!";
        }
    }
} else {
    // Already timed in and out today
    $_SESSION['popup_message'] = "✅ You already timed in and out today.";
}

header("Location: dashboard.php");
exit;
?>
