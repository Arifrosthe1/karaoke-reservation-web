<?php
session_start();
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $cusNophone = $_POST['cusNophone'];

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, cusNophone, role, created_at) VALUES (?, ?, ?, ?, 'user', NOW())");
    $stmt->bind_param("ssss", $username, $email, $password, $cusNophone);

    if ($stmt->execute()) {
        header("Location: login.php?registered=1");
    } else {
        echo "Registration failed.";
    }
}
?>
<!-- HTML Form -->
<!DOCTYPE html>
<html>
<head><title>Register</title></head>
<body>
<h2>Register</h2>
<form method="post">
  <input type="text" name="username" placeholder="Username" required><br>
  <input type="email" name="email" placeholder="Email" required><br>
  <input type="password" name="password" placeholder="Password" required><br>
  <input type="text" name="cusNophone" placeholder="Phone Number" required><br>
  <button type="submit">Register</button>
</form>
<a href="login.php">Login</a>
</body>
</html>