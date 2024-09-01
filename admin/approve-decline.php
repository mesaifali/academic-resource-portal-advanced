<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php");
    exit();
}
include '../includes/db.php';
include '../includes/version';

if (isset($_GET['approve'])) {
    $resource_id = $_GET['approve'];
    $sql_approve = "UPDATE resources SET status='approved' WHERE id='$resource_id'";
    $conn->query($sql_approve);
    header("Location: approve-decline.php");
}

if (isset($_GET['decline'])) {
    $resource_id = $_GET['decline'];
    $sql_decline = "UPDATE resources SET status='declined' WHERE id='$resource_id'";
    $conn->query($sql_decline);
    header("Location: approve-decline.php");
}

$sql_pending_resources = "SELECT * FROM resources WHERE status='pending'";
$result_pending_resources = $conn->query($sql_pending_resources);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="../assets/admin_sidebar/sidebar.css?v=<?php echo $version; ?>">
    <title>Approve/Decline Resources - Academic Resource Portal</title>
</head>
<body>
<?php include '../assets/admin_sidebar/sidebar.php';?>
    <div class="dashboard-container">
        <h2>Approve/Decline Resources</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Thumbnail</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($resource = $result_pending_resources->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $resource['id']; ?></td>
                        <td><?php echo $resource['title']; ?></td>
                        
                        <td><?php if ($resource['thumbnail']) { ?>
        <img src="../uploads/thumbnail/<?php echo htmlspecialchars($resource['thumbnail']); ?>" alt="Thumbnail" style="width: 60px; height: 60px;"> <?php } else { ?> No Thumbnail <?php } ?>
                        </td>

                        <td><?php echo ucfirst($resource['type']); ?></td>
                        
                        <td>
                        <a href="approve-decline.php?approve=<?php echo $resource['id']; ?>"class="edit-btn" style="  border-right: 1px solid #8d94e6; padding: 10px;"><i class="fa-regular fa-circle-check"></i>Approve</a>
                            <a href="approve-decline.php?decline=<?php echo $resource['id']; ?>"class="delete-btn"><i class="fa-regular fa-circle-xmark"></i>Decline</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <script src="../assets/user_sidebar/sidebar.js"></script>
</body>
</html>


                    