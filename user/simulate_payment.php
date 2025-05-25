<?php
session_start();
require_once '../dbconfig.php';

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: ../login.php");
    exit();
}

// Check if there's a pending reservation
if (!isset($_SESSION['pending_reservation'])) {
    header("Location: make_reservation.php");
    exit();
}

$pending_reservation = $_SESSION['pending_reservation'];

// Handle payment processing
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_method = $_POST['payment_method'] ?? '';
    
    if (empty($payment_method)) {
        $error_message = "Please select a payment method.";
    } else {
        // Start transaction
        mysqli_begin_transaction($conn);
        
        try {
            // Insert reservation into database
            $insertReservationQuery = "INSERT INTO reservations (userID, roomID, reservationDate, startTime, endTime, totalPrice, status, addInfo) 
                                      VALUES (?, ?, ?, ?, ?, ?, 'confirmed', ?)";
            
            $stmt = mysqli_prepare($conn, $insertReservationQuery);
            mysqli_stmt_bind_param($stmt, "iiissds", 
                $pending_reservation['userID'],
                $pending_reservation['roomID'],
                $pending_reservation['reservationDate'],
                $pending_reservation['startTime'],
                $pending_reservation['endTime'],
                $pending_reservation['totalPrice'],
                $pending_reservation['specialRequests']
            );
            
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Failed to create reservation");
            }
            
            // Get the reservation ID
            $reservationID = mysqli_insert_id($conn);
            
            // Insert payment record
            $insertPaymentQuery = "INSERT INTO payments (reservationID, paymentMethod, amountPaid, paymentStatus) 
                                  VALUES (?, ?, ?, 'paid')";
            
            $paymentStmt = mysqli_prepare($conn, $insertPaymentQuery);
            mysqli_stmt_bind_param($paymentStmt, "isd", 
                $reservationID,
                $payment_method,
                $pending_reservation['totalPrice']
            );
            
            if (!mysqli_stmt_execute($paymentStmt)) {
                throw new Exception("Failed to record payment");
            }
            
            // Commit transaction
            mysqli_commit($conn);
            
            // Store reservation details for confirmation page
            $_SESSION['completed_reservation'] = [
                'reservationID' => $reservationID,
                'roomType' => $pending_reservation['roomType'],
                'reservationDate' => $pending_reservation['reservationDate'],
                'startTime' => $pending_reservation['startTime'],
                'endTime' => $pending_reservation['endTime'],
                'totalPrice' => $pending_reservation['totalPrice'],
                'paymentMethod' => $payment_method
            ];
            
            // Clear pending reservation
            unset($_SESSION['pending_reservation']);
            
            // Redirect to processing page
            header("Location: payment_processing.php");
            exit();
            
        } catch (Exception $e) {
            // Rollback transaction
            mysqli_rollback($conn);
            $error_message = "Payment processing failed. Please try again.";
        }
    }
}

// Get package details based on the room type (package name)
$packageQuery = "SELECT * FROM packages WHERE packageName = ?";
$packageStmt = mysqli_prepare($conn, $packageQuery);
mysqli_stmt_bind_param($packageStmt, "s", $pending_reservation['roomType']);
mysqli_stmt_execute($packageStmt);
$packageResult = mysqli_stmt_get_result($packageStmt);
$package = mysqli_fetch_assoc($packageResult);

// If package is not found, set default values to prevent errors
if (!$package) {
    $package = [
        'packageName' => $pending_reservation['roomType'] ?? 'Unknown',
        'pricePerHour' => 0
    ];
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
    <link rel="shortcut icon" href="../assets/images/cronykaraoke.webp" type="image/x-icon">
    <title>Payment - Crony Karaoke</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/theme/css/style.css">
    <style>
        body {
            background: #edefeb;
            font-family: 'Inter Tight', sans-serif;
        }
        .payment-container {
            max-width: 800px;
            margin: 2rem auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .payment-header {
            background: linear-gradient(45deg, #493d9e, #8571ff);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .booking-summary {
            background: #f8f9fa;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-radius: 8px;
        }
        .payment-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .payment-method {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .payment-method:hover {
            border-color: #493d9e;
            background: #f8f9ff;
        }
        .payment-method.selected {
            border-color: #493d9e;
            background: #f8f9ff;
        }
        .payment-method input[type="radio"] {
            display: none;
        }
        .payment-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .btn-primary {
            background: #493d9e;
            border-color: #493d9e;
            padding: 12px 30px;
        }
        .btn-primary:hover {
            background: #3d3486;
            border-color: #3d3486;
        }
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

<div class="payment-container">
    <div class="payment-header">
        <h2>Complete Your Payment</h2>
        <p>Secure your karaoke room reservation</p>
    </div>
    
    <div class="p-4">
        <?php if (isset($error_message)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        
        <!-- Booking Summary -->
        <div class="booking-summary">
            <h4 class="mb-3">Booking Summary</h4>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Room Type:</strong> <?php echo htmlspecialchars($package['packageName']); ?></p>
                    <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($pending_reservation['reservationDate'])); ?></p>
                    <p><strong>Time:</strong> <?php echo date('g:i A', strtotime($pending_reservation['startTime'])); ?> - <?php echo date('g:i A', strtotime($pending_reservation['endTime'])); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Duration:</strong> <?php echo $pending_reservation['duration']; ?> hour(s)</p>
                    <p><strong>Rate:</strong> RM <?php echo number_format($package['pricePerHour'], 2); ?>/hour</p>
                    <hr>
                    <h5><strong>Total Amount: RM <?php echo number_format($pending_reservation['totalPrice'], 2); ?></strong></h5>
                </div>
            </div>
            <?php if (!empty($pending_reservation['specialRequests'])): ?>
                <p><strong>Special Requests:</strong> <?php echo htmlspecialchars($pending_reservation['specialRequests']); ?></p>
            <?php endif; ?>
        </div>
        
        <!-- Payment Form -->
        <form method="POST" id="paymentForm">
            <h4 class="mb-3">Select Payment Method</h4>
            
            <div class="payment-methods">
                <label class="payment-method" for="credit_card">
                    <input type="radio" name="payment_method" value="Credit Card" id="credit_card">
                    <div class="payment-icon">💳</div>
                    <div><strong>Credit Card</strong></div>
                    <small class="text-muted">Visa, MasterCard, Amex</small>
                </label>
                
                <label class="payment-method" for="debit_card">
                    <input type="radio" name="payment_method" value="Debit Card" id="debit_card">
                    <div class="payment-icon">💳</div>
                    <div><strong>Debit Card</strong></div>
                    <small class="text-muted">Bank debit cards</small>
                </label>
                
                <label class="payment-method" for="online_banking">
                    <input type="radio" name="payment_method" value="Online Banking" id="online_banking">
                    <div class="payment-icon">🏦</div>
                    <div><strong>Online Banking</strong></div>
                    <small class="text-muted">FPX, Internet Banking</small>
                </label>
                
                <label class="payment-method" for="ewallet">
                    <input type="radio" name="payment_method" value="E-Wallet" id="ewallet">
                    <div class="payment-icon">📱</div>
                    <div><strong>E-Wallet</strong></div>
                    <small class="text-muted">Touch 'n Go, GrabPay</small>
                </label>
            </div>
            
            <div class="d-flex justify-content-between align-items-center">
                <a href="make_reservation.php" class="btn btn-outline-secondary">
                    ← Back to Booking
                </a>
                <button type="submit" class="btn btn-primary btn-lg">
                    Pay RM <?php echo number_format($pending_reservation['totalPrice'], 2); ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
    // Handle payment method selection
    document.querySelectorAll('.payment-method').forEach(method => {
        method.addEventListener('click', function() {
            // Remove selected class from all methods
            document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('selected'));
            // Add selected class to clicked method
            this.classList.add('selected');
        });
    });
    
    // Form validation
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
        if (!selectedPayment) {
            e.preventDefault();
            alert('Please select a payment method.');
        }
    });
</script>

</body>
</html>