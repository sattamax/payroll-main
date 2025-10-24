<?php
session_start();

// ✅ Database connection
$host = "localhost";
$dbname = "employee_system";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// ✅ Create payroll_history table if it doesn’t exist
$pdo->exec("
    CREATE TABLE IF NOT EXISTS payroll_history (
        id INT AUTO_INCREMENT PRIMARY KEY,
        employee_id INT NOT NULL,
        full_name VARCHAR(255),
        regular_pay DECIMAL(10,2),
        overtime_pay DECIMAL(10,2),
        total_deductions DECIMAL(10,2),
        cash_advance DECIMAL(10,2),
        net_pay DECIMAL(10,2),
        snapshot_date DATETIME DEFAULT CURRENT_TIMESTAMP
    )
");

// ✅ Only save if today's snapshot not yet saved
$today = date('Y-m-d');
$checkStmt = $pdo->prepare("SELECT COUNT(*) FROM payroll_history WHERE DATE(snapshot_date) = ?");
$checkStmt->execute([$today]);
$alreadySaved = $checkStmt->fetchColumn();

// ✅ Get applied deductions from session (0 if not applied)
$totalDeductions = $_SESSION['applied_deductions'] ?? 0;

if ($alreadySaved == 0) {

    // ✅ Get all current payroll data before reset
    $stmt = $pdo->query("
        SELECT 
            u.id, 
            u.full_name, 
            p.rate_per_hour, 
            IFNULL(SUM(TIMESTAMPDIFF(MINUTE, a.time_in, a.time_out)) / 60, 0) AS total_hours,
            IFNULL(p.rate_per_hour * SUM(TIMESTAMPDIFF(MINUTE, a.time_in, a.time_out)) / 60, 0) AS regular_gross,
            (
                SELECT IFNULL(SUM(ot.hours * ot.rate), 0)
                FROM overtime ot
                WHERE ot.user_id = u.id
            ) AS overtime_pay,
            IFNULL(SUM(ca.amount), 0) AS cash_advance
        FROM users u
        LEFT JOIN positions p ON u.position_id = p.id
        LEFT JOIN attendance_logs a ON u.id = a.user_id
        LEFT JOIN cash_advance ca ON u.id = ca.user_id
        WHERE u.role = 'employee'
        GROUP BY u.id, u.full_name, p.rate_per_hour
    ");
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ✅ Save each employee’s data into payroll_history
    if ($employees) {
        $insert = $pdo->prepare("
            INSERT INTO payroll_history 
            (employee_id, full_name, regular_pay, overtime_pay, total_deductions, cash_advance, net_pay, snapshot_date)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
        ");

        foreach ($employees as $emp) {
            $gross = $emp['regular_gross'] + $emp['overtime_pay'];
            $ca = $emp['cash_advance'];
            $net = $gross - $totalDeductions - $ca;

            $insert->execute([
                $emp['id'],
                $emp['full_name'],
                $emp['regular_gross'],
                $emp['overtime_pay'],
                $totalDeductions,
                $ca,
                $net
            ]);
        }
    }
}

// ✅ Reset tables (attendance, cash advance, overtime)
$pdo->exec("TRUNCATE TABLE attendance_logs");
$pdo->exec("TRUNCATE TABLE cash_advance");
$pdo->exec("TRUNCATE TABLE overtime");

// ✅ Clear session deductions after reset
unset($_SESSION['applied_deductions']);

// ✅ Redirect back with message
echo "
<script>
    alert('✅ Payroll data saved to history and reset successfully!');
    window.location.href = 'payroll.php';
</script>
";
?>