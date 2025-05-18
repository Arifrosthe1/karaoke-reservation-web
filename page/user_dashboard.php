<?php include '../includes/header.php'; ?>
<h3>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h3>
<p>This is your dashboard. You can book rooms, view or cancel future reservations, and view invoices.</p>

<div class="mt-4">
    <a href="make_reservation.php" class="btn btn-primary">Make a Reservation</a>
    <a href="my_reservations.php" class="btn btn-info">My Reservations</a>
</div>
<?php include '../includes/footer.php'; ?>
