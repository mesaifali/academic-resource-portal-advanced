<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php");
    exit();
}
include '../includes/db.php';
include '../includes/version.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $type = $_POST['type'];
    $event_date = $_POST['event_date'];
    $event_end_date = $_POST['event_end_date']; // New field
    $register_before = $_POST['register_before'];
    $location = $_POST['location'];
    $mode = $_POST['mode'];
    $fee = $_POST['fee'];
    $thumbnail = $_FILES['thumbnail']['name'];

    $thumb_dir = "../uploads/event_thumbnails/";
    $thumb_file = $thumb_dir . basename($thumbnail);
    move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $thumb_file);

    $sql = "INSERT INTO events (title, description, type, event_date, event_end_date, register_before, location, mode, fee, thumbnail) 
            VALUES ('$title', '$description', '$type', '$event_date', '$event_end_date', '$register_before', '$location', '$mode', '$fee', '$thumbnail')";

    if ($conn->query($sql) === TRUE) {
        header("Location: manage-events.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="../assets/admin_sidebar/sidebar.css?v=<?php echo $version; ?>">
    <title>Add Event - Academic Resource Portal</title>
</head>
<body>
<?php include '../assets/admin_sidebar/sidebar.php';?>
     <div class="upload-container">
        <form action="add-event.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Event Title</label>
                <input type="text" id="title" name="title" maxlength="60" required>
                <div id="titleMessage" class="max-length-message">You've reached the maximum length of 60 characters!</div>
            </div>
            <div class="form-group">
                <label for="description">Event Description</label>
                <textarea id="description" name="description" maxlength="250" required></textarea>
                <div id="descriptionMessage" class="max-length-message">You've reached the maximum length of 250 characters!</div>
            </div>
            <div class="form-group">
                <label for="type">Event Type</label>
                <select id="type" name="type" required>
                    <option value="hackathon">Hackathon</option>
                    <option value="webinar">Webinar</option>
                    <option value="workshop">Workshop</option>
                </select>
            </div>
            <div class="form-group">
                <label for="register_before">Registration Deadline</label>
                <input type="date" id="register_before" name="register_before" required>
            </div>
            <div class="form-group">
                <label for="event_date">Event Start Date</label>
                <input type="date" id="event_date" name="event_date" required>
            </div>

            <div class="form-group">
              <label for="event_end_date">Event End Date</label>
              <input type="date" id="event_end_date" name="event_end_date" required>
            </div>
            
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" id="location" name="location" required>
            </div>
            <div class="form-group">
                <label for="mode">Mode</label>
                <select id="mode" name="mode" required>
                    <option value="physical">Physical</option>
                    <option value="digital">Digital</option>
                    <option value="hybrid">Hybrid</option>
                </select>
            </div>
            <div class="form-group">
                <label for="fee">Fee (0 for free events)</label>
                <input type="number" id="fee" name="fee" min="0" step="1" required>
            </div>
            <div class="form-group">
                <label for="thumbnail">Upload Thumbnail</label>
                <input type="file" id="thumbnail" name="thumbnail" required>
            </div>
            <button type="submit" class="dash-btn">Add Event</button>
        </form>
    </div>


       <script>
        function setupMaxLengthWarning(inputElement, messageElement) {
            inputElement.addEventListener('input', function() {
                if (this.value.length === parseInt(this.getAttribute('maxlength'))) {
                    messageElement.style.display = 'block';
                } else {
                    messageElement.style.display = 'none';
                }
            });
        }

        setupMaxLengthWarning(document.getElementById('title'), document.getElementById('titleMessage'));
        setupMaxLengthWarning(document.getElementById('description'), document.getElementById('descriptionMessage'));
    </script>

    <script src="assets/admin_sidebar/sidebar.js"></script>
</body>
</html>