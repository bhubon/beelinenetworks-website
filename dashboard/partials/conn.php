<?php
$host = 'localhost';        // Database host
$db   = 'beelinenetworks';    // Your database name
$user = 'root';             // DB username (default for Laragon/XAMPP is 'root')
$pass = '';                 // DB password (empty by default in local environments)

$conn = new mysqli($host, $user, $pass, $db);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
