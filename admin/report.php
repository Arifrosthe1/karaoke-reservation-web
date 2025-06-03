<?php
include '../dbconfig.php';
session_start();

// Check if user is admin
if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$adminName = $_SESSION['fullName'] ?? 'Admin';

// Initialize variables
$salesReport = [];
$reservationReport = [];
$paymentReport = [];
$reportType = $_GET['report'] ?? '';

// Fetch reports based on type
if ($conn) {
    switch ($reportType) {
        case 'sales':
            // Sales Report - Monthly sales summary
            $stmt = $conn->prepare("
                SELECT 
                    DATE_FORMAT(paymentDate, '%Y-%m') as month,
                    COUNT(*) as total_transactions,
                    SUM(amountPaid) as total_amount
                FROM payments 
                WHERE paymentStatus = 'paid'
                GROUP BY DATE_FORMAT(paymentDate, '%Y-%m')
                ORDER BY month DESC
                LIMIT 12
            ");
            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $salesReport[] = $row;
                }
                $stmt->close();
            }
            break;

        case 'reservations':
            // Reservation Report - Recent reservations with status
            $stmt = $conn->prepare("
                SELECT 
                    r.reservationID,
                    u.fullName as customer_name,
                    rt.roomType,
                    r.reservationDate,
                    r.timeSlot,
                    r.status,
                    r.totalAmount
                FROM reservations r
                JOIN users u ON r.userID = u.userID
                JOIN rooms rt ON r.roomID = rt.roomID
                ORDER BY r.reservationDate DESC
                LIMIT 50
            ");
            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $reservationReport[] = $row;
                }
                $stmt->close();
            }
            break;

        case 'payments':
            // Payment Report - Recent payment transactions
            $stmt = $conn->prepare("
                SELECT 
                    p.paymentID,
                    u.fullName as customer_name,
                    p.amountPaid,
                    p.paymentMethod,
                    p.paymentStatus,
                    p.paymentDate,
                    r.reservationID
                FROM payments p
                JOIN reservations r ON p.reservationID = r.reservationID
                JOIN users u ON r.userID = u.userID
                ORDER BY p.paymentDate DESC
                LIMIT 50
            ");
            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $paymentReport[] = $row;
                }
                $stmt->close();
            }
            break;
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Reports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #e5e7eb;
        }
        .dashboard-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: #ffffff;
            color: #374151;
            border-radius: 0.75rem;
            border: 1px solid #000;
            padding: 1.5rem;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .dashboard-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #4f46e5;
        }
        .btn-card {
            background-color: #4f46e5;
            color: #ffffff;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn-card:hover {
            background-color: #4338ca;
        }
        .header-bg {
            background-color: #ffffff;
            padding: 1.5rem 2rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            border: 1px solid #000;
        }
        .logout-btn {
            background-color: #ef4444;
            color: #ffffff;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }
        .logout-btn:hover {
            background-color: #dc2626;
        }
        .report-table {
            background-color: #ffffff;
            border-radius: 0.75rem;
            border: 1px solid #000;
            overflow: hidden;
        }
        .table-header {
            background-color: #f9fafb;
            border-bottom: 1px solid #000;
        }
        .table-row {
            border-bottom: 1px solid #e5e7eb;
        }
        .table-row:last-child {
            border-bottom: none;
        }
        .back-btn {
            background-color: #6b7280;
            color: #ffffff;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 1rem;
        }
        .back-btn:hover {
            background-color: #4b5563;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center p-4">
    <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-6xl mt-8">
        <div class="flex justify-between items-center mb-8 header-bg">
            <h1 class="text-3xl font-bold text-gray-800">Reports Dashboard</h1>
            <div class="flex gap-4">
                <a href="admin_dashboard.php" class="back-btn">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
                <a href="../logout.php" class="logout-btn">Logout</a>
            </div>
        </div>

        <?php if (empty($reportType)): ?>
        <!-- Report Selection -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="dashboard-card text-center">
                <div class="dashboard-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h2 class="text-xl font-bold mb-4">Sales Report</h2>
                <p class="text-gray-600 mb-6">View monthly sales summary and revenue trends</p>
                <a href="report.php?report=sales" class="btn-card">View Sales Report</a>
            </div>

            <div class="dashboard-card text-center">
                <div class="dashboard-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h2 class="text-xl font-bold mb-4">Reservation Report</h2>
                <p class="text-gray-600 mb-6">View recent reservations and booking status</p>
                <a href="report.php?report=reservations" class="btn-card">View Reservation Report</a>
            </div>

            <div class="dashboard-card text-center">
                <div class="dashboard-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <h2 class="text-xl font-bold mb-4">Payment Report</h2>
                <p class="text-gray-600 mb-6">View payment transactions and status</p>
                <a href="report.php?report=payments" class="btn-card">View Payment Report</a>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($reportType === 'sales'): ?>
        <!-- Sales Report -->
        <div class="mb-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Sales Report - Monthly Summary</h2>
            <a href="report.php" class="back-btn">
                <i class="fas fa-arrow-left mr-2"></i>Back to Reports
            </a>
        </div>
        
        <div class="report-table">
            <table class="w-full">
                <thead class="table-header">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Month</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Total Transactions</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Total Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($salesReport)): ?>
                    <tr class="table-row">
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">No sales data found</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($salesReport as $row): ?>
                    <tr class="table-row">
                        <td class="px-6 py-4"><?php echo htmlspecialchars($row['month']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($row['total_transactions']); ?></td>
                        <td class="px-6 py-4">RM <?php echo htmlspecialchars(number_format($row['total_amount'], 2)); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <?php if ($reportType === 'reservations'): ?>
        <!-- Reservation Report -->
        <div class="mb-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Reservation Report - Recent Bookings</h2>
            <a href="report.php" class="back-btn">
                <i class="fas fa-arrow-left mr-2"></i>Back to Reports
            </a>
        </div>
        
        <div class="report-table">
            <table class="w-full">
                <thead class="table-header">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Reservation ID</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Customer</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Room Type</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Date</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Time Slot</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($reservationReport)): ?>
                    <tr class="table-row">
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No reservation data found</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($reservationReport as $row): ?>
                    <tr class="table-row">
                        <td class="px-6 py-4"><?php echo htmlspecialchars($row['reservationID']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($row['customer_name']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($row['roomType']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($row['reservationDate']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($row['timeSlot']); ?></td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded <?php 
                                echo $row['status'] === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                    ($row['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'); 
                            ?>">
                                <?php echo htmlspecialchars(ucfirst($row['status'])); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">RM <?php echo htmlspecialchars(number_format($row['totalAmount'], 2)); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <?php if ($reportType === 'payments'): ?>
        <!-- Payment Report -->
        <div class="mb-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Payment Report - Recent Transactions</h2>
            <a href="report.php" class="back-btn">
                <i class="fas fa-arrow-left mr-2"></i>Back to Reports
            </a>
        </div>
        
        <div class="report-table">
            <table class="w-full">
                <thead class="table-header">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Payment ID</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Customer</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Reservation ID</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Amount</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Method</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($paymentReport)): ?>
                    <tr class="table-row">
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No payment data found</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($paymentReport as $row): ?>
                    <tr class="table-row">
                        <td class="px-6 py-4"><?php echo htmlspecialchars($row['paymentID']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($row['customer_name']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($row['reservationID']); ?></td>
                        <td class="px-6 py-4">RM <?php echo htmlspecialchars(number_format($row['amountPaid'], 2)); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars(ucfirst($row['paymentMethod'])); ?></td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded <?php 
                                echo $row['paymentStatus'] === 'paid' ? 'bg-green-100 text-green-800' : 
                                    ($row['paymentStatus'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'); 
                            ?>">
                                <?php echo htmlspecialchars(ucfirst($row['paymentStatus'])); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($row['paymentDate']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>