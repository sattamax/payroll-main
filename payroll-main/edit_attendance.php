<?php
require 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id       = $_POST['id'] ?? '';
    $date     = $_POST['date'] ?? '';
    $time_in  = $_POST['time_in'] ?? '';
    $time_out = $_POST['time_out'] ?? '';

    // Basic validation
    if (!$id || !$date || !$time_in || !$time_out) {
        $_SESSION['popup_message'] = "⚠️ Missing required fields.";
        header("Location: attendance.php");
        exit;
    }

    // Calculate total work hours
    $in  = strtotime($time_in);
    $out = strtotime($time_out);
    $hours = ($out - $in) / 3600;

    // Validate logic
    if ($hours < 0) {
        $_SESSION['popup_message'] = "⚠️ Invalid entry: Time Out cannot be earlier than Time In.";
        header("Location: attendance.php");
        exit;
    }

    if ($hours > 8) {
        $_SESSION['popup_message'] = "❌ Invalid entry: Total work hours cannot exceed 8 hours.";
        header("Location: attendance.php");
        exit;
    }

    // Update database
    $stmt = $pdo->prepare("UPDATE attendance_logs SET date = ?, time_in = ?, time_out = ? WHERE id = ?");
    $stmt->execute([$date, $time_in, $time_out, $id]);

    $_SESSION['popup_message'] = "✅ Attendance log updated successfully!";
    header("Location: attendance.php");
    exit;
} else {
    header("Location: attendance.php");
    exit;
}
?>
