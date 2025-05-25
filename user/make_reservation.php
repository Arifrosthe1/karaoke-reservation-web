<?php
include '../dbconfig.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: ../login.php");
    exit();
}

$userID = $_SESSION['userID'];
$username = $_SESSION['fullName'];

// Get available rooms with their packages
$roomsQuery = "SELECT r.*, p.packageName, p.pricePerHour, p.description 
               FROM rooms r 
               JOIN packages p ON r.packageID = p.packageID 
               WHERE r.status = 'available' 
               ORDER BY r.packageID, r.roomName";
$roomsResult = mysqli_query($conn, $roomsQuery);

// Handle form submission
if(isset($_POST['submit'])) {
    $roomType = $_POST['room'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $duration = $_POST['duration'];
    $specialRequests = isset($_POST['special_requests']) ? $_POST['special_requests'] : '';

    // Get all available rooms of the selected package
    $roomQuery = "SELECT r.roomID, p.pricePerHour 
                  FROM rooms r 
                  JOIN packages p ON r.packageID = p.packageID 
                  WHERE p.packageName = ? AND r.status = 'available'";
    $stmt = mysqli_prepare($conn, $roomQuery);
    mysqli_stmt_bind_param($stmt, "s", $roomType);
    mysqli_stmt_execute($stmt);
    $roomResult = mysqli_stmt_get_result($stmt);

    $roomFound = false;

    while ($roomData = mysqli_fetch_assoc($roomResult)) {
        $roomID = $roomData['roomID'];
        $pricePerHour = $roomData['pricePerHour'];
        $totalPrice = $pricePerHour * $duration;

        // Format start and end time
        $startTimeObj = DateTime::createFromFormat('H:i', $time);
        $endTimeObj = clone $startTimeObj;
        $endTimeObj->add(new DateInterval('PT' . $duration . 'H'));
        $startTime = $startTimeObj->format('H:i:s');
        $endTime = $endTimeObj->format('H:i:s');

        // Check if this room is already booked
        $conflictQuery = "SELECT COUNT(*) as count FROM reservations 
                          WHERE roomID = ? AND reservationDate = ? 
                          AND status != 'cancelled'
                          AND ((startTime < ? AND endTime > ?) 
                          OR (startTime < ? AND endTime > ?))";

        $conflictStmt = mysqli_prepare($conn, $conflictQuery);
        mysqli_stmt_bind_param($conflictStmt, "isssss", $roomID, $date, $endTime, $startTime, $startTime, $endTime);
        mysqli_stmt_execute($conflictStmt);
        $conflictResult = mysqli_stmt_get_result($conflictStmt);
        $conflictData = mysqli_fetch_assoc($conflictResult);

        if ($conflictData['count'] == 0) {
            $roomFound = true;

            // Store reservation data in session for payment
            $_SESSION['pending_reservation'] = [
                'userID' => $userID,
                'roomID' => $roomID,
                'roomType' => $roomType,
                'reservationDate' => $date,
                'startTime' => $startTime,
                'endTime' => $endTime,
                'duration' => $duration,
                'totalPrice' => $totalPrice,
                'specialRequests' => $specialRequests
            ];

            // Redirect to simulate payment
            header("Location: simulate_payment.php");
            exit();
        }
    }

    if (!$roomFound) {
        $error_message = "Sorry, all rooms of this type are fully booked at the selected time.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
  <link rel="shortcut icon" href="../assets/images/cronykaraoke.webp" type="image/x-icon">
  <meta name="description" content="Crony Karaoke - Make a Reservation">

  <title>Book a Room - Crony Karaoke</title>
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
    .reservation-form {
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .room-card {
      border-radius: 10px;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      margin-bottom: 20px;
      cursor: pointer;
    }
    .room-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
    .room-card.selected {
      border: 3px solid #493d9e;
    }
    .room-image {
      height: 180px;
      background-size: cover;
      background-position: center;
    }
    .room-details {
      padding: 15px;
    }
    .summary-card {
      background: linear-gradient(45deg, #493d9e, #8571ff);
      color: white;
      border-radius: 10px;
      padding: 20px;
      height: 100%;
    }
    .form-section {
      margin-bottom: 30px;
    }
    .form-label {
      font-weight: 600;
      color: #333;
    }
    .date-time-section {
      background: #f9f9f9;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
    }
    .btn-primary {
      background: #493d9e;
      border-color: #493d9e;
    }
    .btn-primary:hover {
      background: #3d3486;
      border-color: #3d3486;
    }
    .page-header {
      background: linear-gradient(45deg, #493d9e, #8571ff);
      color: white;
      padding: 60px 0;
      margin-bottom: 40px;
    }
    .alert {
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
                <h1 class="display-3 fw-bold">Book Your Karaoke Room</h1>
                <p class="lead">Select your preferred room, date, and time to reserve your spot</p>
            </div>
        </div>
    </div>
</section>

<section data-bs-version="5.1" id="reservation-form" style="padding-top: 50px; padding-bottom: 90px; background: #edefeb;">
    <div class="container">
        <?php if(isset($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="post" action="" class="needs-validation" novalidate>
            <!-- Step 1: Choose Room -->
            <div class="form-section">
                <h2 class="text-center mb-4"><strong>Step 1: Choose Your Room</strong></h2>
                <div class="row">
                    <!-- Room 1: Standard Room -->
                    <div class="col-md-4">
                        <div class="room-card" onclick="selectRoom('Standard')">
                            <div class="room-image" style="background-image: url('../assets/images/standard-room.png');"></div>
                            <div class="room-details" style="background:rgb(247, 243, 248);">
                                <h4><strong>Standard Room</strong></h4>
                                <p>Perfect for small groups of 2-4 people</p>
                                <ul>
                                    <li>Basic sound system</li>
                                    <li>2 microphones</li>
                                    <li>40" TV screen</li>
                                </ul>
                                <h5 class="text-primary">RM 40/hour</h5>
                                <div class="form-check mt-3">
                                    <input class="form-check-input room-select" type="radio" name="room" id="standard-room" value="Standard" required>
                                    <label class="form-check-label" for="standard-room">
                                        Select Room
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Room 2: Deluxe Room -->
                    <div class="col-md-4">
                        <div class="room-card" onclick="selectRoom('Deluxe')">
                            <div class="room-image" style="background-image: url('../assets/images/deluxe-room.png');"></div>
                            <div class="room-details" style="background:rgb(247, 243, 248);">
                                <h4><strong>Deluxe Room</strong></h4>
                                <p>Great for medium groups of 5-8 people</p>
                                <ul>
                                    <li>Enhanced sound system</li>
                                    <li>4 microphones</li>
                                    <li>50" TV screen</li>
                                </ul>
                                <h5 class="text-primary">RM 65/hour</h5>
                                <div class="form-check mt-3">
                                    <input class="form-check-input room-select" type="radio" name="room" id="deluxe-room" value="Deluxe" required>
                                    <label class="form-check-label" for="deluxe-room">
                                        Select Room
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Room 3: VIP Room -->
                    <div class="col-md-4">
                        <div class="room-card" onclick="selectRoom('VIP')">
                            <div class="room-image" style="background-image: url('../assets/images/vip-room.png');"></div>
                            <div class="room-details" style="background:rgb(247, 243, 248);">
                                <h4><strong>VIP Room</strong></h4>
                                <p>Luxury experience for 8-12 people</p>
                                <ul>
                                    <li>Premium sound system</li>
                                    <li>6 microphones</li>
                                    <li>65" TV screen</li>
                                </ul>
                                <h5 class="text-primary">RM 99/hour</h5>
                                <div class="form-check mt-3">
                                    <input class="form-check-input room-select" type="radio" name="room" id="vip-room" value="VIP" required>
                                    <label class="form-check-label" for="vip-room">
                                        Select Room
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Step 2: Choose Date and Time -->
            <div class="form-section date-time-section mt-5">
                <h2 class="text-center mb-4"><strong>Step 2: Select Date & Time</strong></h2>
                <div class="row">
                    <div class="col-md-4 d-flex align-items-stretch">
                        <div class="mb-3 w-100">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
                            <div class="invalid-feedback">
                                Please select a valid date.
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-stretch">
                        <div class="mb-3 w-100">
                            <label for="time" class="form-label">Time</label>
                            <select class="form-select" id="time" name="time" required>
                                <option value="" selected disabled>Select time</option>
                                <option value="10:00">10:00 AM</option>
                                <option value="11:00">11:00 AM</option>
                                <option value="12:00">12:00 PM</option>
                                <option value="13:00">1:00 PM</option>
                                <option value="14:00">2:00 PM</option>
                                <option value="15:00">3:00 PM</option>
                                <option value="16:00">4:00 PM</option>
                                <option value="17:00">5:00 PM</option>
                                <option value="18:00">6:00 PM</option>
                                <option value="19:00">7:00 PM</option>
                                <option value="20:00">8:00 PM</option>
                                <option value="21:00">9:00 PM</option>
                                <option value="22:00">10:00 PM</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select a time.
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-stretch">
                        <div class="mb-3 w-100">
                            <label for="duration" class="form-label">Duration (hours)</label>
                            <select class="form-select" id="duration" name="duration" required>
                                <option value="" selected disabled>Select duration</option>
                                <option value="1">1 hour</option>
                                <option value="2">2 hours</option>
                                <option value="3">3 hours</option>
                                <option value="4">4 hours</option>
                                <option value="5">5 hours</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select duration.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Step 3: Booking Summary -->
            <div class="row mt-5">
                <div class="col-md-8">
                    <div class="form-section">
                        <h2 class="mb-4"><strong>Step 3: Additional Information</strong></h2>
                        <div class="mb-3">
                            <label for="special-requests" class="form-label">Special Requests (Optional)</label>
                            <textarea class="form-control" id="special-requests" name="special_requests" rows="4" placeholder="Any special requests or arrangements..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="summary-card d-flex flex-column align-items-center">
                        <h3 class="mb-4 text-white"><strong>Booking Summary</strong></h3>
                        <div id="summary-details" class="w-100">
                            <p><strong>Room:</strong> <span id="summary-room">Not selected</span></p>
                            <p><strong>Date:</strong> <span id="summary-date">Not selected</span></p>
                            <p><strong>Time:</strong> <span id="summary-time">Not selected</span></p>
                            <p><strong>Duration:</strong> <span id="summary-duration">Not selected</span></p>
                            <hr>
                            <h4 class="text-white"><strong>Total: <span id="summary-total">RM 0.00</span></strong></h4>
                        </div>
                        <button type="submit" name="submit" class="btn btn-light btn-lg mt-4" style="width: 70%; display: block; margin-left: auto; margin-right: auto;">Confirm Booking</button>
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
    // Room selection
    function selectRoom(roomType) {
        // Clear all selections
        document.querySelectorAll('.room-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        // Select the clicked room
        document.getElementById(roomType.toLowerCase() + '-room').checked = true;
        document.getElementById(roomType.toLowerCase() + '-room').closest('.room-card').classList.add('selected');
        
        // Update summary
        updateSummary();
    }

    // Date, time and duration change
    document.getElementById('date').addEventListener('change', updateSummary);
    document.getElementById('time').addEventListener('change', function() {
        updateDurationOptions();
        updateSummary();
    });
    document.getElementById('duration').addEventListener('change', updateSummary);

    // Function to update duration options based on selected time
    function updateDurationOptions() {
        const timeSelect = document.getElementById('time');
        const durationSelect = document.getElementById('duration');
        const selectedTime = timeSelect.value;
        
        if (!selectedTime) {
            resetDurationOptions();
            return;
        }
        
        const selectedHour = parseInt(selectedTime.split(':')[0]);
        const maxHours = 23 - selectedHour;
        
        durationSelect.innerHTML = '<option value="" selected disabled>Select duration</option>';
        
        for (let i = 1; i <= Math.min(5, maxHours); i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = i + (i === 1 ? ' hour' : ' hours');
            durationSelect.appendChild(option);
        }
        
        if (maxHours <= 0) {
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'No available duration';
            option.disabled = true;
            durationSelect.appendChild(option);
        }
        
        durationSelect.value = '';
    }

    function resetDurationOptions() {
        const durationSelect = document.getElementById('duration');
        durationSelect.innerHTML = `
            <option value="" selected disabled>Select duration</option>
            <option value="1">1 hour</option>
            <option value="2">2 hours</option>
            <option value="3">3 hours</option>
            <option value="4">4 hours</option>
            <option value="5">5 hours</option>
        `;
    }

    // Update booking summary
    function updateSummary() {
        let roomElement = document.querySelector('input[name="room"]:checked');
        let roomName = "Not selected";
        let roomPrice = 0;
        
        if(roomElement) {
            switch(roomElement.value) {
                case 'Standard':
                    roomName = "Standard Room";
                    roomPrice = 40;
                    break;
                case 'Deluxe':
                    roomName = "Deluxe Room";
                    roomPrice = 65;
                    break;
                case 'VIP':
                    roomName = "VIP Room";
                    roomPrice = 99;
                    break;
            }
        }
        
        document.getElementById('summary-room').textContent = roomName;
        
        let date = document.getElementById('date').value;
        document.getElementById('summary-date').textContent = date ? new Date(date).toLocaleDateString() : "Not selected";
        
        let time = document.getElementById('time').value;
        let timeDisplay = "Not selected";
        if (time) {
            const hour = parseInt(time.split(':')[0]);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const displayHour = hour > 12 ? hour - 12 : (hour === 0 ? 12 : hour);
            timeDisplay = displayHour + ':00 ' + ampm;
        }
        document.getElementById('summary-time').textContent = timeDisplay;
        
        let duration = document.getElementById('duration').value;
        let durationDisplay = "Not selected";
        if (duration) {
            durationDisplay = duration + " hour(s)";
            
            if (time) {
                const startHour = parseInt(time.split(':')[0]);
                const endHour = startHour + parseInt(duration);
                const endAmpm = endHour >= 12 ? 'PM' : 'AM';
                const endDisplayHour = endHour > 12 ? endHour - 12 : (endHour === 0 ? 12 : endHour);
                durationDisplay += ` (until ${endDisplayHour}:00 ${endAmpm})`;
            }
        }
        document.getElementById('summary-duration').textContent = durationDisplay;
        
        let total = duration && roomPrice ? (roomPrice * duration) : 0;
        document.getElementById('summary-total').textContent = "RM " + total.toFixed(2);
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