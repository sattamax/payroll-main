<?php
include 'includes/header.php';

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

// ✅ Fetch all payroll history
$stmt = $pdo->query("
    SELECT *
    FROM payroll_history
    ORDER BY snapshot_date DESC, full_name ASC
");
$history = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ Fetch unique snapshot dates
$datesStmt = $pdo->query("
    SELECT DISTINCT DATE(snapshot_date) AS snapshot_date 
    FROM payroll_history 
    ORDER BY snapshot_date DESC
");
$dates = $datesStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payroll History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family:'Courier New', monospace;
            background: url('uploads/bg.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .container {
            margin-top: 40px;
            background-color: rgba(255,255,255,0.95);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
        }
        th {
            background-color: #3498db;
            color: white;
            text-align: center;
        }
        td {
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total-row {
            background-color: #2ecc71 !important;
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">Payroll History</h2>

    <div class="mb-3 text-center">
        <a href="payroll.php" class="btn btn-primary">Back to Payroll</a>
        <a href="reset_payroll.php" class="btn btn-danger"
           onclick="return confirm('Are you sure you want to reset payroll? This will save current payroll to history.')">
           Reset Payroll & Save History
        </a>
    </div>

    <div class="mb-3 text-center">
        <strong>View History by Date:</strong><br>
        <?php if (!empty($dates)): ?>
            <?php foreach ($dates as $d): ?>
                <button class="btn btn-sm btn-outline-primary history-btn"
                        data-date="<?= htmlspecialchars($d['snapshot_date']) ?>">
                    <?= htmlspecialchars($d['snapshot_date']) ?>
                </button>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted mt-2">No payroll history yet.</p>
        <?php endif; ?>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="historyModalLabel">Payroll History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalBody"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" onclick="printModal()">🖨️ Print</button>
                    <button type="button" class="btn btn-danger" onclick="exportPDF()">📄 Export PDF</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
const historyData = <?php echo json_encode($history, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
let currentDate = null;

document.querySelectorAll('.history-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const date = btn.getAttribute('data-date');
        currentDate = date;
        const filtered = historyData.filter(h => h.snapshot_date.startsWith(date));

        let totalNetPay = 0;
        let table = `
        <div id="pdfContent">
        <h4 class="text-center mb-3">Payroll History for ${date}</h4>
        <table class="table table-striped table-bordered">
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
                    <th>Net Pay (₱)</th>
                </tr>
            </thead>
            <tbody>`;

        if (filtered.length > 0) {
            filtered.forEach(r => {
                totalNetPay += parseFloat(r.net_pay);
                table += `
                    <tr>
                        <td>${r.employee_id}</td>
                        <td>${r.full_name}</td>
                        <td>${parseFloat(r.regular_pay).toFixed(2)}</td>
                        <td>${parseFloat(r.overtime_pay).toFixed(2)}</td>
                        <td>${parseFloat(r.holiday_pay).toFixed(2)}</td>
                        <td>${parseFloat(r.special_pay).toFixed(2)}</td>
                        <td>${parseFloat(r.leave_pay).toFixed(2)}</td>
                        <td>${parseFloat(r.total_deductions).toFixed(2)}</td>
                        <td>${parseFloat(r.cash_advance).toFixed(2)}</td>
                        <td><strong>${parseFloat(r.net_pay).toFixed(2)}</strong></td>
                    </tr>`;
            });

            // ✅ Add total row
            table += `
                <tr class="total-row">
                    <td colspan="9">TOTAL PAYROLL</td>
                    <td>₱ ${totalNetPay.toFixed(2)}</td>
                </tr>`;
        } else {
            table += `<tr><td colspan="10" class="text-center text-muted">No payroll history found for this date.</td></tr>`;
        }

        table += `</tbody></table></div>`;
        document.getElementById('modalBody').innerHTML = table;

        new bootstrap.Modal(document.getElementById('historyModal')).show();
    });
});

function printModal() {
    const modalContent = document.getElementById('pdfContent').innerHTML;
    const win = window.open('', '', 'width=1200,height=700');
    win.document.write(`
        <html>
        <head>
            <title>Payroll History - ${currentDate}</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
            <div class="container mt-4">
                <h3 class="text-center mb-4">Payroll History Report - ${currentDate}</h3>
                ${modalContent}
            </div>
            <script>window.print();<\/script>
        </body>
        </html>
    `);
    win.document.close();
}

async function exportPDF() {
    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF('l', 'pt', 'a4');
    const content = document.getElementById('pdfContent');

    if (!content) {
        alert('No data to export!');
        return;
    }

    await html2canvas(content, { scale: 1.2 }).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const imgWidth = 800;
        const imgHeight = canvas.height * imgWidth / canvas.width;
        pdf.addImage(imgData, 'PNG', 20, 20, imgWidth, imgHeight);
    });

    pdf.save(`Payroll_History_${currentDate}.pdf`);
}
</script>

</body>
</html>
