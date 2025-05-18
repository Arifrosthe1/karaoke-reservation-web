<?php
include '../includes/header.php';
require_once '../includes/db_connect.php';

// Summary
$total_users = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'user'")->fetch_assoc()['total'];
$total_rooms = $conn->query("SELECT COUNT(*) as total FROM rooms")->fetch_assoc()['total'];
$total_reservations = $conn->query("SELECT COUNT(*) as total FROM reservations")->fetch_assoc()['total'];
?>

<h3>Admin Dashboard</h3>
<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Users</h5>
                <p class="card-text"><?= $total_users ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Rooms</h5>
                <p class="card-text"><?= $total_rooms ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">Reservations</h5>
                <p class="card-text"><?= $total_reservations ?></p>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
