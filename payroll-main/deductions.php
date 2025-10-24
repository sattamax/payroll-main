<?php
session_start();
require 'config.php';

// ✅ Restrict access to admins only
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Access denied.");
}

// ✅ Fetch all deductions with employee names and positions
$stmt = $pdo->query("
    SELECT 
        d.id,
        u.full_name AS employee_name,
        p.title AS position_title,
        d.type,
        d.amount,
        d.status,
        d.created_at
    FROM deductions d
    LEFT JOIN users u ON d.employee_id = u.id
    LEFT JOIN positions p ON u.position_id = p.id
    ORDER BY d.id DESC
");
$deductions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ Fetch all positions for modal dropdown
$positions = $pdo->query("SELECT id, title FROM positions ORDER BY title ASC")->fetchAll(PDO::FETCH_ASSOC);

// ✅ Check for success message after applying deductions
$applied = $_GET['applied'] ?? 0;

// ✅ Handle adding deduction by position
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_by_position') {
    $position_id = $_POST['position_id'];
    $type = $_POST['type'];
    $amount = $_POST['amount'];

    // Get all employees in that position
    $userStmt = $pdo->prepare("SELECT id FROM users WHERE position_id = ?");
    $userStmt->execute([$position_id]);
    $employees = $userStmt->fetchAll(PDO::FETCH_ASSOC);

    if ($employees) {
        $insert = $pdo->prepare("INSERT INTO deductions (employee_id, type, amount, status, created_at) VALUES (?, ?, ?, 'Pending', NOW())");
        foreach ($employees as $emp) {
            $insert->execute([$emp['id'], $type, $amount]);
        }
        // Redirect to avoid form resubmission
        header("Location: deductions.php");
        exit;
    } else {
        $error = "No employees found for this position.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>💸 Manage Deductions</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: url('uploads/bg.jpg') no-repeat center center fixed;
    background-size: cover;
    font-family: 'Courier New', monospace;
}
.main-content {
    max-width: 1000px;
    margin: 40px auto;
    padding: 60px 30px;
    background-color: rgba(255, 255, 255, 0.95);
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}
h2 { font-weight: 600; color: #333; margin-bottom: 30px; }
thead { background-color: #343a40; color: white; }
tr:hover { background-color: #f8f9fa; }
.action-buttons .btn { margin-right: 5px; }
.status-badge { padding: 5px 10px; border-radius: 5px; font-weight: 500; }
.status-Pending { background-color: #f0ad4e; color: white; }
.status-Applied { background-color: #28a745; color: white; }
</style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="main-content">
    <h2>💸 Manage Deductions</h2>

    <!-- Buttons -->
    <div class="d-flex mb-3 gap-2">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDeductionModal">➕ Add Deduction</button>

       

        <?php if (!empty($error)): ?>
            <span class="text-danger fw-bold ms-2 mt-2"><?= htmlspecialchars($error) ?></span>
        <?php endif; ?>
    </div>

    <!-- Deductions Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Employee</th>
                    <th>Position</th>
                    <th>Type</th>
                    <th>Amount (₱)</th>
                    <th>Status</th>
                    <th>Date Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (count($deductions) > 0): ?>
                <?php foreach ($deductions as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['employee_name'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['position_title'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['type']) ?></td>
                    <td>₱<?= number_format($row['amount'], 2) ?></td>
                    <td>
                        <span class="status-badge status-<?= $row['status'] ?>"><?= $row['status'] ?></span>
                    </td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                    <td class="action-buttons">
                        <a href="delete_deduction.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this deduction?');">🗑️ Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8" class="text-center">No deductions found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Deduction Modal -->
<div class="modal fade" id="addDeductionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="deductions.php" method="POST">
        <input type="hidden" name="action" value="add_by_position" />
        <div class="modal-header">
          <h5 class="modal-title">➕ Add Deduction by Position</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <label class="form-label">Position:</label>
          <select name="position_id" class="form-select" required>
              <option value="">-- Select Position --</option>
              <?php foreach($positions as $pos): ?>
                  <option value="<?= $pos['id'] ?>"><?= htmlspecialchars($pos['title']) ?></option>
              <?php endforeach; ?>
          </select>

          <label class="form-label mt-3">Type:</label>
          <input type="text" name="type" class="form-control" required placeholder="e.g. SSS, Pag-IBIG, Late">

          <label class="form-label mt-3">Amount (₱):</label>
          <input type="number" name="amount" step="0.01" class="form-control" required placeholder="Enter amount">
        </div>
        <div class="modal-footer">
          <button class="btn btn-success" type="submit">💾 Save</button>
          <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
