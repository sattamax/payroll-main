<?php
session_start();
require 'config.php';

// Admin access only
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Access denied.");
}

// Fetch overtime records with employee names
$stmt = $pdo->query("
    SELECT o.id, u.full_name, o.ot_date, o.hours, o.rate, (o.hours * o.rate) AS total
    FROM overtime o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.id DESC
");
$overtime = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Manage Overtime</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: url('uploads/bg.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Courier New', monospace;
            margin: 0;
            padding: 0;
        }
        .main-content {
            max-width: 1100px;
            margin: 40px auto !important;
            padding: 90px 30px 25px;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            position: relative;
        }
        h2 {
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }
        .top-btn {
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px 16px;
            border-bottom: 1px solid #e1e1e1;
            text-align: left;
        }
        thead {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .action-buttons .btn {
            margin-right: 6px;
        }
    </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="main-content">
    <h2>🕒 Overtime Records</h2>

    <!-- Add Overtime Button -->
    <button class="btn btn-primary top-btn" data-bs-toggle="modal" data-bs-target="#addOvertimeModal">
        ➕ Add Overtime
    </button>

    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Date</th>
                <th>Hours</th>
                <th>Rate </th>
                <th>Total </th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (count($overtime) > 0): ?>
            <?php foreach ($overtime as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                    <td><?= htmlspecialchars($row['ot_date']) ?></td>
                    <td><?= htmlspecialchars($row['hours']) ?></td>
                    <td><?= number_format($row['rate'], 2) ?></td>
                    <td><?= number_format($row['total'], 2) ?></td>
                    <td class="action-buttons">
                        <button
                            class="btn btn-secondary edit-btn"
                            data-id="<?= $row['id'] ?>"
                            data-bs-toggle="modal"
                            data-bs-target="#editOvertimeModal"
                        >
                            ✏️ Edit
                        </button>
                        <a class="btn btn-danger"
                           href="delete_overtime.php?id=<?= $row['id'] ?>"
                           onclick="return confirm('Are you sure you want to delete this overtime record?');">
                           🗑️ Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6">No overtime records found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Add Overtime Modal -->
<div class="modal fade" id="addOvertimeModal" tabindex="-1" aria-labelledby="addOvertimeLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="add_overtime.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="addOvertimeLabel">➕ Add Overtime</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <label>Employee:</label>
          <select name="user_id" class="form-control" required>
            <option value="">-- Select Employee --</option>
            <?php
            $users = $pdo->query("SELECT id, full_name FROM users")->fetchAll();
            foreach ($users as $user): ?>
                <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['full_name']) ?></option>
            <?php endforeach; ?>
          </select>

          <label>Date:</label>
          <input type="date" name="ot_date" class="form-control" required>

          <label>Hours:</label>
          <input type="number" name="hours" step="0.01" class="form-control" required>

          <label>Rate (₱):</label>
          <input type="number" name="rate" step="0.01" class="form-control" required>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success" type="submit">Save</button>
          <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Overtime Modal -->
  <div class="modal fade" id="editOvertimeModal" tabindex="-1" aria-labelledby="editOvertimeLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="edit_overtime.php" method="POST" id="editOvertimeForm">
          <input type="hidden" name="id" id="edit_id" />
          <div class="modal-header">
            <h5 class="modal-title" id="editOvertimeLabel">✏️ Edit Overtime</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <label>Employee:</label>
            <select name="user_id" id="edit_user_id" class="form-control" required>
              <?php foreach ($users as $user): ?>
                  <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['full_name']) ?></option>
              <?php endforeach; ?>
            </select>

            <label>Date:</label>
            <input type="date" name="ot_date" id="edit_ot_date" class="form-control" required>

            <label>Hours:</label>
            <input type="number" name="hours" id="edit_hours" step="0.01" class="form-control" required>

            <label>Rate (₱):</label>
            <input type="number" name="rate" id="edit_rate" step="0.01" class="form-control" required>
          </div>
          <div class="modal-footer">
            <button class="btn btn-primary" type="submit">Update</button>
            <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', () => {
        const id = button.getAttribute('data-id');

        fetch(`edit_overtime.php?id=${id}&action=fetch`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_id').value = data.id;
                document.getElementById('edit_user_id').value = data.user_id;
                document.getElementById('edit_ot_date').value = data.ot_date;
                document.getElementById('edit_hours').value = data.hours;
                document.getElementById('edit_rate').value = data.rate;
            })
            .catch(() => alert('Failed to load overtime data.'));
    });
});
</script>

</body>
</html>

