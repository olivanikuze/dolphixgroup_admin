<?php
// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'dolphix');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch items and messages
$items = $conn->query("SELECT * FROM items");
$messages = $conn->query("SELECT * FROM messages");
?>