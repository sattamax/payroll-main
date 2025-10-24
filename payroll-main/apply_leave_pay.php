<?php
require 'config.php';
session_start();

// Only admins can apply leave pay
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Access denied.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Update all Pending leave pay records to Applied
        $stmt = $pdo->prepare("UPDATE leave_pay SET status = 'Applied' WHERE status = 'Pending'");
        $stmt->execute();

        // Redirect back to main page
        header("Location: leave_pay.php");
        exit;
    } catch (Exception $e) {
        die("Error applying leave pay: " . $e->getMessage());
    }
} else {
    header("Location: leave_pay.php");
    exit;
}
