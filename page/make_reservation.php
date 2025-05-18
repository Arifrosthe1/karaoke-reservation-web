<?php
include '../includes/header.php';
require_once '../includes/db_connect.php';

date_default_timezone_set('Asia/Kuala_Lumpur');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id = $_POST['room_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Check for overlapping bookings
    $stmt = $conn->prepare("SELECT * FROM reservations WHERE room_id = ? AND date = ? AND time = ?");
    $stmt->bind_param("iss", $room_id, $date, $time);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $msg = "<div class='alert alert-danger'>Room is already booked for that slot!</div>";
    } else {
        $stmt = $conn->prepare("INSERT INTO reservations (user_id, room_id, date, time, status, payment_status) VALUES (?, ?, ?, ?, 'confirmed', 'paid')");
        $stmt->bind_param("iiss", $_SESSION['user_id'], $room_id, $date, $time);
        if ($stmt->execute()) {
            $msg = "<div class='alert alert-success'>Reservation successful!</div>";
        } else {
            $msg = "<div class='alert alert-danger'>Failed to reserve. Try again.</div>";
        }
    }
}

$rooms = $conn->query("SELECT * FROM rooms WHERE status = 'available'");
?>

<h3>Make a Reservation</h3>
<?php if (isset($msg)) echo $msg; ?>
<form method="POST">
    <div class="mb-3">
        <label>Room</label>
        <select name="room_id" class="form-control" required>
            <?php while ($row = $rooms->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>"><?= $row['name'] ?> (RM <?= $row['rate'] ?>/hour)</option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Date</label>
        <input type="date" name="date" class="form-control" required min="<?= date('Y-m-d') ?>">
    </div>
    <div class="mb-3">
        <label>Time Slot</label>
        <select name="time" class="form-control" required>
            <?php
            for ($hour = 10; $hour < 24; $hour++) {
                $start = sprintf("%02d:00", $hour);
                echo "<option value='$start'>$start - " . sprintf("%02d:00", $hour + 1) . "</option>";
            }
            ?>
        </select>
    </div>
    <button class="btn btn-primary">Confirm</button>
</form>
<?php include '../includes/footer.php'; ?>
