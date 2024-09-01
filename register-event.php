<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_id = $_POST['event_id'];
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO event_registrations (event_id, user_id) VALUES ('$event_id', '$user_id')";

    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php?event_registered=success");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
