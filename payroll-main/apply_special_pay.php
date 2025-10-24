<?php
require 'config.php';
session_start();

// ✅ Only admins can apply
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Access denied.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Update all Pending special pay records to Applied
        $stmt = $pdo->prepare("UPDATE special_pay SET status = 'Applied' WHERE status = 'Pending'");
        $stmt->execute();

        // Redirect back to main page
        header("Location: special_pay.php");
        exit;
    } catch (Exception $e) {
        die("Error applying special pay: " . $e->getMessage());
    }
} else {
    header("Location: special_pay.php");
    exit;
}
