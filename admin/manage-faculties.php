<?php
session_start();
require_once '../includes/functions.php';
checkAdminSession();

if (isset($_POST['delete_faculty'])) {
    $faculty_id = (int)$_POST['faculty_id'];
    if (delete_faculty($faculty_id)) {
        $success = "Faculty deleted successfully.";
    } else {
        $error = "Error deleting faculty.";
    }
}

$faculties = get_all_faculties();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Faculties</title>
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="../assets/admin_sidebar/sidebar.css?v=<?php echo $version; ?>">
</head>
<body>
    <?php include '../assets/admin_sidebar/sidebar.php'; ?>

    <div class="dashboard-container">
       <h2 onclick="history.back()"><i class="fa-solid fa-arrow-left"></i><a href="#">Manage Faculties</a></h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($faculties as $faculty): ?>
                    <tr>
                        <td><?php echo $faculty['id']; ?></td>
                        <td><?php echo htmlspecialchars($faculty['name']); ?></td>
                        <td>
                            <form action="" method="post" style="display: inline;">
                                <input type="hidden" name="faculty_id" value="<?php echo $faculty['id']; ?>">
                                <button type="submit" name="delete_faculty" class="btn-delete" onclick="return confirm('Are you sure you want to delete this faculty? This will also delete all associated semesters and results.');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>