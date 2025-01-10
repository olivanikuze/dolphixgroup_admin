

// <?php
// Database connection
//nclude('../config/db_connect.php');
// Get message from POST
//$message = $_POST['message'];
//$user_id = 1; // Example user ID (update dynamically based on the logged-in user)
//$admin_id = 1; // Example admin ID (update dynamically based on the logged-in admin)
//$is_admin = isset($_POST['is_admin']) ? $_POST['is_admin'] : 0; // 0 for client, 1 for admin
//$read_status = 0; // Default to unread

//$message = isset($_POST['message']) ? $_POST['message'] : null;
//if (!$message) {
    //die("Message is required.");
//}
//$message = htmlspecialchars($message); // Sanitize input to prevent XSS



// Insert into database
//$stmt = $conn->prepare("INSERT INTO chat_messages (user_id, admin_id, message, is_admin, read_status) VALUES (?, ?, ?, ?, ?, ?)");
//$stmt->bind_param("iisis", $user_id, $admin_id, $message, $is_admin, $read_status, $attributes);
//$stmt->execute();
//$stmt->close();

//$conn->close();

//$user_id = 1; // Example user ID
//$admin_id = 1; // Example admin ID

// $stmt = $conn->prepare("SELECT * FROM chat_messages WHERE user_id = ? AND admin_id = ? ORDER BY created_at ASC");
// $stmt->bind_param("ii", $user_id, $admin_id);
// $stmt->execute();
// $result = $stmt->get_result();

// while ($row = $result->fetch_assoc()) {
//     $sender = $row['is_admin'] ? "Admin" : "User";
//     echo "<p><strong>{$sender}:</strong> {$row['message']} <small>({$row['created_at']})</small></p>";
// }

// $stmt->close();
// $conn->close();
// ?>


<?php
// Include database connection
include('../config/db_connect.php');

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

// Validate 'message' input
if (!isset($_POST['message']) || empty(trim($_POST['message']))) {
    die("Message is required.");
}
$message = htmlspecialchars($_POST['message']); // Sanitize input

// Other variables
$user_id = 1; // Example user ID
$admin_id = 1; // Example admin ID
$is_admin = isset($_POST['is_admin']) ? $_POST['is_admin'] : 0; // 0 for client, 1 for admin
$read_status = 0; // Default to unread

// Check database connection
if (!$conn) {
    die("Database connection error.");
}

// Insert into database
$stmt = $conn->prepare("INSERT INTO chat_messages (user_id, admin_id, message, is_admin, read_status) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iisii", $user_id, $admin_id, $message, $is_admin, $read_status);

if ($stmt->execute()) {
    echo "Message inserted successfully.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Chat Support</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        #chat-box {
            width: 100%;
            height: 300px;
            border: 1px solid #ccc;
            overflow-y: auto;
            padding: 10px;
            background-color: #f9f9f9;
        }
        #message-box {
            width: calc(100% - 80px);
            padding: 10px;
            border: 1px solid #ccc;
            margin-right: 10px;
            border-radius: 5px;
        }
        #send-btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #send-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>Live Chat Support</h2>
    <div id="chat-box"></div>
    <div style="margin-top: 10px; display: flex;">
        <input type="text" id="message-box" placeholder="Type a message">
        <button id="send-btn">Send</button>
    </div>
    <script>
        const chatBox = document.getElementById("chat-box");
        const messageBox = document.getElementById("message-box");
        const sendBtn = document.getElementById("send-btn");

        // Fetch messages every 2 seconds
        setInterval(() => {
            fetch("fetch_messages.php")
                .then(response => response.text())
                .then(data => {
                    chatBox.innerHTML = data;
                    chatBox.scrollTop = chatBox.scrollHeight; // Scroll to bottom
                });
        }, 2000);

        // Send message
        sendBtn.addEventListener("click", () => {
            const message = messageBox.value.trim();
            if (message) {
                fetch("send_message.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `message=${encodeURIComponent(message)}`
                }).then(() => {
                    messageBox.value = "";
                });
            }
        });
    </script>
</body>
</html>
