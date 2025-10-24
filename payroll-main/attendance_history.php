<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Access denied.");
}

$stmt = $pdo->query("SELECT * FROM attendance_history ORDER BY reset_at DESC, date DESC");
$history = $stmt->fetchAll();
?>

<?php include 'includes/header.php'; ?>

<style>
body {
  background: url('uploads/bg.jpg') no-repeat center center fixed;
  background-size: cover;
  font-family: 'Courier New', monospace;
}
.main-content {
  margin: 30px;
  padding: 20px;
  background: rgba(255,255,255,0.9);
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0,0,0,0.2);
}
h2 { 
  text-align: center; 
  color: #2c3e50;
  margin-bottom: 20px;
}
table {
  width: 100%;
  border-collapse: collapse;
  background: white;
}
th, td {
  border: 1px solid #aaa;
  padding: 10px;
  text-align: center;
}
th {
  background: #007BFF;
  color: white;
}
.back-btn {
  display: inline-block;
  background-color: #2ecc71;
  color: white;
  padding: 10px 18px;
  border-radius: 6px;
  text-decoration: none;
  font-weight: bold;
  transition: background 0.3s ease;
  margin-bottom: 15px;
}
.back-btn:hover {
  background-color: #27ae60;
}
</style>

<div class="main-content">
  <a href="attendance_logs.php" class="back-btn">⬅ Back to Attendance Logs</a>
  <h2>Attendance History (Archived)</h2>

  <table>
    <thead>
      <tr>
        <th>Employee Name</th>
        <th>Date</th>
        <th>Time In</th>
        <th>Time Out</th>
        <th>Reset At</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($history): ?>
        <?php foreach ($history as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['full_name']) ?></td>
          <td><?= htmlspecialchars($row['date']) ?></td>
          <td><?= htmlspecialchars($row['time_in']) ?></td>
          <td><?= htmlspecialchars($row['time_out']) ?></td>
          <td><?= htmlspecialchars($row['reset_at']) ?></td>
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="5" style="text-align:center;">No history found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
