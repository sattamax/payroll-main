<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Mark all Pending holiday pay records as Applied
        $stmt = $pdo->prepare("UPDATE holiday_pay SET status = 'Applied' WHERE status = 'Pending'");
        $stmt->execute();

        // Redirect back with success flag
        header("Location: holiday_pay.php?applied=1");
        exit;
    } catch (Exception $e) {
        die("Error applying holiday pay: " . $e->getMessage());
    }
} else {
    header("Location: holiday_pay.php");
    exit;
}
