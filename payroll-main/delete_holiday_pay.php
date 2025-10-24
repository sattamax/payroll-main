<?php
require 'config.php';
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // ✅ Get record info first
        $stmt = $pdo->prepare("SELECT user_id, amount FROM holiday_pay WHERE id = ?");
        $stmt->execute([$id]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($record) {
            // ✅ Deduct from payroll before deleting
            $update = $pdo->prepare("UPDATE payroll 
                                     SET holiday_pay = holiday_pay - ?, 
                                         net_pay = net_pay - ? 
                                     WHERE employee_id = ?");
            $update->execute([$record['amount'], $record['amount'], $record['user_id']]);

            // ✅ Now delete record
            $del = $pdo->prepare("DELETE FROM holiday_pay WHERE id = ?");
            $del->execute([$id]);

            $_SESSION['success'] = "🗑️ Holiday pay record deleted successfully.";
        } else {
            $_SESSION['error'] = "❌ Record not found.";
        }

    } catch (PDOException $e) {
        $_SESSION['error'] = "❌ Error deleting holiday pay: " . $e->getMessage();
    }
}

header("Location: holiday_pay.php");
exit;
?>
