<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php");
    exit();
}
include '../includes/db.php';

$event_id = $_GET['id'];

$sql = "UPDATE events SET status = 'new' WHERE id = $event_id";
if ($conn->query($sql) === TRUE) {
    header("Location: manage-events.php?message=Event set as new successfully");
} else {
    header("Location: manage-events.php?error=Error setting event as new");
}

$conn->close();
?>