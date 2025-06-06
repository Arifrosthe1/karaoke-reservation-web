<?php
include '../dbconfig.php';
session_start();

// Check admin authentication
if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$adminName = $_SESSION['fullName'] ?? 'Admin';

// Handle Excel export
if (isset($_GET['export']) && isset($_GET['type'])) {
    $exportType = $_GET['type'];
    $filename = '';
    $data = [];
    $headers = [];
    
    if ($exportType === 'sales') {
        $filename = 'Sales_Report_' . date('Y-m-d') . '.csv';
        $headers = ['Payment ID', 'Reservation ID', 'Customer Name', 'Room', 'Payment Method', 'Amount (RM)', 'Payment Date', 'Status'];
        
        $query = "SELECT p.paymentID, p.reservationID, u.fullName, r.roomName, p.paymentMethod, 
                         p.amountPaid, p.paymentDate, p.paymentStatus
                  FROM payments p
                  JOIN reservations res ON p.reservationID = res.reservationID
                  JOIN users u ON res.userID = u.userID
                  JOIN rooms r ON res.roomID = r.roomID
                  ORDER BY p.paymentDate DESC";
        
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                $row['paymentID'],
                $row['reservationID'],
                $row['fullName'],
                $row['roomName'],
                $row['paymentMethod'],
                number_format($row['amountPaid'], 2),
                date('Y-m-d H:i:s', strtotime($row['paymentDate'])),
                ucfirst($row['paymentStatus'])
            ];
        }
    }
    elseif ($exportType === 'reservations') {
        $filename = 'Reservations_Report_' . date('Y-m-d') . '.csv';
        $headers = ['Reservation ID', 'Customer Name', 'Phone', 'Room', 'Package', 'Date', 'Start Time', 'End Time', 'Total Price (RM)', 'Status', 'Created At'];
        
        $query = "SELECT res.reservationID, u.fullName, u.phone, r.roomName, pkg.packageName,
                         res.reservationDate, res.startTime, res.endTime, res.totalPrice, res.status, res.createdAt
                  FROM reservations res
                  JOIN users u ON res.userID = u.userID
                  JOIN rooms r ON res.roomID = r.roomID
                  JOIN packages pkg ON r.packageID = pkg.packageID
                  ORDER BY res.createdAt DESC";
        
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                $row['reservationID'],
                $row['fullName'],
                $row['phone'],
                $row['roomName'],
                $row['packageName'],
                $row['reservationDate'],
                $row['startTime'],
                $row['endTime'],
                number_format($row['totalPrice'], 2),
                ucfirst($row['status']),
                date('Y-m-d H:i:s', strtotime($row['createdAt']))
            ];
        }
    }
    elseif ($exportType === 'payments') {
        $filename = 'Payment_Methods_Report_' . date('Y-m-d') . '.csv';
        $headers = ['Payment Method', 'Total Transactions', 'Total Amount (RM)', 'Percentage'];
        
        $query = "SELECT paymentMethod, COUNT(*) as totalTransactions, SUM(amountPaid) as totalAmount
                  FROM payments 
                  WHERE paymentStatus = 'paid'
                  GROUP BY paymentMethod
                  ORDER BY totalAmount DESC";
        
        $result = $conn->query($query);
        $grandTotal = 0;
        $tempData = [];
        
        // Calculate grand total first
        while ($row = $result->fetch_assoc()) {
            $grandTotal += $row['totalAmount'];
            $tempData[] = $row;
        }
        
        // Add percentage calculation
        foreach ($tempData as $row) {
            $percentage = ($grandTotal > 0) ? ($row['totalAmount'] / $grandTotal) * 100 : 0;
            $data[] = [
                $row['paymentMethod'],
                $row['totalTransactions'],
                number_format($row['totalAmount'], 2),
                number_format($percentage, 1) . '%'
            ];
        }
    }
    
    // Output CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, $headers);
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit();
}

// Fetch report data for display
function getSalesReport($conn) {
    $query = "SELECT p.paymentID, p.reservationID, u.fullName, r.roomName, p.paymentMethod, 
                     p.amountPaid, p.paymentDate, p.paymentStatus
              FROM payments p
              JOIN reservations res ON p.reservationID = res.reservationID
              JOIN users u ON res.userID = u.userID
              JOIN rooms r ON res.roomID = r.roomID
              ORDER BY p.paymentDate DESC
              LIMIT 10";
    return $conn->query($query);
}

function getReservationsReport($conn) {
    $query = "SELECT res.reservationID, u.fullName, u.phone, r.roomName, pkg.packageName,
                     res.reservationDate, res.startTime, res.endTime, res.totalPrice, res.status
              FROM reservations res
              JOIN users u ON res.userID = u.userID
              JOIN rooms r ON res.roomID = r.roomID
              JOIN packages pkg ON r.packageID = pkg.packageID
              ORDER BY res.createdAt DESC
              LIMIT 10";
    return $conn->query($query);
}

function getPaymentMethodsReport($conn) {
    $query = "SELECT paymentMethod, COUNT(*) as totalTransactions, SUM(amountPaid) as totalAmount
              FROM payments 
              WHERE paymentStatus = 'paid'
              GROUP BY paymentMethod
              ORDER BY totalAmount DESC";
    return $conn->query($query);
}

// Get summary statistics
function getSummaryStats($conn) {
    $stats = [];
    
    // Total revenue
    $result = $conn->query("SELECT SUM(amountPaid) as totalRevenue FROM payments WHERE paymentStatus = 'paid'");
    $stats['totalRevenue'] = $result->fetch_assoc()['totalRevenue'] ?? 0;
    
    // This month revenue
    $result = $conn->query("SELECT SUM(amountPaid) as monthRevenue FROM payments WHERE paymentStatus = 'paid' AND MONTH(paymentDate) = MONTH(CURDATE()) AND YEAR(paymentDate) = YEAR(CURDATE())");
    $stats['monthRevenue'] = $result->fetch_assoc()['monthRevenue'] ?? 0;
    
    // Total bookings
    $result = $conn->query("SELECT COUNT(*) as totalBookings FROM reservations");
    $stats['totalBookings'] = $result->fetch_assoc()['totalBookings'] ?? 0;
    
    // Confirmed bookings
    $result = $conn->query("SELECT COUNT(*) as confirmedBookings FROM reservations WHERE status = 'confirmed'");
    $stats['confirmedBookings'] = $result->fetch_assoc()['confirmedBookings'] ?? 0;
    
    return $stats;
}

$salesReport = getSalesReport($conn);
$reservationsReport = getReservationsReport($conn);
$paymentMethodsReport = getPaymentMethodsReport($conn);
$summaryStats = getSummaryStats($conn);

// Calculate total for payment methods percentage
$totalPaymentAmount = 0;
$paymentMethodsData = [];
if ($paymentMethodsReport) {
    while ($row = $paymentMethodsReport->fetch_assoc()) {
        $totalPaymentAmount += $row['totalAmount'];
        $paymentMethodsData[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Reports - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #e5e7eb;
        }
        .report-card {
            background: #ffffff;
            color: #374151;
            border-radius: 0.75rem;
            border: 1px solid #000;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }
        .header-bg {
            background-color: #ffffff;
            padding: 1.5rem 2rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            border: 1px solid #000;
        }
        .export-btn {
            background-color: #059669;
            color: #ffffff;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .export-btn:hover {
            background-color: #047857;
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
        }
        .back-btn:hover {
            background-color: #4b5563;
        }
        .stats-card {
            background-color: #f9fafb;
            border-radius: 0.5rem;
            border: 1px solid #000;
            padding: 1.5rem;
            text-align: center;
        }
        .stats-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        .stats-label {
            font-size: 0.875rem;
            color: #6b7280;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background-color: #f9fafb;
            font-weight: 600;
            color: #374151;
        }
        tr:hover {
            background-color: #f9fafb;
        }
        .status-confirmed { color: #059669; font-weight: 600; }
        .status-pending { color: #d97706; font-weight: 600; }
        .status-cancelled { color: #dc2626; font-weight: 600; }
        .status-paid { color: #059669; font-weight: 600; }
        .status-refunded { color: #dc2626; font-weight: 600; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center p-4">
    <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-7xl mt-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8 header-bg">
            <h1 class="text-3xl font-bold text-gray-800">Business Reports</h1>
            <a href="admin_dashboard.php" class="back-btn">
                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
            </a>
        </div>

        <!-- Summary Statistics -->
        <div class="report-card p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Business Overview</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="stats-card">
                    <div class="stats-value">RM <?php echo number_format($summaryStats['totalRevenue'], 2); ?></div>
                    <div class="stats-label">Total Revenue</div>
                </div>
                <div class="stats-card">
                    <div class="stats-value">RM <?php echo number_format($summaryStats['monthRevenue'], 2); ?></div>
                    <div class="stats-label">This Month Revenue</div>
                </div>
                <div class="stats-card">
                    <div class="stats-value"><?php echo $summaryStats['totalBookings']; ?></div>
                    <div class="stats-label">Total Bookings</div>
                </div>
                <div class="stats-card">
                    <div class="stats-value"><?php echo $summaryStats['confirmedBookings']; ?></div>
                    <div class="stats-label">Confirmed Bookings</div>
                </div>
            </div>
        </div>

        <!-- Sales Report -->
        <div class="report-card p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Sales Report</h2>
                <a href="?export=true&type=sales" class="export-btn">
                    <i class="fas fa-download mr-2"></i>Export to Excel
                </a>
            </div>
            <div class="overflow-x-auto">
                <table>
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Customer</th>
                            <th>Room</th>
                            <th>Payment Method</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($salesReport && $salesReport->num_rows > 0): ?>
                            <?php while ($row = $salesReport->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $row['paymentID']; ?></td>
                                <td><?php echo htmlspecialchars($row['fullName']); ?></td>
                                <td><?php echo htmlspecialchars($row['roomName']); ?></td>
                                <td><?php echo htmlspecialchars($row['paymentMethod']); ?></td>
                                <td>RM <?php echo number_format($row['amountPaid'], 2); ?></td>
                                <td><?php echo date('Y-m-d H:i', strtotime($row['paymentDate'])); ?></td>
                                <td class="status-<?php echo $row['paymentStatus']; ?>">
                                    <?php echo ucfirst($row['paymentStatus']); ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center text-gray-500">No sales data available</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <p class="text-sm text-gray-600 mt-4">Showing latest 10 transactions. Export for complete data.</p>
        </div>

        <!-- Reservations Report -->
        <div class="report-card p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Reservations Report</h2>
                <a href="?export=true&type=reservations" class="export-btn">
                    <i class="fas fa-download mr-2"></i>Export to Excel
                </a>
            </div>
            <div class="overflow-x-auto">
                <table>
                    <thead>
                        <tr>
                            <th>Reservation ID</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Room</th>
                            <th>Package</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Total Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($reservationsReport && $reservationsReport->num_rows > 0): ?>
                            <?php while ($row = $reservationsReport->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $row['reservationID']; ?></td>
                                <td><?php echo htmlspecialchars($row['fullName']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td><?php echo htmlspecialchars($row['roomName']); ?></td>
                                <td><?php echo htmlspecialchars($row['packageName']); ?></td>
                                <td><?php echo $row['reservationDate']; ?></td>
                                <td><?php echo substr($row['startTime'], 0, 5) . ' - ' . substr($row['endTime'], 0, 5); ?></td>
                                <td>RM <?php echo number_format($row['totalPrice'], 2); ?></td>
                                <td class="status-<?php echo $row['status']; ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="9" class="text-center text-gray-500">No reservation data available</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <p class="text-sm text-gray-600 mt-4">Showing latest 10 reservations. Export for complete data.</p>
        </div>

        <!-- Payment Methods Report -->
        <div class="report-card p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Payment Methods Analysis</h2>
                <a href="?export=true&type=payments" class="export-btn">
                    <i class="fas fa-download mr-2"></i>Export to Excel
                </a>
            </div>
            <div class="overflow-x-auto">
                <table>
                    <thead>
                        <tr>
                            <th>Payment Method</th>
                            <th>Total Transactions</th>
                            <th>Total Amount</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($paymentMethodsData)): ?>
                            <?php foreach ($paymentMethodsData as $row): ?>
                            <?php $percentage = ($totalPaymentAmount > 0) ? ($row['totalAmount'] / $totalPaymentAmount) * 100 : 0; ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['paymentMethod']); ?></td>
                                <td><?php echo $row['totalTransactions']; ?></td>
                                <td>RM <?php echo number_format($row['totalAmount'], 2); ?></td>
                                <td><?php echo number_format($percentage, 1); ?>%</td>
                            </tr>
                            <?php endforeach; ?>
                            <tr style="background-color: #f3f4f6; font-weight: 600;">
                                <td>TOTAL</td>
                                <td><?php echo array_sum(array_column($paymentMethodsData, 'totalTransactions')); ?></td>
                                <td>RM <?php echo number_format($totalPaymentAmount, 2); ?></td>
                                <td>100.0%</td>
                            </tr>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center text-gray-500">No payment data available</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-gray-600 text-sm mt-8">
            <p>Report generated on <?php echo date('Y-m-d H:i:s'); ?> by <?php echo htmlspecialchars($adminName); ?></p>
            <p>© <?php echo date('Y'); ?> Karaoke Management System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>