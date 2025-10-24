<?php
session_start();
include 'includes/header.php';
require 'config.php';

// ✅ Fetch all payroll data (Applied + Pending)
$stmt = $pdo->query("
    SELECT 
        u.id, u.full_name, p.rate_per_hour,

        -- Regular hours and pay
        IFNULL(SUM(TIMESTAMPDIFF(MINUTE, a.time_in, a.time_out))/60, 0) AS total_hours,
        IFNULL(p.rate_per_hour * SUM(TIMESTAMPDIFF(MINUTE, a.time_in, a.time_out))/60, 0) AS regular_gross,

        -- Overtime pay (no status)
        IFNULL((SELECT SUM(ot.hours * ot.rate) FROM overtime ot WHERE ot.user_id = u.id), 0) AS overtime_pay,

        -- Holiday, Special, and Leave Pay (Applied + Pending)
        IFNULL((SELECT SUM(amount) FROM holiday_pay WHERE user_id = u.id), 0) AS holiday_pay,
        IFNULL((SELECT SUM(amount) FROM special_pay WHERE user_id = u.id), 0) AS special_pay,
        IFNULL((SELECT SUM(amount) FROM leave_pay WHERE user_id = u.id), 0) AS leave_pay,

        -- Cash Advance and Deductions (Applied + Pending)
        IFNULL((SELECT SUM(amount) FROM cash_advance WHERE user_id = u.id), 0) AS cash_advance,
        IFNULL((SELECT SUM(amount) FROM deductions WHERE employee_id = u.id), 0) AS total_deductions,

        -- Check if there are still pending items
        (
            (SELECT COUNT(*) FROM holiday_pay WHERE user_id=u.id AND status='Pending') +
            (SELECT COUNT(*) FROM special_pay WHERE user_id=u.id AND status='Pending') +
            (SELECT COUNT(*) FROM leave_pay WHERE user_id=u.id AND status='Pending') +
            (SELECT COUNT(*) FROM cash_advance WHERE user_id=u.id AND status='Pending') +
            (SELECT COUNT(*) FROM deductions WHERE employee_id=u.id AND status='Pending')
        ) AS pending_count

    FROM users u
    LEFT JOIN positions p ON u.position_id = p.id
    LEFT JOIN attendance_logs a ON u.id = a.user_id
    WHERE u.role = 'employee'
    GROUP BY u.id, u.full_name, p.rate_per_hour
    ORDER BY u.full_name
");

$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Employee Payroll</title>
<style>
body {
    margin:0;
    padding:0;
    font-family: Arial, sans-serif;
    background:url('uploads/bg.jpg') no-repeat center center fixed;
    background-size:cover;
}
.main-content { margin-left:0; transition:margin-left 0.3s ease; padding:20px; }
body.sidebar-open .main-content { margin-left:200px; }
.content { background:rgba(255,255,255,0.95); padding:20px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.1); }
table { border-collapse:collapse; width:100%; background-color:white; }
th, td { border:1px solid #aaa; padding:8px 12px; text-align:left; }
th { background-color:#3498db; color:white; }
tr:nth-child(even) { background-color:#f2f2f2; }
h2 { text-align:center; color:#222; margin-bottom:20px; }
.btn-container { margin-bottom:15px; text-align:right; }
.btn { background-color:#3498db; color:white; border:none; padding:8px 16px; border-radius:6px; cursor:pointer; margin-left:5px; text-decoration:none; display:inline-block; }
.btn:hover { background-color:#217dbb; }
.btn-danger { background-color:#e74c3c; }
.btn-danger:hover { background-color:#c0392b; }
.btn-success { background-color:#2ecc71; }
.btn-success:hover { background-color:#27ae60; }
.apply-btn { background-color:#f39c12; color:#fff; border:none; padding:5px 10px; border-radius:5px; cursor:pointer; }
.apply-btn:hover { background-color:#e67e22; }
.status-applied { color:green; font-weight:bold; }
.status-pending { color:orange; font-weight:bold; }

/* ✅ Floating centered confirmation box */
.modal-overlay {
    display:none;
    position:fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:rgba(0,0,0,0.5);
    justify-content:center;
    align-items:center;
    z-index:9999;
}
.modal-box {
    background:#fff;
    padding:30px 40px;
    border-radius:12px;
    text-align:center;
    box-shadow:0 0 15px rgba(0,0,0,0.3);
    max-width:400px;
}
.modal-box h3 {
    margin-bottom:20px;
    font-size:20px;
    color:#333;
}
.modal-box .btn {
    margin:10px;
    padding:10px 20px;
    font-size:15px;
}
</style>
</head>
<body>

<div class="main-content">
<div class="content">
<h2>Employee Payroll Summary</h2>

<div class="btn-container">
  <button type="button" class="btn btn-danger" id="resetBtn">🔄 Reset Payroll</button>
  <a href="payroll_history.php" class="btn btn-success">📜 View History</a>
</div>

<table>
<thead>
<tr>
<th>Employee ID</th>
<th>Full Name</th>
<th>Regular Pay (₱)</th>
<th>Overtime Pay (₱)</th>
<th>Holiday Pay (₱)</th>
<th>Special Pay (₱)</th>
<th>Leave Pay (₱)</th>
<th>Total Deductions (₱)</th>
<th>Cash Advance (₱)</th>
<th><strong>Net Pay (₱)</strong></th>
<th>Status</th>
<th>Apply</th>
</tr>
</thead>
<tbody>
<?php if(count($employees)>0): ?>
    <?php foreach($employees as $emp): 
        $gross = $emp['regular_gross'] + $emp['overtime_pay'] + $emp['holiday_pay'] + $emp['special_pay'] + $emp['leave_pay'];
        $net = $gross - $emp['total_deductions'] - $emp['cash_advance'];
        $status = $emp['pending_count'] > 0 ? 'Pending' : 'Applied';
    ?>
    <tr>
        <td><?= str_pad($emp['id'],4,'0',STR_PAD_LEFT) ?></td>
        <td><?= htmlspecialchars($emp['full_name']) ?></td>
        <td><?= number_format($emp['regular_gross'],2) ?></td>
        <td><?= number_format($emp['overtime_pay'],2) ?></td>
        <td><?= number_format($emp['holiday_pay'],2) ?></td>
        <td><?= number_format($emp['special_pay'],2) ?></td>
        <td><?= number_format($emp['leave_pay'],2) ?></td>
        <td><?= number_format($emp['total_deductions'],2) ?></td>
        <td><?= number_format($emp['cash_advance'],2) ?></td>
        <td><strong><?= number_format($net,2) ?></strong></td>
        <td class="<?= $status=='Pending' ? 'status-pending' : 'status-applied' ?>"><?= $status ?></td>
        <td>
        <?php if($status=='Pending'): ?>
            <form action="apply_pay.php" method="post">
                <input type="hidden" name="user_id" value="<?= $emp['id'] ?>">
                <button class="apply-btn" type="submit">Apply</button>
            </form>
        <?php else: ?>
            Applied
        <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
<tr><td colspan="12" style="text-align:center;">No employees found.</td></tr>
<?php endif; ?>
</tbody>
</table>

</div>
</div>

<!-- ✅ Custom Floating Modal -->
<div class="modal-overlay" id="confirmModal">
  <div class="modal-box">
    <h3>Are you sure you want to Reset all Payroll?</h3>
    <form action="reset_payroll.php" method="post" style="display:inline;">
      <button type="submit" class="btn btn-danger">Yes, Reset</button>
    </form>
    <button type="button" class="btn btn-secondary" id="cancelBtn">Cancel</button>
  </div>
</div>

<script>
const resetBtn = document.getElementById('resetBtn');
const modal = document.getElementById('confirmModal');
const cancelBtn = document.getElementById('cancelBtn');

resetBtn.addEventListener('click', () => {
    modal.style.display = 'flex';
});

cancelBtn.addEventListener('click', () => {
    modal.style.display = 'none';
});

window.addEventListener('click', (e) => {
    if (e.target === modal) {
        modal.style.display = 'none';
    }
});
</script>

</body>
</html>
    