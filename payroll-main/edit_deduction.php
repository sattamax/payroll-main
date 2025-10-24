<?php
require 'config.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'add') {
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $position = $_POST['position'] ?? '';

    $stmt = $pdo->prepare("INSERT INTO deductions (description, amount, position) VALUES (?, ?, ?)");
    $stmt->execute([$description, $amount, $position]);
    header('Location: deductions.php');
    exit;
}

if ($action === 'edit') {
    $id = $_POST['id'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $position = $_POST['position'] ?? '';

    $stmt = $pdo->prepare("UPDATE deductions SET description=?, amount=?, position=? WHERE id=?");
    $stmt->execute([$description, $amount, $position, $id]);
    header('Location: manage_deductions.php');
    exit;
}

if ($action === 'fetch' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM deductions WHERE id=?");
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    exit;
}
?>
