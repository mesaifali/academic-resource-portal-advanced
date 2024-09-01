<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

include '../includes/db.php';

$user_id = $_SESSION['user_id'];

if (!isset($_POST['event_id'])) {
    echo json_encode(['success' => false, 'message' => 'Event ID not provided']);
    exit();
}

$event_id = intval($_POST['event_id']);

// Check if the user is registered for this event
$sql_check_registration = "SELECT * FROM event_registrations 
                           WHERE event_id = $event_id AND user_id = $user_id";
$result_check = $conn->query($sql_check_registration);

if ($result_check->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'You are not registered for this event']);
    exit();
}

// Process cancellation
$sql_cancel = "DELETE FROM event_registrations 
               WHERE event_id = $event_id AND user_id = $user_id";

if ($conn->query($sql_cancel) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Registration cancelled successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error cancelling registration']);
}

$conn->close();
?>