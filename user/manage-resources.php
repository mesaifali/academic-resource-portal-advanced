<?php
session_start();
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.php");
    exit();
}
include '../includes/db.php';
include '../includes/version.php';

$user_id = $_SESSION['user_id'];

function safelyDeleteFile($filePath) {
    return !file_exists($filePath) || unlink($filePath);
}

if (isset($_GET['delete'])) {
    $resource_id = $_GET['delete'];
    
    // Use a single query to fetch all necessary data and delete the record
    $sql = "SELECT file_path, thumbnail, type FROM resources WHERE id=? AND user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $resource_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $file_path = $row['file_path'];
        $thumbnail = $row['thumbnail'];
        $type = $row['type'];
        
        $base_path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
        $full_file_path = $base_path . $type . '/' . $file_path;
        $full_thumbnail_path = $base_path . 'thumbnail/' . $thumbnail;
        
        // Delete files asynchronously
        $file_deleted = safelyDeleteFile($full_file_path);
        $thumbnail_deleted = safelyDeleteFile($full_thumbnail_path);
        
        // Delete from database immediately without waiting for file deletion
        $sql_delete = "DELETE FROM resources WHERE id=? AND user_id=?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("ii", $resource_id, $user_id);
        $stmt_delete->execute();
        
        if ($stmt_delete->affected_rows > 0) {
            $_SESSION['message'] = "Resource deleted from database. File deletion in progress.";
        } else {
            $_SESSION['message'] = "Error deleting resource from database.";
        }
        
        $stmt_delete->close();
    } else {
        $_SESSION['message'] = "Resource not found.";
    }
    
    $stmt->close();
    header("Location: manage-resources.php");
    exit();
}

// Fetch resources in a single query
$sql_user_resources = "SELECT id, title, type, status, download_count FROM resources WHERE user_id=?";
$stmt = $conn->prepare($sql_user_resources);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result_user_resources = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="../assets/user_sidebar/sidebar.css?v=<?php echo $version; ?>">
    <title>Manage Resources - Academic Resource Portal</title>
    <style>
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            background-color: #df3535;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
<?php include '../assets/user_sidebar/sidebar.php';?>

<div class="dashboard-container">
    <?php
    if (isset($_SESSION['message'])) {
        echo '<div class="message">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']);
    }
    ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Type</th>
                <th>Status</th>
                <th>Total Download</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($resource = $result_user_resources->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $resource['id']; ?></td>
                <td><?php echo $resource['title']; ?></td>
                <td><?php echo ucfirst($resource['type']); ?></td>
                <td class="<?php echo 'status-' . strtolower($resource['status']); ?>">
                    <?php echo ucfirst($resource['status']); ?>
                </td>
                <td><?php echo $resource['download_count']; ?></td>
                <td>
                    <a href="edit-resource.php?id=<?php echo $resource['id']; ?>" class="edit-btn" style="border-right: 1px solid #8d94e6; padding: 10px;"><i class="fa-regular fa-pen-to-square"></i>Edit</a>
                    <a href="manage-resources.php?delete=<?php echo $resource['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this resource?');"><i class="fa-regular fa-trash-can"></i>Delete</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<script src="../assets/user_sidebar/sidebar.js"></script>
</body>
</html>

<?php
$conn->close();
?>