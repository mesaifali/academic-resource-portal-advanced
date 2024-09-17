<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}
include '../includes/db.php';
include '../includes/version.php';

if (!isset($_GET['id'])) {
    header("Location: manage-events.php");
    exit();
}

$event_id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $type = $_POST['type'];
    $event_date = $_POST['event_date'];
       $event_end_date = $_POST['event_end_date'];
    $current_date = date('Y-m-d');
    $status = ($event_end_date < $current_date) ? 'archived' : 'active';
    $register_before = $_POST['register_before'];
    $location = $_POST['location'];
    $mode = $_POST['mode'];
    $fee = $_POST['fee'];

  $sql = "UPDATE events SET 
            title='$title', 
            description='$description', 
            type='$type', 
            event_date='$event_date', 
            event_end_date='$event_end_date', 
            register_before='$register_before', 
            location='$location', 
            mode='$mode', 
            fee='$fee',
            status='$status'
            WHERE id=$event_id";

    if ($_FILES['thumbnail']['name']) {
        $thumbnail = $_FILES['thumbnail']['name'];
        $thumb_dir = "../uploads/event_thumbnails/";
        $thumb_file = $thumb_dir . basename($thumbnail);
        move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $thumb_file);
        $sql = substr_replace($sql, ", thumbnail='$thumbnail'", -17, 0);
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: manage-events.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql_event = "SELECT * FROM events WHERE id=$event_id";
$result_event = $conn->query($sql_event);
$event = $result_event->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="../assets/admin_sidebar/sidebar.css?v=<?php echo $version; ?>">
    <title>Edit Event - Academic Resource Portal</title>
</head>
<body>
<?php include '../assets/admin_sidebar/sidebar.php';?>
    <div class="upload-container">
        <h2>Edit Event</h2>
        <form action="edit-event.php?id=<?php echo $event_id; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Event Title</label>
                <input type="text" id="title" name="title" maxlength="90" value="<?php echo htmlspecialchars($event['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Event Description</label>
                <textarea id="description" name="description" maxlength="500" required><?php echo htmlspecialchars($event['description']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="type">Event Type</label>
                <select id="type" name="type" required>
                    <option value="hackathon" <?php echo $event['type'] == 'hackathon' ? 'selected' : ''; ?>>Hackathon</option>
                    <option value="webinar" <?php echo $event['type'] == 'webinar' ? 'selected' : ''; ?>>Webinar</option>
                    <option value="workshop" <?php echo $event['type'] == 'workshop' ? 'selected' : ''; ?>>Workshop</option>
                </select>
            </div>
            <div class="form-group">
                <label for="register_before">Registration Deadline</label>
                <input type="date" id="register_before" name="register_before" value="<?php echo $event['register_before']; ?>" required>
            </div>
            <div class="form-group">
                <label for="event_date">Event Start Date</label>
                <input type="date" id="event_date" name="event_date" value="<?php echo $event['event_date']; ?>" required>
            </div>
            <div class="form-group">
    <label for="event_end_date">Event End Date</label>
    <input type="date" id="event_end_date" name="event_end_date" required>
</div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" required>
            </div>
            <div class="form-group">
                <label for="mode">Mode</label>
                <select id="mode" name="mode" required>
                    <option value="physical" <?php echo $event['mode'] == 'physical' ? 'selected' : ''; ?>>Physical</option>
                    <option value="digital" <?php echo $event['mode'] == 'digital' ? 'selected' : ''; ?>>Digital</option>
                    <option value="hybrid" <?php echo $event['mode'] == 'hybrid' ? 'selected' : ''; ?>>Hybrid</option>
                </select>
            </div>
            <div class="form-group">
                <label for="fee">Fee (0 for free events)</label>
                <input type="number" id="fee" name="fee" min="0" step="0.01" value="<?php echo $event['fee']; ?>" required>
            </div>
            <div class="form-group">
                <label for="thumbnail">Upload New Thumbnail <!--(optional)--></label>
                <input type="image" id="thumbnail" name="thumbnail">
            </div>
            <button type="submit" class="dash-btn">Update Event</button>
        </form>
    </div>
    <script src="assets/admin_sidebar/sidebar.js"></script>
</body>
</html>