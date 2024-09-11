<?php
session_start();
require_once '../includes/functions.php';

checkAdminSession();

$courses = get_all_courses();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Enrollments</title>
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="../assets/admin_sidebar/sidebar.css?v=<?php echo $version; ?>">
    </head>
<body>
<?php include '../assets/admin_sidebar/sidebar.php';?>

 <div class="dashboard-container">
 <h2 onclick="history.back()"><i class="fa-solid fa-arrow-left"></i><a href="#">Course Enrollments</a></h2>
        <?php if (empty($courses)): ?>
            <p>No courses available.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Course ID</th>
                        <th>Course Title</th>
                        <th>Total Enrollments</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                        <?php $enrollment_count = get_course_enrollment_count($course['id']); ?>
                        <tr>
                            <td><?php echo $course['id']; ?></td>
                            <td><?php echo htmlspecialchars($course['title']); ?></td>
                            <td><?php echo $enrollment_count; ?></td>
                            <td>
                                <a href="course-users.php?id=<?php echo $course['id']; ?>">View Enrolled Users</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</body>
</html>