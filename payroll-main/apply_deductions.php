<?php
session_start();
require 'config.php';

// ✅ Restrict access to admins only
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Access denied.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Update all pending deductions to Applied
        $stmt = $pdo->prepare("UPDATE deductions SET status = 'Applied' WHERE status = 'Pending'");
        $stmt->execute();

        // Redirect back with a success flag
        header("Location: deductions.php?applied=1");
        exit;

    } catch (Exception $e) {
        die("Error applying deductions: " . $e->getMessage());
    }
} else {
    // Redirect if accessed directly
    header("Location: deductions.php");
    exit;
}
