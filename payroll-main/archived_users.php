<?php
require 'config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// ✅ Optional: Allow only admin users to view archived employees
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: employees.php');
    exit;
}

// ✅ Fetch all archived employees
$stmt = $pdo->query("
    SELECT id, full_name, email, role 
    FROM users 
    WHERE archived = 1 
    ORDER BY full_name ASC
");
$archived_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Archived Employees</title>

    <!-- ✅ Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: url('uploads/bg.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Courier New', monospace;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            margin-top: 50px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.15);
        }
        thead {
            background-color: #0066cc;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4 text-center">🗂️ Archived Employees</h2>

    <!-- Back to employee list -->
    <div class="mb-3">
        <a href="employees.php" class="btn btn-primary">← Back to Active Employees</a>
    </div>

    <?php if (count($archived_users) > 0): ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($archived_users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['full_name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td>
                            <form action="restore_user.php" method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Restore this user?')">
                                    Restore
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-muted text-center mt-4">No archived employees found.</p>
    <?php endif; ?>
</div>

<!-- ✅ Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 