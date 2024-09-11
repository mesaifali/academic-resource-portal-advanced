<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/db.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['message'])) {
    exit('Unauthorized');
}

$user_id = $_SESSION['user_id'];
$message = $_POST['message'];

// Fetch user information
$stmt = $conn->prepare("SELECT username, profile_picture FROM users WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$data = [
    'user_id' => $user_id,
    'username' => $user['username'],
    'profile_picture' => $user['profile_picture'],
    'message' => $message
];

// Save the message to your database
$stmt = $conn->prepare("INSERT INTO messages (user_id, message) VALUES (?, ?)");
$stmt->bind_param('is', $user_id, $message);
$stmt->execute();

$data['id'] = $conn->insert_id;  // Get the ID of the inserted message

echo json_encode($data);
?>