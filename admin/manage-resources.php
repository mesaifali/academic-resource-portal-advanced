<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php");
    exit();
}
include '../includes/db.php';
include '../includes/version.php';

// Handle resource deletion
if (isset($_GET['delete'])) {
    $resource_id = intval($_GET['delete']);
    $sql_delete = "DELETE FROM resources WHERE id='$resource_id'";
    $conn->query($sql_delete);
    header("Location: manage-resources.php");
    exit();
}

// Fetch approved resources
$sql_resources = "SELECT * FROM resources WHERE status='approved'";
$result_resources = $conn->query($sql_resources);
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo $version; ?>">
        <link rel="stylesheet" href="../assets/admin_sidebar/sidebar.css?v=<?php echo $version; ?>">
            <link rel="stylesheet" href="    https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <title>Manage Resources - Academic Resource Portal</title>
</head>
<body>
<?php include '../assets/admin_sidebar/sidebar.php';?>
    <div class="dashboard-container">
        <h2>Manage Resources</h2>
        <table class="resources-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Description</th>
                    <!-- <th>File Name</th> -->
                    <th>Thumbnail</th>
                   <!-- <th>Total Download</th> -->

                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($resource = $result_resources->fetch_assoc()) { ?>
                    <tr>
                    <?php $thumbnail = 'uploads/thumbnail/' . htmlspecialchars($resource['thumbnail']); ?>
                        <td><?php echo htmlspecialchars($resource['id']); ?></td>
                        <td style="width:160px;"><?php echo htmlspecialchars($resource['title']); ?></td>
                        <td><?php echo ucfirst(htmlspecialchars($resource['type'])); ?></td>
                        <td style="width:330px;"><?php echo htmlspecialchars($resource['description']); ?></td>
                      <!--  <td><?php echo ucfirst(htmlspecialchars($resource['file_path'])); ?></td> -->

                        <td>
                            <?php if ($resource['thumbnail']) { ?>
        <img src="../uploads/thumbnail/<?php echo htmlspecialchars($resource['thumbnail']); ?>" alt="Thumbnail" style="width: 100px; height: 100px;">
                            <?php } else { ?>
                                No Thumbnail
                            <?php } ?>
                        </td>

                      <!-- <td><?php echo ucfirst(htmlspecialchars($resource['download_count'])); ?></td> -->

                        <td class="admin-action">
                        <!-- Direct download link for admins -->
<a href="../download.php?file=<?php echo urlencode($resource['file_path']); ?>&type=<?php echo urlencode($resource['type']); ?>" class="dw-btn"><i class="fa-solid fa-cloud-arrow-down"></i>Download</a>


                   <a href="edit-resource.php?id=<?php echo htmlspecialchars($resource['id']); ?>" class="edit-btn"><i class="fa-regular fa-pen-to-square"></i>Edit</a>

                            <a href="manage-resources.php?delete=<?php echo htmlspecialchars($resource['id']); ?>" onclick="return confirm('Are you sure you want to delete this resource?');" class="delete-btn"><i class="fa-regular fa-trash-can"></i>Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <script src="../assets/user_sidebar/sidebar.js"></script>
</body>
</html>
