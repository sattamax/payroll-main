<?php
require 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $holiday_date = $_POST['holiday_date'] ?? '';
    $amount = $_POST['amount'] ?? '';

    if (empty($id) || empty($holiday_date) || empty($amount)) {
        $_SESSION['error'] = "⚠️ Please fill in all fields.";
        header("Location: holiday_pay.php");
        exit;
    }

    try {
        // ✅ Get old amount to adjust payroll
        $oldStmt = $pdo->prepare("SELECT user_id, amount FROM holiday_pay WHERE id = ?");
        $oldStmt->execute([$id]);
        $old = $oldStmt->fetch(PDO::FETCH_ASSOC);

        if (!$old) {
            $_SESSION['error'] = "❌ Record not found.";
            header("Location: holiday_pay.php");
            exit;
        }

        // ✅ Update holiday pay record
        $stmt = $pdo->prepare("UPDATE holiday_pay SET holiday_date = ?, amount = ? WHERE id = ?");
        $stmt->execute([$holiday_date, $amount, $id]);

        // ✅ Update payroll difference
        $difference = $amount - $old['amount'];
        $update = $pdo->prepare("UPDATE payroll 
                                 SET holiday_pay = holiday_pay + ?, 
                                     net_pay = net_pay + ? 
                                 WHERE employee_id = ?");
        $update->execute([$difference, $difference, $old['user_id']]);

        $_SESSION['success'] = "✅ Holiday pay updated successfully!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "❌ Error updating holiday pay: " . $e->getMessage();
    }

    header("Location: holiday_pay.php");
    exit;
}
?>
