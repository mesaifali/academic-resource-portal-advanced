<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    exit('Unauthorized');
}

$last_message_id = isset($_GET['last_message_id']) ? intval($_GET['last_message_id']) : 0;

$sql = "SELECT messages.id, users.username, users.profile_picture, messages.user_id, messages.message 
        FROM messages 
        JOIN users ON messages.user_id = users.id 
        WHERE messages.id > ? 
        ORDER BY messages.created_at ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $last_message_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
?>