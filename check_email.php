<?php
include 'includes/db.php';

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    
    // Check email format and allowed domains
    $allowed_domains = ['gmail.com', 'outlook.com', 'hotmail.com', 'live.com'];
    $email_parts = explode('@', $email);
    if (count($email_parts) !== 2 || !in_array($email_parts[1], $allowed_domains)) {
        echo 'invalid';
        exit;
    }
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
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