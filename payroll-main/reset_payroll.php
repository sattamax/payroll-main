    <?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Database connection
    $host = "localhost";
    $dbname = "employee_system";
    $user = "root";
    $pass = "";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("❌ Database connection failed: " . $e->getMessage());
    }

    // Disable foreign key checks temporarily
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");

    // Create payroll_history table if not exists
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS payroll_history (
            id INT AUTO_INCREMENT PRIMARY KEY,
            employee_id INT NOT NULL,
            full_name VARCHAR(255),
            regular_pay DECIMAL(10,2),
            overtime_pay DECIMAL(10,2),
            holiday_pay DECIMAL(10,2) DEFAULT 0,
            special_pay DECIMAL(10,2) DEFAULT 0,
            leave_pay DECIMAL(10,2) DEFAULT 0,
            total_deductions DECIMAL(10,2),
            cash_advance DECIMAL(10,2),
            net_pay DECIMAL(10,2),
            snapshot_date DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");

    $today = date('Y-m-d');
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM payroll_history WHERE DATE(snapshot_date) = ?");
    $checkStmt->execute([$today]);
    $alreadySaved = $checkStmt->fetchColumn();

    if ($alreadySaved == 0) {
        // Fetch current payroll data
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
                (
                    SELECT IFNULL(SUM(h.amount), 0)
                    FROM holiday_pay h
                    WHERE h.user_id = u.id
                ) AS holiday_pay,
                (
                    SELECT IFNULL(SUM(s.amount), 0)
                    FROM special_pay s
                    WHERE s.user_id = u.id
                ) AS special_pay,
                (
                    SELECT IFNULL(SUM(l.amount), 0)
                    FROM leave_pay l
                    WHERE l.user_id = u.id
                ) AS leave_pay,
                IFNULL(SUM(ca.amount), 0) AS cash_advance
            FROM users u
            LEFT JOIN positions p ON u.position_id = p.id
            LEFT JOIN attendance_logs a ON u.id = a.user_id
            LEFT JOIN cash_advance ca ON u.id = ca.user_id
            WHERE u.role = 'employee'
            GROUP BY u.id, u.full_name, p.rate_per_hour
        ");
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $insert = $pdo->prepare("
            INSERT INTO payroll_history 
            (employee_id, full_name, regular_pay, overtime_pay, holiday_pay, special_pay, leave_pay, total_deductions, cash_advance, net_pay, snapshot_date)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");

        foreach ($employees as $emp) {
            // Fetch total deductions for employee
            $deductStmt = $pdo->prepare("SELECT IFNULL(SUM(amount), 0) FROM deductions WHERE employee_id = ?");
            $deductStmt->execute([$emp['id']]);
            $deductions = $deductStmt->fetchColumn() ?: 0;

            $gross = $emp['regular_gross'] + $emp['overtime_pay'] + $emp['holiday_pay'] + $emp['special_pay'] + $emp['leave_pay'];
            $net = $gross - $deductions - $emp['cash_advance'];

            $insert->execute([
                $emp['id'],
                $emp['full_name'],
                $emp['regular_gross'],
                $emp['overtime_pay'],
                $emp['holiday_pay'],
                $emp['special_pay'],
                $emp['leave_pay'],
                $deductions,
                $emp['cash_advance'],
                $net
            ]);
        }
    }

    // Safely clear payroll-related tables
    $tables = ['attendance_logs','overtime','cash_advance','holiday_pay','special_pay','leave_pay','deductions'];
    foreach($tables as $table) {
        try {
            $pdo->exec("TRUNCATE TABLE $table");
        } catch(PDOException $e) {
            // Optional: log error instead of alert
            error_log("Failed to truncate $table: ".$e->getMessage());
        }
    }

    // Re-enable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

    // Redirect back silently without prompt
    header("Location: payroll.php");
    exit;
    ?>
