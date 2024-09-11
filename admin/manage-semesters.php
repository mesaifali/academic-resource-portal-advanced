<?php
session_start();
require_once '../includes/functions.php';
checkAdminSession();

if (isset($_POST['delete_semester'])) {
    $semester_id = (int)$_POST['semester_id'];
    if (delete_semester($semester_id)) {
        $success = "Semester deleted successfully.";
    } else {
        $error = "Error deleting semester.";
    }
}

$semesters = get_all_semesters();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Semesters</title>
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="../assets/admin_sidebar/sidebar.css?v=<?php echo $version; ?>">
</head>
<body>
    <?php include '../assets/admin_sidebar/sidebar.php'; ?>

    <div class="dashboard-container">
        <h2 onclick="history.back()"><i class="fa-solid fa-arrow-left"></i><a href="#">Manage Semesters</a></h2>
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
                    <th>Faculty</th>
                    <th>Semester</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($semesters as $semester): ?>
                    <tr>
                        <td><?php echo $semester['id']; ?></td>
                        <td><?php echo htmlspecialchars(get_faculty_name($semester['faculty_id'])); ?></td>
                        <td><?php echo htmlspecialchars($semester['name']); ?></td>
                        <td>
                            <form action="" method="post" style="display: inline;">
                                <input type="hidden" name="semester_id" value="<?php echo $semester['id']; ?>">
                                <button type="submit" name="delete_semester" class="btn-delete" onclick="return confirm('Are you sure you want to delete this semester? This will also delete all associated results.');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>