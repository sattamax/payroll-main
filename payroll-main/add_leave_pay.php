<?php
require 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $leave_date = $_POST['leave_date'] ?? '';
    $user_id = $_POST['user_id'] ?? '';
    $amount = $_POST['amount'] ?? '';

    if (empty($leave_date) || empty($user_id) || empty($amount)) {
        $_SESSION['error'] = "⚠️ Please fill in all fields.";
        header("Location: leave_pay.php");
        exit;
    }

    try {
        // ✅ Insert leave pay record
        $stmt = $pdo->prepare("INSERT INTO leave_pay (user_id, leave_date, amount) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $leave_date, $amount]);

        // ✅ Update payroll table if record exists
        $update = $pdo->prepare("UPDATE payroll 
                                 SET leave_pay = leave_pay + ?, 
                                     net_pay = net_pay + ? 
                                 WHERE employee_id = ?");
        $update->execute([$amount, $amount, $user_id]);

        $_SESSION['success'] = "✅ Leave pay added successfully!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "❌ Error adding leave pay: " . $e->getMessage();
    }

    header("Location: leave_pay.php");
    exit;
}
?>
