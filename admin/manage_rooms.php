<?php
include '../dbconfig.php'; // your database connection
session_start();
// Basic session check for admin
if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Redirect if not logged in or not admin
    exit();
}

$message = '';
$messageType = ''; // 'success' or 'error'

// Handle Add Room Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_room'])) {
    $roomName = $_POST['new_room_name'];
    $pricePerHour = $_POST['new_room_price'];
    $capacity = $_POST['new_room_capacity'];
    $description = "Room for " . htmlspecialchars($capacity) . " people."; // Default description

    // Basic validation
    if (!empty($roomName) && is_numeric($pricePerHour) && $pricePerHour >= 0 && is_numeric($capacity) && $capacity > 0) {
        $stmt = $conn->prepare("INSERT INTO packages (packageName, description, pricePerHour) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $roomName, $description, $pricePerHour);
        if ($stmt->execute()) {
            // Optionally, you might want to add a corresponding entry to the 'rooms' table here
            // For simplicity, we're just managing 'packages' (room types) in this view.
            $message = 'Room type added successfully!';
            $messageType = 'success';
        } else {
            $message = 'Error adding room type: ' . $stmt->error;
            $messageType = 'error';
        }
        $stmt->close();
    } else {
        $message = 'Please fill in all fields correctly.';
        $messageType = 'error';
    }
}

// Handle Edit Room Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_room'])) {
    $packageID = $_POST['edit_package_id'];
    $roomName = $_POST['edit_room_name'];
    $pricePerHour = $_POST['edit_room_price'];
    $capacity = $_POST['edit_room_capacity'];
    $description = "Room for " . htmlspecialchars($capacity) . " people."; // Update description

    if (!empty($roomName) && is_numeric($pricePerHour) && $pricePerHour >= 0 && is_numeric($capacity) && $capacity > 0) {
        $stmt = $conn->prepare("UPDATE packages SET packageName = ?, description = ?, pricePerHour = ? WHERE packageID = ?");
        $stmt->bind_param("ssdi", $roomName, $description, $pricePerHour, $packageID);
        if ($stmt->execute()) {
            $message = 'Room type updated successfully!';
            $messageType = 'success';
        } else {
            $message = 'Error updating room type: ' . $stmt->error;
            $messageType = 'error';
        }
        $stmt->close();
    } else {
        $message = 'Please fill in all fields correctly for editing.';
        $messageType = 'error';
    }
}

// Handle Delete Room Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_room'])) {
    $packageID = $_POST['delete_package_id'];

    // In a real system, consider checking for existing reservations linked to this packageID
    // or set packageID to NULL in rooms table if it's a foreign key with ON DELETE SET NULL.
    // For this example, we'll directly delete from packages.
    $stmt = $conn->prepare("DELETE FROM packages WHERE packageID = ?");
    $stmt->bind_param("i", $packageID);
    if ($stmt->execute()) {
        $message = 'Room type deleted successfully!';
        $messageType = 'success';
    } else {
        $message = 'Error deleting room type: ' . $stmt->error;
        $messageType = 'error';
    }
    $stmt->close();
}

// Fetch existing room packages
$rooms = [];
$result = $conn->query("SELECT packageID, packageName, description, pricePerHour FROM packages ORDER BY packageName ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
} else {
    $message = 'Error loading room types: ' . $conn->error;
    $messageType = 'error';
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Karaoke Rooms</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .message-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 1000;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center p-4">
    <div id="messageContainer" class="message-container">
        <?php if ($message): ?>
            <div class="p-3 rounded-lg <?php echo $messageType === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?> shadow-md">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-3xl mt-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Manage Karaoke Rooms</h1>

        <div class="mb-8 p-6 bg-gray-50 rounded-lg border border-gray-200">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Add New Room Type</h2>
            <form method="POST" class="space-y-4">
                <input type="hidden" name="add_room" value="1">
                <div>
                    <label for="new_room_name" class="block text-sm font-medium text-gray-700 mb-1">Room Name</label>
                    <input type="text" id="new_room_name" name="new_room_name" placeholder="e.g., Party Room" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                </div>
                <div>
                    <label for="new_room_price" class="block text-sm font-medium text-gray-700 mb-1">Price per Hour (RM)</label>
                    <input type="number" id="new_room_price" name="new_room_price" step="0.50" min="0" placeholder="e.g., 25.00" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                </div>
                <div>
                    <label for="new_room_capacity" class="block text-sm font-medium text-gray-700 mb-1">Capacity (Persons)</label>
                    <input type="number" id="new_room_capacity" name="new_room_capacity" min="1" placeholder="e.g., 10" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                </div>
                <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-lg text-lg font-semibold hover:bg-green-700 transition duration-300 shadow-md">
                    Add Room Type
                </button>
            </form>
        </div>

        <div class="mb-8 p-6 bg-white rounded-lg border border-gray-200">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Existing Room Types</h2>
            <div class="space-y-4">
                <?php if (empty($rooms)): ?>
                    <p class="text-center text-gray-500">No room types found.</p>
                <?php else: ?>
                    <?php foreach ($rooms as $room): ?>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg shadow-sm">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900"><?php echo htmlspecialchars($room['packageName']); ?></h3>
                                <p class="text-sm text-gray-600">Price: RM <?php echo htmlspecialchars(number_format($room['pricePerHour'], 2)); ?>/hour</p>
                                <p class="text-sm text-gray-600">Description: <?php echo htmlspecialchars($room['description']); ?></p>
                            </div>
                            <div class="flex space-x-2">
                                <button onclick="showEditForm(<?php echo htmlspecialchars(json_encode($room)); ?>)" class="bg-yellow-500 text-white py-1 px-3 rounded-md text-sm hover:bg-yellow-600 transition duration-300">Edit</button>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this room type?');">
                                    <input type="hidden" name="delete_room" value="1">
                                    <input type="hidden" name="delete_package_id" value="<?php echo htmlspecialchars($room['packageID']); ?>">
                                    <button type="submit" class="bg-red-500 text-white py-1 px-3 rounded-md text-sm hover:bg-red-600 transition duration-300">Delete</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div id="editRoomFormContainer" class="mb-8 p-6 bg-blue-50 rounded-lg border border-blue-200 hidden">
            <h2 class="text-2xl font-semibold text-blue-800 mb-4">Edit Room Type</h2>
            <form method="POST" class="space-y-4">
                <input type="hidden" name="edit_room" value="1">
                <input type="hidden" id="edit_package_id" name="edit_package_id">
                <div>
                    <label for="edit_room_name" class="block text-sm font-medium text-gray-700 mb-1">Room Name</label>
                    <input type="text" id="edit_room_name" name="edit_room_name" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                </div>
                <div>
                    <label for="edit_room_price" class="block text-sm font-medium text-gray-700 mb-1">Price per Hour (RM)</label>
                    <input type="number" id="edit_room_price" name="edit_room_price" step="0.50" min="0" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                </div>
                <div>
                    <label for="edit_room_capacity" class="block text-sm font-medium text-gray-700 mb-1">Capacity (Persons)</label>
                    <input type="number" id="edit_room_capacity" name="edit_room_capacity" min="1" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideEditForm()" class="bg-gray-300 text-gray-800 py-2 px-4 rounded-lg text-md font-semibold hover:bg-gray-400 transition duration-300">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-lg text-md font-semibold hover:bg-blue-700 transition duration-300">Save Changes</button>
                </div>
            </form>
        </div>

        <p class="mt-6 text-center text-sm text-gray-600">
            <a href="admin_dashboard.php" class="font-medium text-gray-600 hover:text-gray-800">Back to Admin Dashboard</a>
        </p>
    </div>

    <script>
        // Function to show the edit form and populate it with data
        function showEditForm(roomData) {
            document.getElementById('edit_package_id').value = roomData.packageID;
            document.getElementById('edit_room_name').value = roomData.packageName;
            document.getElementById('edit_room_price').value = parseFloat(roomData.pricePerHour);
            // Assuming capacity is part of description or a separate field in DB.
            // For now, let's parse from description if possible, or add a hidden input for it.
            // If capacity is not in packages table, you'd need to fetch it from 'rooms' table.
            // For simplicity, I'll assume capacity is derived from description or a fixed value for package types.
            // If capacity is not explicitly stored in 'packages', you might need to adjust your DB schema or logic.
            // For now, I'll just put a placeholder or try to parse it if available in description.
            const capacityMatch = roomData.description ? roomData.description.match(/Up to (\d+) people/) : null;
            document.getElementById('edit_room_capacity').value = capacityMatch ? parseInt(capacityMatch[1]) : '';

            document.getElementById('editRoomFormContainer').classList.remove('hidden');
            // Scroll to the edit form
            document.getElementById('editRoomFormContainer').scrollIntoView({ behavior: 'smooth' });
        }

        // Function to hide the edit form
        function hideEditForm() {
            document.getElementById('editRoomFormContainer').classList.add('hidden');
        }

        // Auto-hide messages after a few seconds
        document.addEventListener('DOMContentLoaded', function() {
            const messageContainer = document.getElementById('messageContainer');
            if (messageContainer.children.length > 0) {
                setTimeout(() => {
                    messageContainer.classList.add('hidden');
                }, 5000); // Hide after 5 seconds
            }
        });
    </script>
</body>
</html>
