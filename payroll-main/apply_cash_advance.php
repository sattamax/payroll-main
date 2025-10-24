<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Mark all pending cash advances as Applied
        $stmt = $pdo->prepare("UPDATE cash_advance SET status = 'Applied' WHERE status = 'Pending'");
        $stmt->execute();

        header("Location: cash_advance.php");
        exit;
    } catch (Exception $e) {
        die("Error applying cash advances: " . $e->getMessage());
    }
} else {
    header("Location: cash_advance.php");
    exit;
}
