<?php
include '../includes/header.php';
require_once '../includes/db_connect.php';

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT r.*, u.username, rm.name AS room_name, rm.rate FROM reservations r 
                        JOIN users u ON r.user_id = u.id 
                        JOIN rooms rm ON r.room_id = rm.id 
                        WHERE r.id = ? AND r.user_id = ?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows == 0) {
    echo "<div class='alert alert-danger'>Invoice not found.</div>";
    include '../includes/footer.php';
    exit();
}
$row = $res->fetch_assoc();
$cost = $row['rate'];
$invoice_no = "INV" . str_pad($row['id'], 5, '0', STR_PAD_LEFT);
?>

<h3>Invoice</h3>
<table class="table table-bordered">
    <tr><th>Invoice #</th><td><?= $invoice_no ?></td></tr>
    <tr><th>Customer</th><td><?= $row['username'] ?></td></tr>
    <tr><th>Room</th><td><?= $row['room_name'] ?></td></tr>
    <tr><th>Date</th><td><?= $row['date'] ?></td></tr>
    <tr><th>Time</th><td><?= $row['time'] ?></td></tr>
    <tr><th>Cost</th><td>RM <?= $cost ?></td></tr>
    <tr><th>Status</th><td><?= ucfirst($row['status']) ?></td></tr>
    <tr><th>Payment</th><td><?= ucfirst($row['payment_status']) ?></td></tr>
</table>
<a href="my_reservations.php" class="btn btn-secondary">Back</a>
<?php include '../includes/footer.php'; ?>
