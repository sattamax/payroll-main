<?php
include 'includes/header.php';
require 'config.php';

// Fetch leave pay records with employee name
$stmt = $pdo->query("
    SELECT l.*, u.full_name 
    FROM leave_pay l
    JOIN users u ON l.user_id = u.id
    ORDER BY l.leave_date DESC
");
$leave_pays = $stmt->fetchAll();

// Fetch users for Add modal dropdown
$users = $pdo->query("SELECT id, full_name FROM users ORDER BY full_name ASC")->fetchAll();
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: url('uploads/bg.jpg') no-repeat center center fixed;
    background-size: cover;
    font-family: 'Courier New', monospace;
    margin: 0;
}
.main-content { padding: 40px; }
.card {
    background: rgba(255,255,255,0.95);
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    padding: 30px;
}
h2 { font-weight: 600; color: #333; margin-bottom: 25px; }

/* Buttons */
.top-buttons { display: flex; justify-content: flex-start; align-items: center; gap: 15px; margin-bottom: 25px; flex-wrap: wrap; }
.btn-add, .btn-apply { font-size: 16px; padding: 10px 20px; border: none; border-radius: 6px; color: white; cursor: pointer; transition: 0.3s; }
.btn-add { background-color: #007bff; }
.btn-add:hover { background-color: #0056b3; }
.btn-apply { background-color: #28a745; }
.btn-apply:hover { background-color: #1e7e34; }

/* Table */
.table { width: 100%; border-collapse: collapse; background-color: rgba(255,255,255,0.9); }
th, td { padding: 14px 18px; border: 1px solid #dee2e6; vertical-align: middle; }
thead th { background-color: #343a40; color: white; text-align: center; }
tbody tr:hover { background-color: rgba(241,243,245,0.7); }
.status-badge { padding: 5px 10px; border-radius: 5px; font-weight: 500; }
.status-Pending { background-color: #f0ad4e; color: white; }
.status-Applied { background-color: #28a745; color: white; }
.btn-sm { padding: 5px 10px; font-size: 14px; }
</style>

<div class="main-content">
  <div class="card">
    <h2>🏖️ Leave Pay Management</h2>

    <div class="top-buttons">
      <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addLeavePayModal">➕ Add Leave Pay</button>

    
    </div>

    <div class="table-responsive">
      <table class="table table-bordered text-center align-middle">
        <thead>
          <tr>
            <th>Date</th>
            <th>Employee ID</th>
            <th>Name</th>
            <th>Amount (₱)</th>
            <th>Status</th>
            <th>Tools</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($leave_pays): foreach ($leave_pays as $lp): ?>
            <tr>
              <td><?= htmlspecialchars($lp['leave_date']) ?></td>
              <td><?= htmlspecialchars($lp['user_id']) ?></td>
              <td><?= htmlspecialchars($lp['full_name']) ?></td>
              <td>₱<?= number_format($lp['amount'], 2) ?></td>
              <td>
                <span class="status-badge status-<?= $lp['status'] ?>"><?= $lp['status'] ?></span>
              </td>
              <td>
                <a href="delete_leave_pay.php?id=<?= $lp['id'] ?>" 
                   class="btn btn-danger btn-sm" 
                   onclick="return confirm('Delete this record?')">
                   🗑️ Delete
                </a>
              </td>
            </tr>
          <?php endforeach; else: ?>
            <tr><td colspan="6" class="text-center">No leave pay records found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Leave Pay Modal -->
<div class="modal fade" id="addLeavePayModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="add_leave_pay.php" method="POST">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">➕ Add Leave Pay</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <label>Date:</label>
          <input type="date" name="leave_date" required class="form-control mb-2">
          <label>Employee:</label>
          <select name="user_id" required class="form-control mb-2">
            <option value="">Select employee</option>
            <?php foreach ($users as $u): ?>
              <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['full_name']) ?></option>
            <?php endforeach; ?>
          </select>
          <label>Amount (₱):</label>
          <input type="number" name="amount" step="0.01" required class="form-control mb-2">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">💾 Save</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
