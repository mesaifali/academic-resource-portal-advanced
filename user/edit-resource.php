<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.php");
    exit();
}
include '../includes/db.php';
include '../includes/functions.php';
include '../includes/version.php';

// Initialize resource data
$resource_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'];
$resource = null;

if ($resource_id > 0) {
    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM resources WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $resource_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $resource = $result->fetch_assoc();
    } else {
        header("Location: ../error.php?message=" . urlencode("Resource not found or you don't have permission to edit it."));
        exit;
    }
    $stmt->close();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize form data
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $type = htmlspecialchars($_POST['type']);

    // Update resource in the database
    $sql = "UPDATE resources SET title = ?, description = ?, type = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $title, $description, $type, $resource_id, $user_id);
    
    if ($stmt->execute()) {
        $success_message = "Resource updated successfully.";
    } else {
        $error_message = "Error updating resource: " . $conn->error;
    }
    $stmt->close();
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
    <title>Edit Resource - Academic Resource Portal</title>
</head>
<body>
<?php include '../assets/user_sidebar/sidebar.php';?>
    <main>
        <section class="edit-container">
            <h2>Edit Resource</h2>
            <?php 
            if (isset($success_message)) {
                echo "<p class='success'>$success_message</p>";
            }
            if (isset($error_message)) {
                echo "<p class='error'>$error_message</p>";
            }
            if ($resource) { 
            ?>
                <form action="edit-resource.php?id=<?php echo $resource_id; ?>" method="POST">
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
                    <button type="submit" class="dash-btn">Update Resource</button>
                </form>
            <?php } else { ?>
                <p>Resource not found or you don't have permission to edit it.</p>
            <?php } ?>
        </section>
    </main>
    <script src="../assets/user_sidebar/sidebar.js"></script>
</body>
</html>