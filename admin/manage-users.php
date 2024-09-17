<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php");
    exit();
}
include '../includes/db.php';
include '../includes/version.php';

if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    $sql_delete = "DELETE FROM users WHERE id='$user_id'";
    $conn->query($sql_delete);
    header("Location: manage-users.php");
}

$sql_users = "SELECT * FROM users";
$result_users = $conn->query($sql_users);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="../assets/admin_sidebar/sidebar.css?v=<?php echo $version; ?>">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <title>Manage Users - Academic Resource Portal</title>
</head>
<body>
<?php include '../assets/admin_sidebar/sidebar.php';?>
    <div class="dashboard-container">
        <h2>Manage Users</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $result_users->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['name']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['username']; ?></td>
                        <td>
<a href="edit-user.php?id=<?php echo $user['id']; ?>"class="edit-btn" style="  border-right: 1px solid #8d94e6; padding: 10px;"><i class="fa-regular fa-pen-to-square"></i>Edit</a>
                        <a href="manage-users.php?delete=<?php echo htmlspecialchars($user['id']); ?>" onclick="return confirm('Are you sure you want to delete this user?');" class="delete-btn"><i class="fa-regular fa-trash-can"></i>Delete</a>
                         <!--   <a href="manage-users.php?delete=<?php echo $user['id']; ?>">Delete</a> -->
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <form method="get" action="export-users.php">
            <button type="submit" class="admin-btn">Export Users to CSV</button>
        </form>
    </div>
<script src="../assets/user_sidebar/sidebar.js"></script>
</body>
</html>