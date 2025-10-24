<?php
require 'config.php';
session_start();

// Only admins
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Access denied.");
}

$holiday_date = $_POST['holiday_date'] ?? null;
$position_id  = $_POST['position_id'] ?? null;
$amount       = $_POST['amount'] ?? null;

// Basic validation
if (!$holiday_date || !$position_id || !$amount) {
    header("Location: holiday_pay.php?error=missing_fields");
    exit;
}

// Get all employees under that position
$stmt = $pdo->prepare("SELECT id, full_name FROM users WHERE position_id = ?");
$stmt->execute([$position_id]);
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($employees) {
    try {
        // Start transaction
        $pdo->beginTransaction();

        $insert = $pdo->prepare("
            INSERT INTO holiday_pay (user_id, holiday_date, amount)
            VALUES (?, ?, ?)
        ");

        foreach ($employees as $emp) {
            $insert->execute([$emp['id'], $holiday_date, $amount]);
        }

        $pdo->commit();

        // Redirect with success flag
        header("Location: holiday_pay.php?added=1");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        // Redirect with error message
        header("Location: holiday_pay.php?error=" . urlencode($e->getMessage()));
        exit;
    }

} else {
    // No employees found
    header("Location: holiday_pay.php?error=no_employees");
    exit;
}
?>
