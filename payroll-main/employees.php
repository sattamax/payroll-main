<?php include 'includes/header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee List</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: url('uploads/bg.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            font-family: 'Courier New', monospace;
        }
        .main-content { padding: 30px; min-height: 100vh; }
        .main-content-inner {
            background-color: rgba(255,255,255,0.9);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.15);
        }
        .profile-pic { width:50px; height:50px; object-fit:cover; border-radius:50%; }
        .btn { padding:8px 16px; border-radius:5px; font-weight:bold; font-size:14px; }
        thead { background:#0066cc; color:#fff; }
        .btn-warning { color: white; }
    </style>
</head>
<body>

<div class="main-content">
    <div class="main-content-inner">
        <h2>Employee List</h2>

        <!-- Buttons -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <a href="add_user.php" class="btn btn-primary">➕ New</a>
                <a href="archived_users.php" class="btn btn-secondary">🗂️ View Archived</a>
            </div>

            <form method="get" class="d-flex" style="max-width:350px;">
                <input type="text" name="search" class="form-control me-2"
                       placeholder="🔍 Search name, email, or role..."
                       value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button type="submit" class="btn btn-secondary">Search</button>
            </form>
        </div>

        <?php
        require 'config.php';

        // Search filter
        $search = $_GET['search'] ?? '';
        if ($search) {
            $stmt = $pdo->prepare("
                SELECT u.*, p.title AS position_title 
                FROM users u 
                LEFT JOIN positions p ON u.position_id = p.id
                WHERE (u.full_name LIKE ? OR u.email LIKE ? OR u.role LIKE ?)
                AND u.archived = 0
                ORDER BY u.full_name ASC
            ");
            $stmt->execute(["%$search%", "%$search%", "%$search%"]);
        } else {
            $stmt = $pdo->query("
                SELECT u.*, p.title AS position_title 
                FROM users u 
                LEFT JOIN positions p ON u.position_id = p.id
                WHERE u.archived = 0
                ORDER BY u.full_name ASC
            ");
        }
        $users = $stmt->fetchAll();
        ?>

        <!-- Show search results -->
        <?php if ($search): ?>
            <p><strong>Showing results for:</strong> “<?= htmlspecialchars($search) ?>” (<?= count($users) ?> found)</p>
        <?php endif; ?>

        <!-- Table -->
        <table class="table table-bordered table-hover align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Profile</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Position</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (count($users) > 0): ?>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <!-- ✅ Show ID as 0001, 0002, 0003, etc. -->
                        <td><?= str_pad($u['id'], 4, '0', STR_PAD_LEFT) ?></td>

                        <td><img src="uploads/<?= htmlspecialchars($u['profile_pic'] ?: 'default-user.png') ?>"
                                 class="profile-pic" alt="Profile"></td>
                        <td><?= htmlspecialchars($u['full_name']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= htmlspecialchars($u['role']) ?></td>
                        <td><?= htmlspecialchars($u['position_title'] ?? 'N/A') ?></td>
                        <td>
                            <a href="#" class="btn btn-warning btn-sm archive-btn"
                               data-id="<?= $u['id'] ?>" data-bs-toggle="modal" data-bs-target="#archiveModal">
                                Archive
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center text-muted">No users found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Archive Modal -->
<div class="modal fade" id="archiveModal" tabindex="-1" aria-labelledby="archiveModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title" id="archiveModalLabel">Confirm Archive</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to archive this user?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

        <form id="archiveForm" method="post" action="archive_user.php" style="display:inline;">
            <input type="hidden" name="id" id="archive_id" value="">
            <button type="submit" class="btn btn-warning text-white">Yes, Archive</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script for Archive Button -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const archiveButtons = document.querySelectorAll('.archive-btn');
    const archiveIdInput = document.getElementById('archive_id');

    archiveButtons.forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-id');
            archiveIdInput.value = userId;
        });
    });
});
</script>

</body>
</html>