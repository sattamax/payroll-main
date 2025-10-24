<?php
require 'config.php';
session_start();

// Check if the form submitted an ID
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $user_id = $_POST['id'];

    // Update user to archived = 1
    $stmt = $pdo->prepare("UPDATE users SET archived = 1 WHERE id = ?");
    $stmt->execute([$user_id]);

    // Optional success message
    $_SESSION['message'] = "User archived successfully!";
}

// Redirect back to employees list
header("Location: employees.php");
exit;
?>