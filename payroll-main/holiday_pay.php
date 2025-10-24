<?php
include 'includes/header.php';
require 'config.php';

// Fetch holiday pay records with employee name & position
$stmt = $pdo->query("
    SELECT h.*, u.full_name, p.title AS position_title
    FROM holiday_pay h
    JOIN users u ON h.user_id = u.id
    LEFT JOIN positions p ON u.position_id = p.id
    ORDER BY h.holiday_date DESC
");
$holiday_pays = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all positions for Add modal
$positions = $pdo->query("SELECT id, title FROM positions ORDER BY title ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

<style>
body {
  background: url('uploads/bg.jpg') no-repeat center center fixed;
  background-size: cover;
  font-family:'Courier New', monospace;
  margin: 0;
}
.main-content {
  padding: 40px;
}
.card {
  background: rgba(255, 255, 255, 0.96);
  border-radius: 15px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  padding: 30px;
}
h2 {
  font-size: 28px;
  font-weight: 600;
  margin-bottom: 25px;
  color: #333;
  text-align: left;
}
.top-buttons {
  display: flex;
  justify-content: flex-start;
  align-items: center;
  gap: 15px;
  margin-bottom: 25px;
  flex-wrap: wrap;
}
.btn-add {
  font-size: 16px;
  padding: 10px 20px;
  background-color: #007bff;
  color: white;
  border-radius: 6px;
  border: none;
  transition: 0.3s;
}
.btn-add:hover { background-color: #0056b3; }

.btn-success {
  padding: 10px 25px;
  font-size: 16px;
  border-radius: 6px;
}

.table {
  background-color: rgba(255,255,255,0.95);
}
th, td {
  padding: 14px 18px;
  border: 1px solid #dee2e6;
  vertical-align: middle;
}
thead th {
  background-color: #007bff;
  color: white;
  text-align: center;
}
tbody tr:hover {
  background-color: rgba(241,243,245,0.8);
}
.btn-sm {
  padding: 6px 10px;
  font-size: 14px;
}
.status-badge {
  padding: 5px 10px;
  border-radius: 5px;
  font-weight: 500;
}
.status-Pending { background-color: #f0ad4e; color: white; }
.status-Applied { background-color: #28a745; color: white; }
</style>

<div class="main-content">
  <div class="card">
    <h2>🎉 Holiday Pay Management</h2>

    <!-- Top Buttons -->
    <div class="top-buttons">
      <button type="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#addHolidayPayModal">
        ➕ Add Holiday Pay
      </button>

      
      </form>
    </div>

    <!-- Holiday Pay Table -->
    <div class="table-responsive">
      <table class="table table-hover align-middle text-center">
        <thead>
          <tr>
            <th>Date</th>
            <th>Employee ID</th>
            <th>Name</th>
            <th>Position</th>
            <th>Amount (₱)</th>
            <th>Status</th>
            <th>Tools</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($holiday_pays) > 0): ?>
            <?php foreach ($holiday_pays as $hp): ?>
              <tr>
                <td><?= htmlspecialchars($hp['holiday_date']) ?></td>
                <td><?= htmlspecialchars($hp['user_id']) ?></td>
                <td><?= htmlspecialchars($hp['full_name']) ?></td>
                <td><?= htmlspecialchars($hp['position_title'] ?? 'N/A') ?></td>
                <td>₱<?= number_format($hp['amount'], 2) ?></td>
                <td>
                  <span class="status-badge status-<?= $hp['status'] ?>"><?= $hp['status'] ?></span>
                </td>
                <td>
                  <a href="delete_holiday_pay.php?id=<?= $hp['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?')">
                    🗑️ Delete
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="7" class="text-center">No holiday pay records found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Holiday Pay Modal -->
<div class="modal fade" id="addHolidayPayModal" tabindex="-1" aria-labelledby="addHolidayPayLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="add_holiday_pay.php" method="POST">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="addHolidayPayLabel">➕ Add Holiday Pay (by Position)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <label>Date:</label>
          <input type="date" name="holiday_date" required class="form-control mb-3">

          <label>Position:</label>
          <select name="position_id" class="form-control mb-3" required>
            <option value="">Select position</option>
            <?php foreach ($positions as $p): ?>
              <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['title']) ?></option>
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
