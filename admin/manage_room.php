<?php
include '../includes/header.php';
require_once '../includes/db_connect.php';

if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $rate = $_POST['rate'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO rooms (name, rate, status) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $name, $rate, $status);
    $stmt->execute();
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM rooms WHERE id = $id");
}

$rooms = $conn->query("SELECT * FROM rooms");
?>

<h3>Manage Rooms</h3>
<form method="POST" class="row g-3 mb-4">
    <div class="col-md-4">
        <input type="text" name="name" placeholder="Room Name" class="form-control" required>
    </div>
    <div class="col-md-3">
        <input type="number" name="rate" placeholder="Rate per hour" class="form-control" required>
    </div>
    <div class="col-md-3">
        <select name="status" class="form-control" required>
            <option value="available">Available</option>
            <option value="maintenance">Maintenance</option>
        </select>
    </div>
    <div class="col-md-2">
        <button name="add" class="btn btn-success">Add Room</button>
    </div>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Room Name</th>
            <th>Rate (RM/hour)</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $rooms->fetch_assoc()): ?>
            <tr>
                <td><?= $row['name'] ?></td>
                <td><?= $row['rate'] ?></td>
                <td><?= ucfirst($row['status']) ?></td>
                <td>
                    <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this room?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php include '../includes/footer.php'; ?>
