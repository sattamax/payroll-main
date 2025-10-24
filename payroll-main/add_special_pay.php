<?php
require 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $special_date = $_POST['special_date'] ?? '';
    $user_id = $_POST['user_id'] ?? '';
    $amount = $_POST['amount'] ?? '';

    if (empty($special_date) || empty($user_id) || empty($amount)) {
        $_SESSION['error'] = "⚠️ Please fill in all fields.";
        header("Location: special_pay.php");
        exit;
    }

    try {
        // ✅ Insert into special_pay table
        $stmt = $pdo->prepare("INSERT INTO special_pay (user_id, special_date, amount) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $special_date, $amount]);

        // ✅ Update payroll for this employee (if exists)
        $update = $pdo->prepare("UPDATE payroll 
                                 SET special_pay = special_pay + ?, 
                                     net_pay = net_pay + ? 
                                 WHERE employee_id = ?");
        $update->execute([$amount, $amount, $user_id]);

        $_SESSION['success'] = "✅ Special pay added successfully!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "❌ Error adding special pay: " . $e->getMessage();
    }

    header("Location: special_pay.php");
    exit;
}
?>
