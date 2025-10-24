<?php
require 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $leave_date = $_POST['leave_date'] ?? '';
    $amount = $_POST['amount'] ?? '';

    if (empty($id) || empty($leave_date) || empty($amount)) {
        $_SESSION['error'] = "⚠️ Please fill in all fields.";
        header("Location: leave_pay.php");
        exit;
    }

    try {
        // ✅ Get old record
        $oldStmt = $pdo->prepare("SELECT user_id, amount FROM leave_pay WHERE id = ?");
        $oldStmt->execute([$id]);
        $old = $oldStmt->fetch(PDO::FETCH_ASSOC);

        if (!$old) {
            $_SESSION['error'] = "❌ Record not found.";
            header("Location: leave_pay.php");
            exit;
        }

        // ✅ Update record
        $stmt = $pdo->prepare("UPDATE leave_pay SET leave_date = ?, amount = ? WHERE id = ?");
        $stmt->execute([$leave_date, $amount, $id]);

        // ✅ Adjust payroll difference
        $difference = $amount - $old['amount'];
        $update = $pdo->prepare("UPDATE payroll 
                                 SET leave_pay = leave_pay + ?, 
                                     net_pay = net_pay + ? 
                                 WHERE employee_id = ?");
        $update->execute([$difference, $difference, $old['user_id']]);

        $_SESSION['success'] = "✅ Leave pay updated successfully!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "❌ Error updating leave pay: " . $e->getMessage();
    }

    header("Location: leave_pay.php");
    exit;
}
?>
