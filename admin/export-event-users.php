<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['admin_id'])) {
    die("Not logged in as admin");
}

include '../includes/db.php';

if (!isset($_GET['id'])) {
    die("No event ID provided");
}

$event_id = intval($_GET['id']);

// Fetch event details
$sql_event = "SELECT title FROM events WHERE id = ?";
$stmt = $conn->prepare($sql_event);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result_event = $stmt->get_result();
$event = $result_event->fetch_assoc();

if (!$event) {
    die("Event not found");
}

// Fetch registered users for this event
$sql_registrations = "SELECT u.name, u.username, u.email, u.phone, er.registration_date 
                      FROM event_registrations er 
                      JOIN users u ON er.user_id = u.id 
                      WHERE er.event_id = ?
                      ORDER BY er.registration_date DESC";
$stmt = $conn->prepare($sql_registrations);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result_registrations = $stmt->get_result();

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $event['title'] . '_registrations.csv"');

// Open the output stream
$output = fopen('php://output', 'w');

// Add UTF-8 BOM for proper encoding in Excel
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Output the column headings
fputcsv($output, array('Username', 'Name', 'Email', 'Phone', 'Registration Date'));

// Loop over the rows, outputting them
while ($row = $result_registrations->fetch_assoc()) {
    fputcsv($output, array(
        $row['username'],
        $row['name'],
        $row['email'],
        $row['phone'],
        $row['registration_date']
    ));
}

fclose($output);
$conn->close();
exit();
?>