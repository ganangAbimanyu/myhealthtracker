<?php
$host = 'localhost';
$dbname = 'myhealthtracker';
$username = 'root'; // Default for XAMPP
$password = '';     // Default for XAMPP

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
