<?php
include '../includes/header.php';
require_once '../includes/db_connect.php';

$reservations = $conn->query("SELECT r.*, u.username, rm.name AS room_name 
                              FROM reservations r 
                              JOIN users u ON r.user_id = u.id 
                              JOIN rooms rm ON r.room_id = rm.id 
                              ORDER BY r.date DESC, r.time DESC");
?>

<h3>All Reservations</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>User</th>
            <th>Room</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Payment</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $reservations->fetch_assoc()): ?>
            <tr>
                <td><?= $row['username'] ?></td>
                <td><?= $row['room_name'] ?></td>
                <td><?= $row['date'] ?></td>
                <td><?= $row['time'] ?></td>
                <td><?= ucfirst($row['status']) ?></td>
                <td><?= ucfirst($row['payment_status']) ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php include '../includes/footer.php'; ?>
