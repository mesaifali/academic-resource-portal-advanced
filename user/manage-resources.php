<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.php");
    exit();
}
include '../includes/db.php';
include '../includes/version.php';

$user_id = $_SESSION['user_id'];

if (isset($_GET['delete'])) {
    $resource_id = $_GET['delete'];
    $sql_delete = "DELETE FROM resources WHERE id='$resource_id' AND user_id='$user_id'";
    $conn->query($sql_delete);
    header("Location: manage-resources.php");
}

$sql_user_resources = "SELECT * FROM resources WHERE user_id='$user_id'";
$result_user_resources = $conn->query($sql_user_resources);
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="    https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
       <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="../assets/user_sidebar/sidebar.css?v=<?php echo $version; ?>">
    <title>Manage Resources - Academic Resource Portal</title>
</head>
<body>
<?php include '../assets/user_sidebar/sidebar.php';?>

    <div class="dashboard-container">
        <!-- <h2>Manage Resources</h2> -->
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
            <td><?php echo ucfirst($resource['download_count']); ?></td>
            <td>
                <a href="edit-resource.php?id=<?php echo $resource['id']; ?>" class="edit-btn" style="  border-right: 1px solid #8d94e6;
                  padding: 10px;"><i class="fa-regular fa-pen-to-square"></i>Edit</a>
                <a href="manage-resources.php?delete=<?php echo $resource['id']; ?>" class="delete-btn"><i class="fa-regular fa-trash-can"></i>Delete</a>
            </td>
        </tr>
    <?php } ?>
</tbody>
        </table>
    </div>
        <script src="../assets/user_sidebar/sidebar.js"></script>
</body>
</html>

