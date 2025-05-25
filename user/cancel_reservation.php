<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
  <link rel="shortcut icon" href="../assets/images/cronykaraoke.webp" type="image/x-icon">
  <meta name="description" content="Crony Karaoke - Cancel Reservation">

  <title>Cancel Reservation - Crony Karaoke</title>
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
    .reservation-card {
      background: white;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      margin-bottom: 20px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .reservation-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .reservation-card.selected {
      border: 3px solid #dc3545;
      background: #fff5f5;
    }
    .page-header {
      background: linear-gradient(45deg, #493d9e, #8571ff);
      color: white;
      padding: 60px 0;
      margin-bottom: 40px;
    }
    .cancel-summary {
      background: linear-gradient(45deg, #dc3545, #ff6b7d);
      color: white;
      border-radius: 10px;
      padding: 20px;
      height: 100%;
    }
    .btn-cancel {
      background: #dc3545;
      border-color: #dc3545;
      color: white;
    }
    .btn-cancel:hover {
      background: #c82333;
      border-color: #bd2130;
      color: white;
    }
    .btn-primary {
      background: #493d9e;
      border-color: #493d9e;
    }
    .btn-primary:hover {
      background: #3d3486;
      border-color: #3d3486;
    }
    .status-badge {
      font-size: 0.8rem;
      padding: 4px 8px;
    }
    .status-confirmed {
      background-color: #28a745;
      color: white;
    }
    .status-pending {
      background-color: #ffc107;
      color: #000;
    }
    .alert {
      margin-bottom: 20px;
    }
    .form-section {
      margin-bottom: 30px;
    }
    .cancellation-reason {
      background: #f9f9f9;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
    }
    .reservation-details {
      border-left: 4px solid #493d9e;
      padding-left: 15px;
      margin-bottom: 15px;
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
                <h1 class="display-3 fw-bold">Cancel Your Reservation</h1>
                <p class="lead">Select the booking you want to cancel and provide a reason</p>
            </div>
        </div>
    </div>
</section>

<section data-bs-version="5.1" id="cancel-reservation" style="padding-top: 50px; padding-bottom: 90px; background: #edefeb;">
    <div class="container">
        <!-- Alert Messages -->
        <div id="success-alert" class="alert alert-success alert-dismissible fade" role="alert" style="display: none;">
            <strong>Success!</strong> Your reservation has been cancelled successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        
        <div id="error-alert" class="alert alert-danger alert-dismissible fade" role="alert" style="display: none;">
            <strong>Error!</strong> <span id="error-message">Unable to cancel reservation. Please try again.</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <form id="cancelForm" class="needs-validation" novalidate>
            <!-- Step 1: Select Reservation to Cancel -->
            <div class="form-section">
                <h2 class="text-center mb-4"><strong>Step 1: Select Reservation to Cancel</strong></h2>
                <div class="row" id="reservations-list">
                    <!-- Sample reservations - in real implementation, these would be populated from database -->
                    <div class="col-md-6 mb-3">
                        <div class="reservation-card" onclick="selectReservation('RES001', 'Standard Room', '2025-05-28', '19:00', '2 hours', 'RM 80.00')">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5><strong>Standard Room</strong></h5>
                                <span class="badge status-confirmed status-badge">Confirmed</span>
                            </div>
                            <div class="reservation-details">
                                <p class="mb-1"><strong>Date:</strong> May 28, 2025</p>
                                <p class="mb-1"><strong>Time:</strong> 7:00 PM - 9:00 PM</p>
                                <p class="mb-1"><strong>Duration:</strong> 2 hours</p>
                                <p class="mb-0"><strong>Total:</strong> RM 80.00</p>
                            </div>
                            <div class="form-check mt-3">
                                <input class="form-check-input reservation-select" type="radio" name="reservation" id="res-001" value="RES001" required>
                                <label class="form-check-label" for="res-001">
                                    Select this reservation
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="reservation-card" onclick="selectReservation('RES002', 'Deluxe Room', '2025-05-30', '15:00', '3 hours', 'RM 195.00')">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5><strong>Deluxe Room</strong></h5>
                                <span class="badge status-confirmed status-badge">Confirmed</span>
                            </div>
                            <div class="reservation-details">
                                <p class="mb-1"><strong>Date:</strong> May 30, 2025</p>
                                <p class="mb-1"><strong>Time:</strong> 3:00 PM - 6:00 PM</p>
                                <p class="mb-1"><strong>Duration:</strong> 3 hours</p>
                                <p class="mb-0"><strong>Total:</strong> RM 195.00</p>
                            </div>
                            <div class="form-check mt-3">
                                <input class="form-check-input reservation-select" type="radio" name="reservation" id="res-002" value="RES002" required>
                                <label class="form-check-label" for="res-002">
                                    Select this reservation
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="reservation-card" onclick="selectReservation('RES003', 'VIP Room', '2025-06-02', '20:00', '4 hours', 'RM 396.00')">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5><strong>VIP Room</strong></h5>
                                <span class="badge status-pending status-badge">Pending</span>
                            </div>
                            <div class="reservation-details">
                                <p class="mb-1"><strong>Date:</strong> June 2, 2025</p>
                                <p class="mb-1"><strong>Time:</strong> 8:00 PM - 12:00 AM</p>
                                <p class="mb-1"><strong>Duration:</strong> 4 hours</p>
                                <p class="mb-0"><strong>Total:</strong> RM 396.00</p>
                            </div>
                            <div class="form-check mt-3">
                                <input class="form-check-input reservation-select" type="radio" name="reservation" id="res-003" value="RES003" required>
                                <label class="form-check-label" for="res-003">
                                    Select this reservation
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="reservation-card" onclick="selectReservation('RES004', 'Standard Room', '2025-06-05', '16:00', '1 hour', 'RM 40.00')">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5><strong>Standard Room</strong></h5>
                                <span class="badge status-confirmed status-badge">Confirmed</span>
                            </div>
                            <div class="reservation-details">
                                <p class="mb-1"><strong>Date:</strong> June 5, 2025</p>
                                <p class="mb-1"><strong>Time:</strong> 4:00 PM - 5:00 PM</p>
                                <p class="mb-1"><strong>Duration:</strong> 1 hour</p>
                                <p class="mb-0"><strong>Total:</strong> RM 40.00</p>
                            </div>
                            <div class="form-check mt-3">
                                <input class="form-check-input reservation-select" type="radio" name="reservation" id="res-004" value="RES004" required>
                                <label class="form-check-label" for="res-004">
                                    Select this reservation
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="invalid-feedback">
                    Please select a reservation to cancel.
                </div>
            </div>

            <!-- Step 2: Cancellation Reason -->
            <div class="form-section cancellation-reason mt-5">
                <h2 class="text-center mb-4"><strong>Step 2: Reason for Cancellation</strong></h2>
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="cancellation-reason" class="form-label">Please tell us why you're cancelling (Optional)</label>
                            <select class="form-select mb-3" id="reason-select" name="reason">
                                <option value="" selected>Select a reason (optional)</option>
                                <option value="Change of plans">Change of plans</option>
                                <option value="Emergency">Emergency</option>
                                <option value="Double booking">Double booking</option>
                                <option value="Health issues">Health issues</option>
                                <option value="Weather conditions">Weather conditions</option>
                                <option value="Other">Other</option>
                            </select>
                            <textarea class="form-control" id="cancellation-reason" name="additional_comments" rows="4" placeholder="Additional comments (optional)..."></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="cancel-summary d-flex flex-column align-items-center">
                            <h3 class="mb-4 text-white"><strong>Cancellation Summary</strong></h3>
                            <div id="cancel-summary-details" class="w-100">
                                <p><strong>Room:</strong> <span id="cancel-room">Not selected</span></p>
                                <p><strong>Date:</strong> <span id="cancel-date">Not selected</span></p>
                                <p><strong>Time:</strong> <span id="cancel-time">Not selected</span></p>
                                <p><strong>Duration:</strong> <span id="cancel-duration">Not selected</span></p>
                                <hr>
                                <h5 class="text-white"><strong>Refund Amount: <span id="cancel-refund">RM 0.00</span></strong></h5>
                                <small class="text-light">*Refund will be processed within 3-5 business days</small>
                            </div>
                            <button type="submit" class="btn btn-light btn-lg mt-4" style="width: 70%; display: block; margin-left: auto; margin-right: auto;">
                                <span class="mbr-iconfont mobi-mbri-close mobi-mbri me-2"></span>
                                Cancel Reservation
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
    // Reservation selection
    function selectReservation(id, room, date, time, duration, total) {
        // Clear all selections
        document.querySelectorAll('.reservation-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        // Select the clicked reservation
        event.currentTarget.classList.add('selected');
        document.querySelector(`input[value="${id}"]`).checked = true;
        
        // Update cancellation summary
        updateCancelSummary(room, date, time, duration, total);
    }

    // Update cancellation summary
    function updateCancelSummary(room, date, time, duration, total) {
        document.getElementById('cancel-room').textContent = room || "Not selected";
        
        // Format date
        if (date) {
            const dateObj = new Date(date);
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('cancel-date').textContent = dateObj.toLocaleDateString('en-US', options);
        } else {
            document.getElementById('cancel-date').textContent = "Not selected";
        }
        
        // Format time
        if (time) {
            const [hours, minutes] = time.split(':');
            const hour = parseInt(hours);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const displayHour = hour > 12 ? hour - 12 : (hour === 0 ? 12 : hour);
            
            // Calculate end time based on duration
            if (duration) {
                const durationHours = parseInt(duration.split(' ')[0]);
                const endHour = hour + durationHours;
                const endAmpm = endHour >= 12 ? 'PM' : 'AM';
                const endDisplayHour = endHour > 12 ? endHour - 12 : (endHour === 0 ? 12 : endHour);
                document.getElementById('cancel-time').textContent = `${displayHour}:00 ${ampm} - ${endDisplayHour}:00 ${endAmpm}`;
            } else {
                document.getElementById('cancel-time').textContent = `${displayHour}:00 ${ampm}`;
            }
        } else {
            document.getElementById('cancel-time').textContent = "Not selected";
        }
        
        document.getElementById('cancel-duration').textContent = duration || "Not selected";
        document.getElementById('cancel-refund').textContent = total || "RM 0.00";
    }

    // Form submission
    document.getElementById('cancelForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!this.checkValidity()) {
            e.stopPropagation();
            this.classList.add('was-validated');
            return;
        }
        
        // Get selected reservation
        const selectedReservation = document.querySelector('input[name="reservation"]:checked');
        if (!selectedReservation) {
            showError('Please select a reservation to cancel.');
            return;
        }
        
        // Simulate cancellation process
        const submitBtn = document.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Processing...';
        
        // Simulate API call
        setTimeout(() => {
            // Success simulation
            showSuccess();
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            
            // Reset form after success
            setTimeout(() => {
                this.reset();
                document.querySelectorAll('.reservation-card').forEach(card => {
                    card.classList.remove('selected');
                });
                updateCancelSummary('', '', '', '', '');
            }, 2000);
        }, 2000);
    });

    function showSuccess() {
        const alert = document.getElementById('success-alert');
        alert.style.display = 'block';
        alert.classList.add('show');
        setTimeout(() => {
            alert.classList.remove('show');
            setTimeout(() => alert.style.display = 'none', 150);
        }, 5000);
    }

    function showError(message) {
        const alert = document.getElementById('error-alert');
        const messageSpan = document.getElementById('error-message');
        messageSpan.textContent = message;
        alert.style.display = 'block';
        alert.classList.add('show');
        setTimeout(() => {
            alert.classList.remove('show');
            setTimeout(() => alert.style.display = 'none', 150);
        }, 5000);
    }

    // Form validation
    (function () {
        'use strict'
        
        var forms = document.querySelectorAll('.needs-validation')
        
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    
                    form.classList.add('was-validated')
                }, false)
            })
    })();
</script>

<input name="animation" type="hidden">
</body>
</html>