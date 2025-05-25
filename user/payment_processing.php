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
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
    <link rel="shortcut icon" href="../assets/images/cronykaraoke.webp" type="image/x-icon">
    <meta name="description" content="Crony Karaoke - Processing Payment">
    <title>Payment Processing - Crony Karaoke</title>
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
        .payment-processing-card {
            background: white;
            border-radius: 10px;
            padding: 3rem;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        .spinner {
            width: 80px;
            height: 80px;
            border: 8px solid #f3f3f3;
            border-top: 8px solid #493d9e;
            border-radius: 50%;
            animation: spin 1.5s linear infinite;
            margin: 0 auto 2rem;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .processing-text {
            color: #333;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .processing-subtext {
            color: #666;
            margin-bottom: 2rem;
        }
        .page-header {
            background: linear-gradient(45deg, #493d9e, #8571ff);
            color: white;
            padding: 60px 0;
            margin-bottom: 0;
        }
        .progress {
            height: 10px;
            background-color: #e9ecef;
            border-radius: 5px;
        }
        .progress-bar {
            background-color: #493d9e;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<section class="page-header" style="padding-top: 40px; padding-bottom: 20px; margin-bottom: 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h1 class="display-3 fw-bold">Processing Your Payment</h1>
                <p class="lead">Please wait while we secure your reservation</p>
            </div>
        </div>
    </div>
</section>

<div class="content-wrapper d-flex justify-content-center align-items-center" style="min-height: 60vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 d-flex justify-content-center align-items-center">
                <div class="payment-processing-card">
                    <div class="spinner"></div>
                    <h3 class="processing-text">Processing your payment...</h3>
                    <p class="processing-subtext">Please don't close this window. You'll be redirected automatically when the process is complete.</p>
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">Processing... <span id="progress-text">0%</span></small>
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
    // Animate progress bar
    let progress = 0;
    const progressBar = document.querySelector('.progress-bar');
    const progressText = document.getElementById('progress-text');
    
    const interval = setInterval(function() {
        progress += Math.random() * 10 + 5; // Random increment between 5-15
        if (progress > 100) progress = 100;
        
        progressBar.style.width = progress + '%';
        progressBar.setAttribute('aria-valuenow', progress);
        progressText.textContent = Math.round(progress) + '%';
        
        if (progress >= 100) {
            clearInterval(interval);
            progressText.textContent = 'Complete!';
            
            // Redirect after progress reaches 100%
            setTimeout(function() {
                window.location.href = "payment_done.php";
            }, 1000);
        }
    }, 200); // Update every 200ms for smoother animation
</script>

</body>
</html>