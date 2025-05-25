<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
  <link rel="shortcut icon" href="../assets/images/cronykaraoke.webp" type="image/x-icon">
  <meta name="description" content="Crony Karaoke - View Your Bookings">

  <title>View Bookings - Crony Karaoke</title>
  <link rel="stylesheet" href="../assets/web/assets/mobirise-icons2/mobirise2.css">
  <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap-grid.min.css">
  <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap-reboot.min.css">
  <link rel="stylesheet" href="../assets/animatecss/animate.css">
  <link rel="stylesheet" href="../assets/dropdown/css/style.css">
  <link rel="stylesheet" href="../assets/socicon/css/styles.css">
  <link rel="stylesheet" href="../assets/theme/css/style.css">
  <link rel="preload" href="https://fonts.googleapis.com/css?family=Inter+Tight:100,200,300,400,500,600,700,800,900,100i,200i,300i,400i,500i,600i,700i,800i,900i&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter+Tight:100,200,300,400,500,600,700,800,900,100i,200i,300i,400i,500i,600i,700i,800i,900i&display=swap"></noscript>
  <link rel="preload" as="style" href="../assets/mobirise/css/mbr-additional.css?v=f0jscm"><link rel="stylesheet" href="../assets/mobirise/css/mbr-additional.css?v=f0jscm" type="text/css">

  <style>
    .booking-card {
      background: white;
      padding: 25px;
      border-radius: 15px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      margin-bottom: 25px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .booking-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
    .booking-header {
      background: linear-gradient(45deg, #493d9e, #8571ff);
      color: white;
      padding: 15px 20px;
      border-radius: 10px;
      margin-bottom: 20px;
    }
    .booking-status {
      display: inline-block;
      padding: 5px 15px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
      text-transform: uppercase;
    }
    .status-confirmed {
      background-color: #28a745;
      color: white;
    }
    .status-pending {
      background-color: #ffc107;
      color: #212529;
    }
    .status-cancelled {
      background-color: #dc3545;
      color: white;
    }
    .booking-details {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 15px;
      margin-bottom: 20px;
    }
    .detail-item {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 8px;
      text-align: center;
    }
    .detail-label {
      font-size: 0.9rem;
      color: #6c757d;
      margin-bottom: 5px;
      font-weight: 500;
    }
    .detail-value {
      font-size: 1.1rem;
      font-weight: 600;
      color: #333;
    }
    .btn-cancel {
      background-color: #dc3545;
      color: #fff;
      padding: 8px 16px;
      border: none;
      border-radius: 5px;
      text-decoration: none;
      display: inline-block;
      transition: background-color 0.3s ease;
      font-size: 0.9rem;
    }
    .btn-cancel:hover {
      background-color: #a71d2a;
      color: #fff;
    }
    .btn-invoice {
      background-color: #493d9e;
      color: #fff;
      padding: 8px 16px;
      border: none;
      border-radius: 5px;
      text-decoration: none;
      display: inline-block;
      transition: background-color 0.3s ease;
      font-size: 0.9rem;
      margin-right: 10px;
    }
    .btn-invoice:hover {
      background-color: #3d3486;
      color: #fff;
    }
    .page-header {
      background: linear-gradient(45deg, #493d9e, #8571ff);
      color: white;
      padding: 60px 0;
      margin-bottom: 40px;
    }
    .filter-section {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      margin-bottom: 30px;
    }
    .stats-card {
      background: linear-gradient(45deg, #149dcc, #2fcef5);
      color: white;
      padding: 20px;
      border-radius: 10px;
      text-align: center;
      margin-bottom: 20px;
    }
    .stats-number {
      font-size: 2rem;
      font-weight: bold;
      margin-bottom: 5px;
    }
    .stats-label {
      font-size: 0.9rem;
      opacity: 0.9;
    }
    .empty-state {
      text-align: center;
      padding: 60px 20px;
      background: white;
      border-radius: 15px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .empty-state .mbr-iconfont {
      font-size: 4rem;
      color: #ccc;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  
<section data-bs-version="5.1" class="menu menu2 cid-uLC4xntJah" once="menu" id="menu02-1m">
    <nav class="navbar navbar-dropdown navbar-fixed-top navbar-expand-lg">
        <div class="container">
            <div class="navbar-brand">
                <span class="navbar-logo">
                    <a href="user_dashboard.php">
                        <img src="../assets/images/cronykaraoke-1.webp" alt="Crony Karaoke Logo" style="height: 3rem;">
                    </a>
                </span>
                <span class="navbar-caption-wrap"><a class="navbar-caption text-black text-primary display-4" href="user_dashboard.php">Crony<br>Karaoke</a></span>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-bs-toggle="collapse" data-target="#navbarSupportedContent" data-bs-target="#navbarSupportedContent" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav-dropdown ms-auto me-auto" style="margin-right:2rem;" data-app-modern-menu="true">
                    <li class="nav-item">
                        <a class="nav-link link text-black text-primary display-4" href="user_dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link link text-black text-primary display-4" href="make_reservation.php">Book Room</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link link text-black text-primary display-4" href="user_dashboard.php#newsletter-promotions">Newsletter</a>
                    </li>
                </ul>
                <div class="navbar-buttons mbr-section-btn d-flex align-items-center gap-2">
                    <a href="mailto:helper@cronykaraoke.com" class="btn btn-link p-0" title="Email Helper">
                        <span class="mbr-iconfont mobi-mbri-letter mobi-mbri" style="font-size:1.5rem;color:#149dcc;"></span>
                    </a>
                    <a href="tel:+60165014332" class="btn btn-link p-0" title="Call Helper">
                        <span class="mbr-iconfont mobi-mbri-phone mobi-mbri" style="font-size:1.5rem;color:#149dcc;"></span>
                    </a>
                    <a class="btn btn-primary display-4 ms-2" href="../logout.php">Logout</a>
                </div>
            </div>
        </div>
    </nav>
</section>

<section class="page-header" style="padding-top: 150px; padding-bottom: 40px; margin-bottom: 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h1 class="display-3 fw-bold">Your Bookings</h1>
                <p class="lead">View and manage all your karaoke room reservations</p>
            </div>
        </div>
    </div>
</section>

<section data-bs-version="5.1" id="view-bookings" style="padding-top: 50px; padding-bottom: 90px; background: #edefeb;">
    <div class="container">
        
        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number">5</div>
                    <div class="stats-label">Total Bookings</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" style="background: linear-gradient(45deg, #28a745, #34ce57);">
                    <div class="stats-number">3</div>
                    <div class="stats-label">Confirmed</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" style="background: linear-gradient(45deg, #ffc107, #ffda49);">
                    <div class="stats-number">1</div>
                    <div class="stats-label">Pending</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" style="background: linear-gradient(45deg, #dc3545, #ff6b7d);">
                    <div class="stats-number">1</div>
                    <div class="stats-label">Cancelled</div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Status Filter</label>
                    <select class="form-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="pending">Pending</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date From</label>
                    <input type="date" class="form-control" id="dateFrom">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date To</label>
                    <input type="date" class="form-control" id="dateTo">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary w-100" onclick="applyFilters()">
                        <span class="mbr-iconfont mobi-mbri-search mobi-mbri me-2"></span>Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Bookings List -->
        <div id="bookings-container">
            
            <!-- Booking Card 1 - Confirmed -->
            <div class="booking-card" data-status="confirmed" data-date="2025-06-15">
                <div class="booking-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1"><strong>VIP Room - Reservation #12345</strong></h4>
                            <p class="mb-0">Booked on May 20, 2025</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <span class="booking-status status-confirmed">Confirmed</span>
                        </div>
                    </div>
                </div>
                
                <div class="booking-details">
                    <div class="detail-item">
                        <div class="detail-label">Date</div>
                        <div class="detail-value">June 15, 2025</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Time</div>
                        <div class="detail-value">7:00 PM - 10:00 PM</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Duration</div>
                        <div class="detail-value">3 hours</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Total Amount</div>
                        <div class="detail-value">RM 297.00</div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-8">
                        <h6><strong>Special Requests:</strong></h6>
                        <p class="text-muted mb-0">Please prepare birthday decorations and a cake table.</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="view_invoice.php?id=12345" class="btn-invoice">View Invoice</a>
                        <a href="cancel_reservation.php?id=12345" class="btn-cancel">Cancel</a>
                    </div>
                </div>
            </div>

            <!-- Booking Card 2 - Pending -->
            <div class="booking-card" data-status="pending" data-date="2025-06-10">
                <div class="booking-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1"><strong>Deluxe Room - Reservation #12344</strong></h4>
                            <p class="mb-0">Booked on May 18, 2025</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <span class="booking-status status-pending">Pending Payment</span>
                        </div>
                    </div>
                </div>
                
                <div class="booking-details">
                    <div class="detail-item">
                        <div class="detail-label">Date</div>
                        <div class="detail-value">June 10, 2025</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Time</div>
                        <div class="detail-value">2:00 PM - 4:00 PM</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Duration</div>
                        <div class="detail-value">2 hours</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Total Amount</div>
                        <div class="detail-value">RM 130.00</div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-8">
                        <h6><strong>Special Requests:</strong></h6>
                        <p class="text-muted mb-0">No special requests.</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="complete_payment.php?id=12344" class="btn-invoice">Complete Payment</a>
                        <a href="cancel_reservation.php?id=12344" class="btn-cancel">Cancel</a>
                    </div>
                </div>
            </div>

            <!-- Booking Card 3 - Confirmed -->
            <div class="booking-card" data-status="confirmed" data-date="2025-06-01">
                <div class="booking-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1"><strong>Standard Room - Reservation #12343</strong></h4>
                            <p class="mb-0">Booked on May 15, 2025</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <span class="booking-status status-confirmed">Confirmed</span>
                        </div>
                    </div>
                </div>
                
                <div class="booking-details">
                    <div class="detail-item">
                        <div class="detail-label">Date</div>
                        <div class="detail-value">June 01, 2025</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Time</div>
                        <div class="detail-value">12:00 PM - 2:00 PM</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Duration</div>
                        <div class="detail-value">2 hours</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Total Amount</div>
                        <div class="detail-value">RM 80.00</div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-8">
                        <h6><strong>Special Requests:</strong></h6>
                        <p class="text-muted mb-0">Extra microphones for group singing.</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="view_invoice.php?id=12343" class="btn-invoice">View Invoice</a>
                        <span class="text-muted">Cannot Cancel</span>
                    </div>
                </div>
            </div>

            <!-- Booking Card 4 - Cancelled -->
            <div class="booking-card" data-status="cancelled" data-date="2025-05-28">
                <div class="booking-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1"><strong>Deluxe Room - Reservation #12342</strong></h4>
                            <p class="mb-0">Booked on May 10, 2025 | Cancelled on May 12, 2025</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <span class="booking-status status-cancelled">Cancelled</span>
                        </div>
                    </div>
                </div>
                
                <div class="booking-details">
                    <div class="detail-item">
                        <div class="detail-label">Date</div>
                        <div class="detail-value">May 28, 2025</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Time</div>
                        <div class="detail-value">6:00 PM - 8:00 PM</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Duration</div>
                        <div class="detail-value">2 hours</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Refund Amount</div>
                        <div class="detail-value">RM 130.00</div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-8">
                        <h6><strong>Cancellation Reason:</strong></h6>
                        <p class="text-muted mb-0">Customer requested cancellation due to schedule conflict.</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="view_invoice.php?id=12342" class="btn-invoice">View Refund Receipt</a>
                    </div>
                </div>
            </div>

            <!-- Booking Card 5 - Confirmed -->
            <div class="booking-card" data-status="confirmed" data-date="2025-05-25">
                <div class="booking-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1"><strong>VIP Room - Reservation #12341</strong></h4>
                            <p class="mb-0">Booked on May 05, 2025</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <span class="booking-status status-confirmed">Completed</span>
                        </div>
                    </div>
                </div>
                
                <div class="booking-details">
                    <div class="detail-item">
                        <div class="detail-label">Date</div>
                        <div class="detail-value">May 25, 2025</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Time</div>
                        <div class="detail-value">8:00 PM - 11:00 PM</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Duration</div>
                        <div class="detail-value">3 hours</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Total Paid</div>
                        <div class="detail-value">RM 297.00</div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-8">
                        <h6><strong>Special Requests:</strong></h6>
                        <p class="text-muted mb-0">Corporate team building event setup.</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="view_invoice.php?id=12341" class="btn-invoice">View Invoice</a>
                        <a href="leave_review.php?id=12341" class="btn" style="background-color: #28a745; color: white; padding: 8px 16px; border-radius: 5px; text-decoration: none;">Leave Review</a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Empty State (hidden by default) -->
        <div id="empty-state" class="empty-state" style="display: none;">
            <span class="mbr-iconfont mobi-mbri-search mobi-mbri"></span>
            <h4>No bookings found</h4>
            <p class="text-muted">Try adjusting your filters or <a href="make_reservation.php">make a new booking</a>.</p>
        </div>

    </div>
</section>

<section data-bs-version="5.1" class="footer3 cid-uLCpCfgtNL" once="footers" id="footer03-22" style="padding-top: 40px; padding-bottom: 0px;">
    <div class="container">
        <div class="row">
            <div class="col-12 content-head">
                <div class="mbr-section-head mb-5">
                    <div class="container text-center">
                        <a href="user_dashboard.php" class="btn btn-light btn-sm">Back to Dashboard</a>
                        <a href="../logout.php" class="btn btn-light btn-sm">Logout</a>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-5">
                <p class="mbr-fonts-style copyright display-8">
                    © Copyright 2025 Crony Karaoke - All Rights Reserved
                </p>
            </div>
        </div>
    </div>
</section>

  <script src="../assets/web/assets/jquery/jquery.min.js"></script>
  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/smoothscroll/smooth-scroll.js"></script>
  <script src="../assets/dropdown/js/script.min.js"></script>
  <script src="../assets/touchswipe/jquery.touch-swipe.min.js"></script>
  <script src="../assets/theme/js/script.js"></script>
  <script src="../assets/formoid/formoid.min.js"></script>

  <script>
    // Filter functionality
    function applyFilters() {
        const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
        const dateFrom = document.getElementById('dateFrom').value;
        const dateTo = document.getElementById('dateTo').value;
        
        const bookingCards = document.querySelectorAll('.booking-card');
        let visibleCount = 0;
        
        bookingCards.forEach(card => {
            const cardStatus = card.getAttribute('data-status');
            const cardDate = card.getAttribute('data-date');
            
            let showCard = true;
            
            // Status filter
            if (statusFilter && cardStatus !== statusFilter) {
                showCard = false;
            }
            
            // Date range filter
            if (dateFrom && cardDate < dateFrom) {
                showCard = false;
            }
            
            if (dateTo && cardDate > dateTo) {
                showCard = false;
            }
            
            if (showCard) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Show empty state if no bookings match filters
        const emptyState = document.getElementById('empty-state');
        if (visibleCount === 0) {
            emptyState.style.display = 'block';
        } else {
            emptyState.style.display = 'none';
        }
    }

    // Clear filters
    function clearFilters() {
        document.getElementById('statusFilter').value = '';
        document.getElementById('dateFrom').value = '';
        document.getElementById('dateTo').value = '';
        
        const bookingCards = document.querySelectorAll('.booking-card');
        bookingCards.forEach(card => {
            card.style.display = 'block';
        });
        
        document.getElementById('empty-state').style.display = 'none';
    }

    // Set minimum date for date inputs
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('dateFrom').setAttribute('max', today);
        document.getElementById('dateTo').setAttribute('max', today);
    });
  </script>

  <input name="animation" type="hidden">
</body>
</html>