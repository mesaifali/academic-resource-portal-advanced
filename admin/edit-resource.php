<?php

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php");
    exit();
}


include '../includes/db.php';
include '../includes/functions.php';
include '../includes/version.php';

// Initialize resource data
$resource_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$resource = null;
$popup_title = '';
$popup_message = '';
$show_popup = false;

if ($resource_id > 0) {
    $sql = "SELECT * FROM resources WHERE id = $resource_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $resource = $result->fetch_assoc();
    } else {
        echo 'Resource not found.';
        exit;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize form data
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $type = htmlspecialchars($_POST['type']);
    $file_path = htmlspecialchars($_POST['file_path']);
    $thumbnail = $resource['thumbnail'];

    // Handle thumbnail upload
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
        $target_dir = "../uploads/thumbnail/";
        $target_file = $target_dir . basename($_FILES["thumbnail"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["thumbnail"]["tmp_name"]);
        if($check !== false) {
            if ($_FILES["thumbnail"]["size"] <= 5000000) {
                if(in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
                    if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_file)) {
                        $thumbnail = htmlspecialchars(basename($_FILES["thumbnail"]["name"]));
                    } else {
                        $popup_title = 'Error';
                        $popup_message = 'Sorry, there was an error uploading your file.';
                        $show_popup = true;
                    }
                } else {
                    $popup_title = 'Error';
                    $popup_message = 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.';
                    $show_popup = true;
                }
            } else {
                $popup_title = 'Error';
                $popup_message = 'Sorry, your file is too large.';
                $show_popup = true;
            }
        } else {
            $popup_title = 'Error';
            $popup_message = 'File is not an image.';
            $show_popup = true;
        }
    }

    // Update resource in the database
    if (!$show_popup) {
        $sql = "UPDATE resources SET title='$title', description='$description', type='$type', thumbnail='$thumbnail' WHERE id=$resource_id";
        
        if ($conn->query($sql) === TRUE) {
            $popup_title = 'Success';
            $popup_message = 'Resource updated successfully.';
            $show_popup = true;
        } else {
            $popup_title = 'Error';
            $popup_message = 'Error updating resource: ' . $conn->error;
            $show_popup = true;
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="../assets/admin_sidebar/sidebar.css?v=<?php echo $version; ?>">
    <title>Edit Resource - Academic Resource Portal</title>
</head>
<body>
<?php include '../assets/admin_sidebar/sidebar.php';?>
    <main>
        <section class="edit-admin-container">
            <h2>Edit Resource</h2>
            <?php if ($resource) { ?>
                <form action="edit-resource.php?id=<?php echo $resource_id; ?>" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($resource['title']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($resource['description']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="type">Type:</label>
                        <select id="type" name="type" required>
                            <option value="book" <?php echo $resource['type'] == 'book' ? 'selected' : ''; ?>>Book</option>
                            <option value="notes" <?php echo $resource['type'] == 'notes' ? 'selected' : ''; ?>>Notes</option>
                            <option value="question" <?php echo $resource['type'] == 'question' ? 'selected' : ''; ?>>Question</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="thumbnail">Thumbnail:</label>
                        <input type="file" id="thumbnail" name="thumbnail" accept="image/*">
                        <p>Current thumbnail: <?php echo htmlspecialchars($resource['thumbnail']); ?></p>
                    </div>

                    <button type="submit" class="dash-btn">Update Resource</button>
                </form>
            <?php } else { ?>
                <p>Resource not found.</p>
            <?php } ?>
        </section>
    </main>

    <?php if ($show_popup) { ?>
    <div id="popup" class="popup" style="display: flex;">
        <div class="popup-content">
            <span class="close-btn" onclick="closePopup()">&times;</span>
            <h2 id="popup-title"><?php echo $popup_title; ?></h2>
            <p id="popup-message"><?php echo $popup_message; ?></p>
            <button class="dash-btn" onclick="redirect()">OK</button>
        </div>
    </div>
    <?php } ?>

    <script src="../assets/user_sidebar/sidebar.js"></script>
    <script src="../assets/js/popup.js"></script>
</body>
</html>
