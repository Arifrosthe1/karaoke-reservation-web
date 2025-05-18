<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Karaoke Reservation</title>
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand" href="#">KaraokeSys</a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <li class="nav-item"><a class="nav-link" href="../admin/dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="../admin/manage_rooms.php">Rooms</a></li>
                <li class="nav-item"><a class="nav-link" href="../admin/view_reservations.php">Reservations</a></li>
            <?php else: ?>
                <li class="nav-item"><a class="nav-link" href="../pages/user_dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="../pages/make_reservation.php">Book Room</a></li>
            <?php endif; ?>
            <li class="nav-item"><a class="nav-link" href="../auth/logout.php">Logout</a></li>
        </ul>
    </div>
</nav>
<div class="container mt-4">