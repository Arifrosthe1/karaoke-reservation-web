<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: ../login.php");
    exit();
}

// Check if there's a completed reservation
if (!isset($_SESSION['completed_reservation'])) {
    header("Location: make_reservation.php");
    exit();
}

$completed_reservation = $_SESSION['completed_reservation'];
$bookingReference = '#CK' . str_pad($completed_reservation['reservationID'], 5, '0', STR_PAD_LEFT);

// Function to format room type display
function formatRoomType($roomType) {
    // The roomType should be the package name like "Standard", "Deluxe", "VIP"
    return htmlspecialchars($roomType) . " Room";
}

// Clear the completed reservation from session after displaying
// (We'll keep it for this page load, but could clear it after)
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
    <link rel="shortcut icon" href="../assets/images/cronykaraoke.webp" type="image/x-icon">
    <meta name="description" content="Crony Karaoke - Payment Complete">
    <title>Payment Complete - Crony Karaoke</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/theme/css/style.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: #edefeb;
            font-family: 'Inter Tight', sans-serif;
        }
        .content-wrapper {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 4rem 0;
        }
        .success-card {
            background: white;
            border-radius: 10px;
            padding: 3rem;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 600px;
            width: 100%;
        }
        .success-icon {
            background: #e0f7ea;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
        }
        .success-icon .check {
            font-size: 3rem;
            color: #10b981;
        }
        .success-heading {
            color: #10b981;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .success-subtext {
            color: #666;
            margin-bottom: 2rem;
        }
        .page-header {
            background: linear-gradient(45deg, #493d9e, #8571ff);
            color: white;
            padding: 60px 0;
            margin-bottom: 0;
        }
        .btn-primary {
            background: #493d9e;
            border-color: #493d9e;
        }
        .btn-primary:hover {
            background: #3d3486;
            border-color: #3d3486;
        }
        .booking-details {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin: 2rem 0;
            text-align: left;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in {
            animation: fadeInUp 0.5s ease-out forwards;
        }
    </style>
</head>
<body>

<section class="page-header" style="padding-top: 50px; padding-bottom: 40px; margin-bottom: 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h1 class="display-3 fw-bold">Booking Confirmed!</h1>
                <p class="lead">Your karaoke session has been successfully reserved</p>
            </div>
        </div>
    </div>
</section>

<div class="content-wrapper" style="justify-content: center; align-items: center; display: flex; min-height: 70vh;">
  <div class="container d-flex justify-content-center align-items-center">
    <div class="row justify-content-center w-100">
      <div class="col-md-8">
        <div class="success-card animate-fade-in mx-auto">
          <div class="success-icon">
            <span class="check">✓</span>
          </div>
          <h3 class="success-heading">Payment Successful!</h3>
          <p class="success-subtext">Your karaoke room has been booked. We're looking forward to welcoming you!</p>
          
          <div class="booking-details">
            <div class="row">
              <div class="col-md-6">
                <h5 class="mb-3">Booking Details</h5>
                <p><strong>Booking Reference:</strong></p>
                <h4 style="color: #493d9e; margin-bottom: 1rem;"><?php echo $bookingReference; ?></h4>
                <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($completed_reservation['reservationDate'])); ?></p>
                <p><strong>Time:</strong> <?php echo date('g:i A', strtotime($completed_reservation['startTime'])); ?> - <?php echo date('g:i A', strtotime($completed_reservation['endTime'])); ?></p>
              </div>
              <div class="col-md-6">
                <h5 class="mb-3">Payment Summary</h5>
                <p><strong>Room Type:</strong> <?php echo formatRoomType($completed_reservation['roomType']); ?></p>
                <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($completed_reservation['paymentMethod']); ?></p>
                <p><strong>Amount Paid:</strong> RM <?php echo number_format($completed_reservation['totalPrice'], 2); ?></p>
                <p><span class="badge bg-success">PAID</span></p>
              </div>
            </div>
          </div>
          
          <div class="mb-4 text-center">
            <div class="alert alert-info">
              <p class="mb-1"><strong>📧 Confirmation Email Sent</strong></p>
              <p class="mb-0">A confirmation email has been sent to your registered email address.</p>
            </div>
            <p class="mb-0"><small class="text-muted">Please present your booking reference upon arrival at our venue.</small></p>
          </div>
          
          <div class="d-grid gap-2">
            <a href="booking.php" class="btn btn-primary btn-lg">View My Bookings</a>
            <a href="make_reservation.php" class="btn btn-outline-primary">Book Another Room</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<footer class="py-4" style="background: #333; color: white; text-align: center;">
    <div class="container">
        <p class="mb-0">© Copyright 2025 Crony Karaoke - All Rights Reserved</p>
    </div>
</footer>

<script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
    // Clear the completed reservation from session after 30 seconds
    // This prevents users from refreshing and seeing the same confirmation
    setTimeout(function() {
        fetch('clear_session.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({action: 'clear_completed_reservation'})
        });
    }, 30000);
</script>

</body>
</html>

<?php
// Clear the completed reservation from session after displaying
// Comment out if you want to keep it for debugging
// unset($_SESSION['completed_reservation']);
?></document_content>
</invoke>