<?php
include '../dbconfig.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: ../login.php");
    exit();
}

$username = $_SESSION['fullName'];
$userID = $_SESSION['userID'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
  <meta name="description" content="Crony Karaoke - User Dashboard">
  <title>Dashboard - Crony Karaoke</title>
  
  <!-- Favicon -->
  <link rel="shortcut icon" href="../assets/images/cronykaraoke.webp" type="image/x-icon">
  
  <!-- External Stylesheets -->
  <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/animatecss/animate.css">
  <link rel="stylesheet" href="../assets/theme/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  
  <!-- Google Fonts -->
  <link rel="preload" href="https://fonts.googleapis.com/css?family=Inter+Tight:100,200,300,400,500,600,700,800,900,100i,200i,300i,400i,500i,600i,700i,800i,900i&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter+Tight:100,200,300,400,500,600,700,800,900,100i,200i,300i,400i,500i,600i,700i,800i,900i&display=swap"></noscript>

  <link rel="stylesheet" href="style.css">
</head>

<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="user_home.php">
        <img src="../assets/images/cronykaraoke-1.webp" alt="Crony Karaoke Logo">
        <span>Crony Karaoke</span>
      </a>
      
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto me-auto">
          <li class="nav-item">
            <a class="nav-link" href="make_reservation.php">Book Room</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#promotions">Promotions</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="mailto:helper@cronykaraoke.com">Support</a>
          </li>
        </ul>
        <a class="btn-logout" href="../logout.php">
          <i class="fas fa-sign-out-alt me-1"></i>
          Logout
        </a>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <main class="main-content">
    <div class="container">
      <!-- Welcome Header -->
      <div class="welcome-header">
        <h1 class="welcome-title">
          Welcome back, <span class="username-highlight"><?php echo htmlspecialchars($username); ?></span>
        </h1>
        <p class="welcome-subtitle">Manage your bookings and explore our karaoke services</p>
      </div>

      <!-- Dashboard Cards -->
      <div class="dashboard-grid">
        <div class="dashboard-card">
          <div class="card-icon">
            <i class="fas fa-calendar-plus"></i>
          </div>
          <h3 class="card-title">Make Reservation</h3>
          <p class="card-description">Book a room, select date and time for your karaoke session</p>
          <a href="make_reservation.php" class="card-button">Book Now</a>
        </div>

        <div class="dashboard-card">
          <div class="card-icon">
            <i class="fas fa-user-edit"></i>
          </div>
          <h3 class="card-title">Update Profile</h3>
          <p class="card-description">Update your email, phone number, and password settings</p>
          <a href="profile_update.php" class="card-button">Update Profile</a>
        </div>

        <div class="dashboard-card">
          <div class="card-icon">
            <i class="fas fa-receipt"></i>
          </div>
          <h3 class="card-title">View Bookings</h3>
          <p class="card-description">See all your booking history and payment details</p>
          <a href="booking.php" class="card-button">View All</a>
        </div>

        <div class="dashboard-card">
          <div class="card-icon">
            <i class="fas fa-times-circle"></i>
          </div>
          <h3 class="card-title">Cancel Booking</h3>
          <p class="card-description">Cancel your existing reservations if needed</p>
          <a href="cancel_reservation.php" class="card-button">Manage Bookings</a>
        </div>
      </div>

      <!-- Upcoming Bookings -->
      <div class="bookings-section">
        <h2 class="section-title">Your Upcoming Bookings</h2>
        
        <div class="table-container">
          <?php
          $today = date('Y-m-d');
          $query = "SELECT r.reservationID, rm.roomName, r.reservationDate, r.startTime, r.endTime, r.status
                    FROM reservations r
                    JOIN rooms rm ON r.roomID = rm.roomID
                    WHERE r.userID = ? AND r.reservationDate >= ?
                    ORDER BY r.reservationDate ASC, r.startTime ASC";

          $stmt = $conn->prepare($query);
          $stmt->bind_param("is", $userID, $today);
          $stmt->execute();
          $result = $stmt->get_result();

          if ($result->num_rows > 0) {
          ?>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>Room</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Duration</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  while ($row = $result->fetch_assoc()) {
                    $reservationID = $row['reservationID'];
                    $roomName = $row['roomName'];
                    $date = date("d M Y", strtotime($row['reservationDate']));
                    $startTime = date("H:i", strtotime($row['startTime']));
                    $duration = round((strtotime($row['endTime']) - strtotime($row['startTime'])) / 3600, 1);
                    $status = strtolower($row['status']);
                    
                    $statusClass = 'status-pending';
                    if ($status == 'confirmed') $statusClass = 'status-confirmed';
                    if ($status == 'cancelled') $statusClass = 'status-cancelled';
                  ?>
                    <tr>
                      <td><?php echo htmlspecialchars($roomName); ?></td>
                      <td><?php echo $date; ?></td>
                      <td><?php echo $startTime; ?></td>
                      <td><?php echo $duration; ?>h</td>
                      <td>
                        <span class="status-badge <?php echo $statusClass; ?>">
                          <?php echo ucfirst($status); ?>
                        </span>
                      </td>
                      <td>
                        <?php if ($status != 'cancelled' && $row['reservationDate'] >= $today): ?>
                          <a href="cancel_reservation.php?id=<?php echo $reservationID; ?>" class="action-btn btn-cancel">
                            Cancel
                          </a>
                        <?php endif; ?>
                        <a href="view_invoice.php?id=<?php echo $reservationID; ?>" class="action-btn btn-detail">
                          Details
                        </a>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          <?php 
          } else { 
          ?>
            <div class="no-bookings">
              <i class="fas fa-calendar-times"></i>
              <h4>No Upcoming Bookings</h4>
              <p>You don't have any upcoming reservations. Ready to book your next karaoke session?</p>
              <a href="make_reservation.php" class="card-button">Book Now</a>
            </div>
          <?php 
          }
          $stmt->close();
          ?>
        </div>
      </div>

      <!-- Promotions Section -->
      <div id="promotions" class="promotions-section">
        <h2 class="section-title">Latest Promotions</h2>
        
        <div class="dashboard-grid">
          <div class="dashboard-card">
            <div class="card-icon">
              <i class="fas fa-percentage"></i>
            </div>
            <h3 class="card-title">Early Bird Discount</h3>
            <p class="card-description">Book 3 days in advance and get 10% off your total booking fee</p>
            <span class="status-badge status-confirmed">Active</span>
          </div>

          <div class="dashboard-card">
            <div class="card-icon">
              <i class="fas fa-star"></i>
            </div>
            <h3 class="card-title">VIP Room Special</h3>
            <p class="card-description">Experience our luxury VIP room with premium sound system</p>
            <span class="status-badge status-cancelled">Ended</span>
          </div>

          <div class="dashboard-card">
            <div class="card-icon">
              <i class="fas fa-users"></i>
            </div>
            <h3 class="card-title">Refer & Earn</h3>
            <p class="card-description">Refer friends and both get RM5 promo codes via email</p>
            <span class="status-badge status-confirmed">Ongoing</span>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="footer-info">
        <p class="mb-1">© 2025 Crony Karaoke — Sing. Laugh. Repeat.</p>
        <p class="mb-1">Level 2, Lot 18, Plaza Sentral, Kuala Lumpur, Malaysia</p>
        <p class="mb-0">
          <a href="mailto:kl_info@cronykaraoke.com">kl_info@cronykaraoke.com</a>
        </p>
        <p class="mb-0">Powered by CronyTech</p>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="../assets/web/assets/jquery/jquery.min.js"></script>
  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/smoothscroll/smooth-scroll.js"></script>
  <script src="../assets/theme/js/script.js"></script>
</body>
</html>