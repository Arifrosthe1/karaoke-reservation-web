<?php
include '../includes/header.php';
require_once '../includes/db_connect.php';

$user_id = $_SESSION['user_id'];
$reservations = $conn->query("SELECT r.*, rm.name AS room_name, rm.rate FROM reservations r JOIN rooms rm ON r.room_id = rm.id WHERE r.user_id = $user_id ORDER BY r.date DESC, r.time DESC");

if (isset($_GET['cancel']) && is_numeric($_GET['cancel'])) {
    $id = intval($_GET['cancel']);
    $check = $conn->query("SELECT * FROM reservations WHERE id = $id AND user_id = $user_id AND date >= CURDATE()");
    if ($check->num_rows == 1) {
        $conn->query("UPDATE reservations SET status = 'cancelled' WHERE id = $id");
        echo "<div class='alert alert-success'>Reservation cancelled.</div>";
    }
}
?>

<h3>My Reservations</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Room</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Payment</th>
            <th>Invoice</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $reservations->fetch_assoc()): ?>
        <tr>
            <td><?= $row['room_name'] ?></td>
            <td><?= $row['date'] ?></td>
            <td><?= $row['time'] ?></td>
            <td><?= ucfirst($row['status']) ?></td>
            <td><?= ucfirst($row['payment_status']) ?></td>
            <td><a href="invoice.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">View</a></td>
            <td>
                <?php if ($row['status'] == 'confirmed' && $row['date'] >= date('Y-m-d')): ?>
                    <a href="?cancel=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Cancel this booking?')">Cancel</a>
                <?php else: ?>—
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
<?php include '../includes/footer.php'; ?>
