<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $deduction_date = $_POST['deduction_date'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];

    // Insert with status 'Pending'
    $stmt = $pdo->prepare("
        INSERT INTO deductions (user_id, deduction_date, description, amount, status)
        VALUES (?, ?, ?, ?, 'Pending')
    ");
    $stmt->execute([$user_id, $deduction_date, $description, $amount]);

    header("Location: deductions.php");
    exit;
}

// Fetch users for dropdown
$users = $pdo->query("SELECT id, full_name FROM users ORDER BY full_name ASC")->fetchAll();
include 'includes/header.php';
?>

<div class="main-content">
    <h2>➕ New Deduction</h2>
    <form method="POST">
        <label>Date:</label>
        <input type="date" name="deduction_date" required class="form-control mb-2">

        <label>Employee:</label>
        <select name="user_id" class="form-control mb-2" required>
            <option value="">Select employee</option>
            <?php foreach ($users as $u): ?>
                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['full_name']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Description:</label>
        <input type="text" name="description" required class="form-control mb-2">

        <label>Amount (₱):</label>
        <input type="number" name="amount" step="0.01" required class="form-control mb-2">

        <button type="submit" class="btn btn-success">💾 Save</button>
        <a href="deductions.php" class="btn btn-secondary">Back</a>
    </form>
</div>
