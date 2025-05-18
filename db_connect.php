<?php
/**
 * Database Connection File
 * Establishes connection to the Karaoke Reservation System database
 */

// Database credentials
$db_host = "localhost";      // Usually localhost
$db_name = "karaoke_db";     // Database name
$db_user = "root";           // Your database username
$db_pass = "";               // Your database password

// Create connection
try {
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode to associative array
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // Set charset
    $conn->exec("SET NAMES utf8");
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

/**
 * Simple function to sanitize input data
 * 
 * @param string $data Data to be sanitized
 * @return string Sanitized data
 */
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>