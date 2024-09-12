<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php");
    exit();
}
include '../includes/db.php';
include '../includes/version.php';

if (!isset($_GET['id'])) {
    header("Location: manage-events.php");
    exit();
}

$event_id = intval($_GET['id']);

// Fetch event details
$sql_event = "SELECT title, event_date, type, location FROM events WHERE id = $event_id";
$result_event = $conn->query($sql_event);
$event = $result_event->fetch_assoc();

if (!$event) {
    header("Location: manage-events.php");
    exit();
}

// Fetch registered users for this event
$sql_registrations = "SELECT u.name, u.id, u.username, u.email, u.phone, er.registration_date 
                      FROM event_registrations er 
                      JOIN users u ON er.user_id = u.id 
                      WHERE er.event_id = $event_id 
                      ORDER BY er.registration_date DESC";
$result_registrations = $conn->query($sql_registrations);

$total_registrations = $result_registrations->num_rows;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="../assets/admin_sidebar/sidebar.css?v=<?php echo $version; ?>">
    <title>Event Registrations - Academic Resource Portal</title>
</head>
<body>
    <?php include '../assets/admin_sidebar/sidebar.php'; ?>
    <div class="dashboard-container">
     <div class="manage-event-section">  
       
        <h2>Event Registrations</h2>
        
        <div class="event-details-section">
        <h3 style="color:#FFDE21;"> Overview </h3>
        <hr class="dashed">
            <h3 style="padding-top: 10px;"><?php echo htmlspecialchars($event['title']); ?></h3>
            <p><strong>Date:</strong> <?php echo date('M d, Y', strtotime($event['event_date'])); ?></p>
            <p><strong>Type:</strong> <?php echo ucfirst($event['type']); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
            <p><strong>Total Registrations:</strong> <?php echo $total_registrations; ?></p>
        </div>
</div>

        <table class="registrations-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Registration Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($total_registrations > 0): ?>
                    <?php while($registration = $result_registrations->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($registration['username']); ?></td>
                            <td><?php echo htmlspecialchars($registration['name']); ?></td>
                            <td><?php echo htmlspecialchars($registration['email']); ?></td>
                            <td><?php echo htmlspecialchars($registration['phone']); ?></td>
                            <td><?php echo date('M d, Y H:i', strtotime($registration['registration_date'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No registrations for this event yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

<form method="get" action="export-event-users.php">
    <input type="hidden" name="id" value="<?php echo $event_id; ?>">
    <button type="submit" class="admin-btn">Export Registrations to CSV</button>
</form>

        <a href="manage-events.php" class="dwn-btn">Back to Manage Events</a>
    </div>
    <script src="../assets/admin_sidebar/sidebar.php"></script>
</body>
</html>