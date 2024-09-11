<?php
session_start();
require_once '../includes/functions.php';

// Check if admin is logged in
checkAdminSession();

// Fetch course statistics
$total_courses = get_total_courses();
$total_enrollments = get_total_enrollments();
$total_categories = get_total_categories();
$total_completions = get_total_completions();

// Fetch recent courses (latest 5)
$recent_courses = get_recent_courses(5);

// Fetch course category data for the pie chart
$category_data = get_courses_by_category();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="../assets/admin_sidebar/sidebar.css?v=<?php echo $version; ?>">
</head>
<body>
    <?php include '../assets/admin_sidebar/sidebar.php';?>
    <div class="content">
        <div class="overview">
            <h2>Course Dashboard Overview</h2>
            <div class="course-button-container">
                <a href="create-courses.php" class="course-dash-btn">Create Course</a>
                <a href="manage-courses.php" class="course-dash-btn">Manage Courses</a>
                <a href="course-enrollments.php" class="course-dash-btn">Course Enrollments</a>
                <a href="manage-categories.php" class="course-dash-btn">Manage Categories</a>
            </div>
            <div class="course-main-content">
                <div class="activities">
                    <h2>Total Courses</h2>
                    <p><?php echo $total_courses; ?></p>
                </div>
                <div class="activities">
                    <h2>Total Enrollments</h2>
                    <p><?php echo $total_enrollments; ?></p>
                </div>
                <div class="activities">
                    <h2>Total Categories</h2>
                    <p><?php echo $total_categories; ?></p>
                </div>
                <div class="activities">
                    <h2>Course Completions</h2>
                    <p><?php echo $total_completions; ?></p>
                </div>
            </div>
        </div>
      
    </div>
</body>
</html>