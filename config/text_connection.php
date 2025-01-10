<?php
// Database configuration
$host = 'localhost'; // Replace with your database host
$dbname = 'admin'; // Replace with your database name
$username = 'root'; // Replace with your database username
$password = '12345678'; // Replace with your database password

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database connection successful!";
} catch (PDOException $e) {
    // Catch and display error
    echo "Database connection failed: " . $e->getMessage();
}
?>