<?php
include('db_connect.php');

$stmt = $conn->prepare("SELECT * FROM chat_messages ORDER BY created_at ASC");
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
$stmt->close();
$conn->close();
?>
