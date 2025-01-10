<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="image/favicon.ico">
    <link rel="stylesheet" href="livechat.css">
    <title>DOLPHIX</title>
</head>
<body>
    <header id="navbar">
        <div class="logo"><a href="about.html">D
            <img src="image/favicon.ico" style="border-radius: 20px; ">LPHIX GROUP</a></div>
        <div class="search-bar">
            <input type="text" placeholder="Search..." class="input">
            <button id="Search" onclick="searchFunction()">Search</button>
            <button class="mode-toggle" onclick="toggleMode()">Dark/Light</button>
        </div>
        <div class="link">
            <ul>
                <li><a href="index.html">HOME</a></li>
                <li>
                    <a href="#">PRODUCT</a>
                    <div class="dropdown">
                        <a href="gaming.html">GAMING CENTER</a>
                        <a href="laptop.html">LAPTOP & TABLETS </a>
                        <a href="printer.html">PRINTER</a>
                        <a href="ups.html">UPS</a>
                        <a href="#">MAINTENANCE FACILITY</a>
                    </div>
                </li>
                <li><a href="about.html">ABOUT US</a></li>
                           </ul>
        </div>
    </header>
    <main>
        <div class="chat-container">
            <div class="chat-header">
                <h2> DYNAMIC CHAT</h2>
            </div>
            <div class="chat-box" id="chat-box">
                <!-- Messages will appear here -->
            </div>
            <div class="chat-input">
                <input type="text" id="message-input" placeholder="Type your message...">
                <button onclick="sendMessage()">Send</button>
            </div>
        </div>
    </main>
   
    <footer id="end">
        <div class="bottom_link">
            <a href="#">Back To Top</a>
        </div>
        <div class="socialMedia">
            <a href="https://www.youtube.com/watch?v=qJQJ1_1u-cQ" target="_blank"><img src="image/yut-Photoroom.jpg" alt="Youtube" width="30px" ></a>
            <a href="#"><img src="image/what-Photoroom.jpg" alt="WhatApp" width="30px"></a>
            <a href="#"><img src="image/inst-Photoroom.jpg" alt="Instagram" width="30px"></a>
        </div>
        <div class="para">
            <p>&copy; 2024 Gaming Team. All Rights Reserved.</p>
        </div>
    </footer>
    <script>
       const chatBox = document.getElementById('chat-box');

function fetchMessages() {
    fetch('../config/fetchM.php')
        .then(response => response.json())
        .then(messages => {
            chatBox.innerHTML = ''; // Clear the chat box
            messages.forEach(msg => {
                const messageDiv = document.createElement('div');
                messageDiv.className = `chat-message ${msg.is_admin ? 'admin' : 'user'}`;
                messageDiv.textContent = msg.message;
                chatBox.appendChild(messageDiv);
            });
            chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll
        });
}

function sendMessage() {
    const messageInput = document.getElementById('message-input');
    const message = messageInput.value.trim();

    if (message) {
        fetch('send_message.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message })
        }).then(() => {
            messageInput.value = ''; // Clear input
            fetchMessages(); // Refresh chat
        });
    }
}

// Fetch messages every 2 seconds
setInterval(fetchMessages, 2000);


    </script>
</body>
</html>
<?php
// Include database connection
include('../config/db_connect.php');
include('../config/fetchM.php');
// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

$data = json_decode(file_get_contents('php://input'), true);
$message = htmlspecialchars($data['message']);
$user_id = 1; // Example user ID
$admin_id = 1; // Example admin ID
$is_admin = 0; // Client message

$stmt = $conn->prepare("INSERT INTO chat_messages (user_id, admin_id, message, is_admin, read_status) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iisii", $user_id, $admin_id, $message, $is_admin, $read_status = 0);

if ($stmt->execute()) {
    echo "Message sent successfully.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();


?>
