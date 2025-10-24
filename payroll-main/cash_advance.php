<?php 
include 'includes/header.php'; 
require 'config.php';

// Fetch cash advances with user names
$stmt = $pdo->query("
  SELECT ca.*, u.full_name 
  FROM cash_advance ca 
  JOIN users u ON ca.user_id = u.id 
  ORDER BY ca.ca_date DESC
");
$cash_advances = $stmt->fetchAll();

// Fetch users for Add modal dropdown only
$users = $pdo->query("SELECT id, full_name FROM users ORDER BY full_name ASC")->fetchAll();
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

<style>
body { background: url('uploads/bg.jpg') no-repeat center center fixed; background-size: cover; font-family: 'Courier New', monospace; margin: 0; }
.main-content { padding: 40px; }
.card { background: rgba(255, 255, 255, 0.96); border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); padding: 30px; }
h2 { font-size: 28px; font-weight: 600; margin-bottom: 25px; color: #333; text-align: left; }
.top-buttons { display: flex; justify-content: flex-start; align-items: center; gap: 15px; margin-bottom: 25px; flex-wrap: wrap; }
.btn-add, .btn-apply { font-size: 16px; padding: 10px 20px; border: none; border-radius: 6px; color: white; cursor: pointer; transition: 0.3s; }
.btn-add { background-color: #007bff; }
.btn-add:hover { background-color: #0056b3; }
.btn-apply { background-color: #28a745; }
.btn-apply:hover { background-color: #1e7e34; }

/* Table styling */
.table { width: 100%; border-collapse: collapse; background-color: rgba(255,255,255,0.95); }
th, td { padding: 14px 18px; border: 1px solid #dee2e6; vertical-align: middle; }
thead th { background-color: #007bff; color: white; text-align: center; }
tbody tr:hover { background-color: rgba(241,243,245,0.7); }
.btn-sm { padding: 5px 10px; font-size: 14px; }

/* Status badges */
.status-badge { padding: 5px 10px; border-radius: 6px; font-weight: 500; font-size: 0.9rem; display: inline-block; min-width: 80px; text-align: center; }
.status-Pending { background-color: #f0ad4e; color: white; }
.status-Applied { background-color: #28a745; color: white; }
</style>

<div class="main-content">
  <div class="card">
    <h2>💸 Cash Advance Management</h2>

    <div class="top-buttons">
      <button type="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#addCashAdvanceModal">➕ Add Cash Advance</button>
      
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle text-center">
        <thead>
          <tr>
            <th>Date</th>
            <th>Employee ID</th>
            <th>Name</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Tools</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cash_advances as $ca): ?>
            <tr>
              <td><?= htmlspecialchars($ca['ca_date']) ?></td>
              <td><?= htmlspecialchars($ca['user_id']) ?></td>
              <td><?= htmlspecialchars($ca['full_name']) ?></td>
              <td>₱<?= number_format($ca['amount'], 2) ?></td>
              <td>
                <span class="status-badge status-<?= $ca['status'] ?>"><?= $ca['status'] ?></span>
              </td>
              <td>
                <a href="delete_cash_advance.php?id=<?= $ca['id'] ?>" class="btn btn-sm btn-danger">🗑️ Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Cash Advance Modal -->
<div class="modal fade" id="addCashAdvanceModal" tabindex="-1" aria-labelledby="addCashAdvanceLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="add_cash_advance.php" method="POST">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="addCashAdvanceLabel">➕ Add Cash Advance</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <label>Date:</label>
          <input type="date" name="ca_date" required class="form-control mb-3">
          <label>Employee:</label>
          <select name="user_id" class="form-control mb-3" required>
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
