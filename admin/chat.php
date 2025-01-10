<?php
include('../config/db_connect.php');
// Fetch all messages

// $stmt = $conn->prepare("SELECT * FROM chat_messages ORDER BY created_at ASC");
// if ($stmt) {
//     $stmt->execute();
//     $messages = $stmt->get_result();
// } else {
//     die("Database query failed: " . $conn->error);
// }
$stmt = $pdo->prepare('SELECT * FROM chat_messages WHERE id = ?');
$stmt->execute();

// Handle message sending
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = htmlspecialchars($_POST['message']);
    $user_id = 1; // Example user ID
    $admin_id = 1; // Admin ID
    $is_admin = 1; // Admin message

    $stmt = $conn->prepare("INSERT INTO chat_messages (user_id, admin_id, message, is_admin, read_status) VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("iisii", $user_id, $admin_id, $message, $is_admin, $read_status = 0);

        if ($stmt->execute()) {
            echo "Message sent successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Chat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .chat-container {
            width: 100%;
            max-width: 600px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .chat-box {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
            border-bottom: 1px solid #ddd;
        }

        .chat-box .user {
            background: #d1e7fd;
            color: #333;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 10px;
            text-align: left;
        }

        .chat-box .admin {
            background: #ffefc1;
            color: #333;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 10px;
            text-align: right;
        }

        .chat-form {
            display: flex;
            padding: 10px;
            background: #f9f9f9;
        }

        .chat-form input[type="text"] {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }

        .chat-form button {
            background: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .chat-form button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="chat-box">
        <?php while ($row = $messages->fetch_assoc()): ?>
            <div class="<?= $row['is_admin'] ? 'admin' : 'user' ?>">
                <p><?= htmlspecialchars($row['message']) ?></p>
            </div>
        <?php endwhile; ?>
    </div>
    <form method="POST">
        <input type="text" name="message" placeholder="Type a message..." required>
        <button type="submit">Send</button>
    </form>
</body>
</html>
