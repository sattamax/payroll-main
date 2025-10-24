<?php
require 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $special_date = $_POST['special_date'] ?? '';
    $amount = $_POST['amount'] ?? '';

    if (empty($id) || empty($special_date) || empty($amount)) {
        $_SESSION['error'] = "⚠️ Please fill in all fields.";
        header("Location: special_pay.php");
        exit;
    }

    try {
        // ✅ Fetch old record to calculate the difference
        $oldStmt = $pdo->prepare("SELECT user_id, amount FROM special_pay WHERE id = ?");
        $oldStmt->execute([$id]);
        $old = $oldStmt->fetch(PDO::FETCH_ASSOC);

        if (!$old) {
            $_SESSION['error'] = "❌ Record not found.";
            header("Location: special_pay.php");
            exit;
        }

        // ✅ Update record
        $stmt = $pdo->prepare("UPDATE special_pay SET special_date = ?, amount = ? WHERE id = ?");
        $stmt->execute([$special_date, $amount, $id]);

        // ✅ Update payroll difference
        $difference = $amount - $old['amount'];
        $update = $pdo->prepare("UPDATE payroll 
                                 SET special_pay = special_pay + ?, 
                                     net_pay = net_pay + ? 
                                 WHERE employee_id = ?");
        $update->execute([$difference, $difference, $old['user_id']]);

        $_SESSION['success'] = "✅ Special pay updated successfully!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "❌ Error updating special pay: " . $e->getMessage();
    }

    header("Location: special_pay.php");
    exit;
}
?>
