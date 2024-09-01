<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php");
    exit();
}
include '../includes/db.php';
include '../includes/version.php';

// Auto archive events
$current_date = date('Y-m-d');
$sql_auto_archive = "UPDATE events SET status = 'archived' WHERE event_end_date < '$current_date' AND status != 'archived'";
$conn->query($sql_auto_archive);

// Handle event deletion
if (isset($_GET['delete'])) {
    $event_id = intval($_GET['delete']);
    
    // First, delete related registrations
    $sql_delete_registrations = "DELETE FROM event_registrations WHERE event_id = $event_id";
    $conn->query($sql_delete_registrations);
    
    // Then, delete the event
    $sql_delete = "DELETE FROM events WHERE id = $event_id";
    if ($conn->query($sql_delete) === TRUE) {
        $success_message = "Event and its registrations deleted successfully.";
    } else {
        $error_message = "Error deleting event: " . $conn->error;
    }
}

// Fetch all events
$sql_all_events = "SELECT id, title, event_date, event_end_date, type, thumbnail, status,
                   (SELECT COUNT(*) FROM event_registrations WHERE event_id = events.id) as registration_count 
                   FROM events 
                   ORDER BY event_date DESC";
$result_all_events = $conn->query($sql_all_events);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="../assets/admin_sidebar/sidebar.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Manage Events - Academic Resource Portal</title>
    <style>
        .thumbnail-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }
        .message {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
<?php include '../assets/admin_sidebar/sidebar.php';?>
<div class="dashboard-container">
    <div class="manage-event-section">
        <h2>Manage Events</h2>
        <a href="add-event.php" class="add-event-btn"><i class="fa-solid fa-plus"></i>Add New Event</a>
    </div>
    
    <?php if (isset($success_message)): ?>
        <div class="message success"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <?php if (isset($error_message)): ?>
        <div class="message error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <table class="resources-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Type</th>
                <th>Status</th>
                <th>Total Registered</th>
                <th>Thumbnail</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($event = $result_all_events->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($event['title']); ?></td>
                    <td><?php echo date('M d, Y', strtotime($event['event_date'])); ?></td>
                    <td><?php echo date('M d, Y', strtotime($event['event_end_date'])); ?></td>
                    <td><?php echo ucfirst($event['type']); ?></td>
                    <td><?php echo ucfirst($event['status']); ?></td>
                    <td><?php echo $event['registration_count']; ?></td>
                    <td>
                        <img src="../uploads/event_thumbnails/<?php echo htmlspecialchars($event['thumbnail']); ?>" 
                             alt="Event Thumbnail" 
                             class="thumbnail-preview">
                    </td>
                    <td class="admin-action">
                        <a href="event-registrations.php?id=<?php echo $event['id']; ?>" class="dw-btn"><i class="fa-regular fa-user"></i>Registrations</a>
                        <a href="edit-event.php?id=<?php echo $event['id']; ?>" class="edit-btn"><i class="fa-regular fa-pen-to-square"></i>Edit</a>
                        <a href="manage-events.php?delete=<?php echo $event['id']; ?>" onclick="return confirm('Are you sure you want to delete this event? This will also delete all related registrations.');" class="delete-btn"><i class="fa-regular fa-trash-can"></i>Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="../assets/admin_sidebar/sidebar.js"></script>
</body>
</html>