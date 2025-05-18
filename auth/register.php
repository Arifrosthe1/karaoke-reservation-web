<?php
require_once "../includes/db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = trim($_POST['phone']);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, cusNophone, role) VALUES (?, ?, ?, ?, 'user')");
    $stmt->bind_param("ssss", $username, $email, $password, $phone);
    
    if ($stmt->execute()) {
        header("Location: login.php?register=success");
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<form method="POST" class="container mt-5">
    <h3>User Registration</h3>
    <input type="text" name="username" class="form-control my-2" required placeholder="Username">
    <input type="email" name="email" class="form-control my-2" required placeholder="Email">
    <input type="text" name="phone" class="form-control my-2" required placeholder="Phone">
    <input type="password" name="password" class="form-control my-2" required placeholder="Password">
    <button class="btn btn-primary">Register</button>
</form>