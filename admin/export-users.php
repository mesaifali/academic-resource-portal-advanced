<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php");
    exit();
}
require_once '../includes/db.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=users.csv');

$output = fopen('php://output', 'w');

// Column headers
fputcsv($output, array('ID', 'Name', 'Username', 'Email', 'Phone', 'Created Date', 'Profile Picture'));

$sql = "SELECT id, name, username, email, phone, created_at, profile_picture FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
}

fclose($output);
?>