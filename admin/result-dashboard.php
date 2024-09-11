<?php
session_start();
require_once '../includes/functions.php';

// Check if admin is logged in
checkAdminSession();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="../assets/admin_sidebar/sidebar.css?v=<?php echo $version; ?>">
</head>
<body>
    <?php include '../assets/admin_sidebar/sidebar.php';?>
    <div class="result-content">
        <div class="overview">
            <h2>Result Dashboard Overview</h2>
            <div class="course-button-container">
                <a href="add_result.php" class="course-dash-btn">Add Result</a>
                <a href="manage-results.php" class="course-dash-btn">Manage Results</a>
                <a href="manage-faculties.php" class="course-dash-btn">Manage Faculties</a>
                <a href="manage-semesters.php" class="course-dash-btn">Manage Semesters</a>
            </div>
        </div>


    </div>
</body>
</html>