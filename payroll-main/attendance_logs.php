<?php
session_start();
require 'config.php';

// ✅ Fetch attendance logs (still connected to payroll calculations)
$role = $_SESSION['user']['role'] ?? '';
$user_id = $_SESSION['user']['id'] ?? '';

if ($role === 'admin') {
    $stmt = $pdo->query("
        SELECT a.*, u.full_name 
        FROM attendance_logs a 
        JOIN users u ON a.user_id = u.id 
        ORDER BY a.date DESC
    ");
} else {
    $stmt = $pdo->prepare("
        SELECT a.*, u.full_name 
        FROM attendance_logs a 
        JOIN users u ON a.user_id = u.id 
        WHERE a.user_id = ? 
        ORDER BY a.date DESC
    ");
    $stmt->execute([$user_id]);
}
$logs = $stmt->fetchAll();
?>

<?php include 'includes/header.php'; ?>

<style>
  body {
    background: url('uploads/bg.jpg') no-repeat center center fixed;
    background-size: cover;
    margin: 0;
    font-family:'Courier New', monospace;
  }

  .main-content {
    padding: 30px;
    margin: 30px;
    border-radius: 15px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    color: #000;
    transition: margin-left 0.3s ease;
  }

  body.sidebar-open .main-content {
    margin-left: 270px;
  }

  h2 {
    margin-bottom: 20px;
    color: #fff;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.4);
  }

  table {
    width: 100%;
    border-collapse: collapse;
    background: rgba(255, 255, 255, 0.85);
    border-radius: 10px;
    overflow: hidden;
  }

  thead {
    background-color: rgba(0, 102, 204, 0.9);
    color: white;
  }

  th, td {
    padding: 12px 15px;
    border: 1px solid #ccc;
    text-align: left;
  }

  tbody tr:nth-child(even) {
    background-color: rgba(255, 255, 255, 0.6);
  }

  tbody tr:hover {
    background-color: rgba(217, 232, 255, 0.8);
  }

  .action-btn {
    padding: 6px 12px;
    margin-right: 8px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
  }

  .delete-btn {
    background-color: #f44336;
    color: white;
  }

  .delete-btn:hover {
    background-color: #da190b;
  }

  .btn-top {
    margin-bottom: 15px;
    text-align: right;
  }

  #popupMessage {
    display: none;
    position: fixed;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(255,255,255,0.95);
    color: #222;
    padding: 20px 30px;
    border-radius: 12px;
    box-shadow: 0 4px 25px rgba(0,0,0,0.3);
    font-size: 18px;
    text-align: center;
    z-index: 1000;
    min-width: 300px;
    font-weight: bold;
  }
</style>

<div class="main-content">
  <h2>Attendance Logs</h2>

  <!-- Removed Reset Attendance (Payroll handles that) -->
  <?php if ($role === 'admin'): ?>
  <div class="btn-top">
    <a href="attendance_history.php" class="action-btn" style="background-color:#4caf50; color:white;">📜 View History</a>
  </div>
   

  <?php endif; ?>

  <table>
    <thead>
      <tr>
        <th>Full Name</th>
        <th>Date</th>
        <th>Time In</th>
        <th>Time Out</th>
        
      </tr>
    </thead>
    <tbody>
      <?php if (count($logs) > 0): ?>
        <?php foreach ($logs as $log): ?>
        <tr>
          <td><?= htmlspecialchars($log['full_name']) ?></td>
          <td><?= htmlspecialchars($log['date']) ?></td>
          <td><?= htmlspecialchars($log['time_in']) ?></td>
          <td><?= htmlspecialchars($log['time_out']) ?></td>
        
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="5" style="text-align:center;">No attendance records found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<div id="popupMessage"><?= $_SESSION['popup_message'] ?? '' ?></div>
<?php unset($_SESSION['popup_message']); ?>

<script>
  window.onload = function() {
    const popup = document.getElementById('popupMessage');
    if (popup.textContent.trim() !== '') {
      popup.style.display = 'block';
      setTimeout(() => popup.style.display = 'none', 4000);
    }
  };
</script>
