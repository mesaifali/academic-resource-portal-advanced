<?php
include 'includes/db.php';

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    
    // Check username format
    if (strlen($username) < 4 || !preg_match('/^[a-zA-Z0-9._]+$/', $username)) {
        echo 'invalid';
        exit;
    }
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo 'taken';
    } else {
        echo 'available';
    }
}

$conn->close();
?>