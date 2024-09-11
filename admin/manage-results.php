<?php
session_start();
require_once '../includes/functions.php';
checkAdminSession();

$error = '';
$success = '';

if (isset($_POST['delete_result'])) {
    $result_id = (int)$_POST['result_id'];
    if (delete_result($result_id)) {
        $success = "Result deleted successfully.";
    } else {
        $error = "Error deleting result.";
    }
}

$results = get_all_results();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Results</title>
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="../assets/admin_sidebar/sidebar.css?v=<?php echo $version; ?>">
</head>
<body>
    <?php include '../assets/admin_sidebar/sidebar.php'; ?>

    <div class="dashboard-container">
        <h2 onclick="history.back()"><i class="fa-solid fa-arrow-left"></i><a href="#">Manage Results</a></h2>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Faculty</th>
                    <th>Semester</th>
                    <th>Batch Year</th>
                    <th>Result Date</th>
                    <th>Input Method</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $result): ?>
                    <tr>
                        <td><?php echo $result['id']; ?></td>
                        <td><?php echo htmlspecialchars(get_faculty_name($result['faculty_id'])); ?></td>
                        <td><?php echo htmlspecialchars(get_semester_name($result['semester_id'])); ?></td>
                        <td><?php echo $result['batch_year']; ?></td>
                        <td><?php echo $result['result_date']; ?></td>
                        <td><?php echo ucfirst($result['input_method']); ?></td>
                        <td>
                            <form action="" method="post" style="display: inline;">
                                <input type="hidden" name="result_id" value="<?php echo $result['id']; ?>">
                                <button type="submit" name="delete_result" class="btn-delete" onclick="return confirm('Are you sure you want to delete this result?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>