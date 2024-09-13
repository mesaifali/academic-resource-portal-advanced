<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.php");
    exit();
}
include '../includes/db.php';
include '../includes/version.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $type = $_POST['type'];

    // File upload for resource
    $allowed_file_types = ['pdf', 'docx', 'csv', 'txt', 'xlsx', 'xls'];
    $file_ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

    if (!in_array($file_ext, $allowed_file_types)) {
        echo "Invalid file type for resource. Only PDF, DOCX, CSV, TXT, Excel files are allowed.";
        exit();
    }

    $file = $_FILES['file']['name'];
    $target_dir = "../uploads/$type/";
    $target_file = $target_dir . basename($file);
    if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        echo "Error uploading file.";
        exit();
    }

    // Thumbnail upload
    $allowed_thumb_types = ['jpg', 'jpeg', 'png', 'gif'];
    $thumb_ext = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));

    if (!in_array($thumb_ext, $allowed_thumb_types)) {
        echo "Invalid thumbnail type. Only JPG, JPEG, PNG, GIF files are allowed.";
        exit();
    }

    $thumbnail = $_FILES['thumbnail']['name'];
    $thumb_dir = "../uploads/thumbnail/";
    $thumb_file = $thumb_dir . basename($thumbnail);
    if (!move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $thumb_file)) {
        echo "Error uploading thumbnail.";
        exit();
    }

    // Insert data into the database
    $sql = "INSERT INTO resources (user_id, title, description, file_path, thumbnail, type) 
            VALUES ('$user_id', '$title', '$description', '$file', '$thumbnail', '$type')";

    if ($conn->query($sql) === TRUE) {
        header("Location: manage-resources.php");
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
    <link rel="stylesheet" href="../assets/user_sidebar/sidebar.css?v=<?php echo $version; ?>">
    <title>Upload Resource - Academic Resource Portal</title>
</head>
<body>
<?php include '../assets/user_sidebar/sidebar.php'; ?>
<div class="upload-container">
    <form action="upload-resource.php"  class="mobile-upload" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Resource Title</label>
            <input type="text" id="title" name="title" maxlength="90" placeholder="Resource Title" required>
            <div id="titleMessage" class="max-length-message">You've reached the maximum length of 90 characters!</div>
        </div>
        <div class="form-group">
            <label for="description">Resource Description</label>
            <textarea maxlength="250" id="description" name="description" placeholder="Resource Description" required></textarea>
            <div id="descriptionMessage" class="max-length-message">You've reached the maximum length of 250 characters!</div>
        </div>
        <div class="form-group">
            <label for="type">Resource Type</label>
            <select id="type" name="type" required>
                <option value="book">Book</option>
                <option value="note">Note</option>
                <option value="question">Question</option>
            </select>
        </div>
        <div class="form-group">
            <label for="file">Upload Resource File</label>
            <input type="file" id="file" name="file" accept=".pdf,.docx,.csv,.txt,.xlsx,.xls" required>
        </div>
        <div class="form-group">
            <label for="thumbnail">Upload Thumbnail</label>
            <input type="file" id="thumbnail" name="thumbnail" accept=".jpg,.jpeg,.png,.gif" required>
        </div>
        <button type="submit" class="dash-btn">Submit</button>
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

<script src="../assets/user_sidebar/sidebar.js"></script>

</body>
</html>
