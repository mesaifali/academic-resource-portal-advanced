<?php
session_start();
require_once '../includes/functions.php';

// Check if admin is logged in
checkAdminSession();

if (isset($_POST['delete_all_messages'])) {
    // Delete all messages
    $sql = "DELETE FROM messages";
    if ($conn->query($sql) === TRUE) {
        echo "All messages deleted successfully.";
    } else {
        echo "Error deleting messages: " . $conn->error;
    }
}
?>