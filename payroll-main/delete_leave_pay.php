<?php
require 'config.php';
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // ✅ Get record info first
        $stmt = $pdo->prepare("SELECT user_id, amount FROM leave_pay WHERE id = ?");
        $stmt->execute([$id]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($record) {
            // ✅ Deduct from payroll
            $update = $pdo->prepare("UPDATE payroll 
                                     SET leave_pay = leave_pay - ?, 
                                         net_pay = net_pay - ? 
                                     WHERE employee_id = ?");
            $update->execute([$record['amount'], $record['amount'], $record['user_id']]);

            // ✅ Delete record
            $del = $pdo->prepare("DELETE FROM leave_pay WHERE id = ?");
            $del->execute([$id]);

            $_SESSION['success'] = "🗑️ Leave pay deleted successfully.";
        } else {
            $_SESSION['error'] = "❌ Record not found.";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "❌ Error deleting record: " . $e->getMessage();
    }
}

header("Location: leave_pay.php");
exit;
?>
