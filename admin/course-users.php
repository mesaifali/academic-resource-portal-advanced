<?php
session_start();
require_once '../includes/functions.php';

checkAdminSession();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage-courses.php");
    exit();
}

$course_id = (int)$_GET['id'];
$course = get_course($course_id);

if (!$course) {
    header("Location: manage-courses.php");
    exit();
}

$enrolled_users = get_course_enrollments($course_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Users - <?php echo htmlspecialchars($course['title']); ?></title>
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="../assets/admin_sidebar/sidebar.css?v=<?php echo $version; ?>">
        <style>
        .progress-bar {
            width: 100%;
            background-color: #e0e0e0;
            padding: 3px;
            border-radius: 3px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, .2);
        }
        .progress-bar-fill {
            display: block;
            height: 22px;
            background-color: #659cef;
            border-radius: 3px;
            transition: width 500ms ease-in-out;
        }
    </style>
</head>
<body>
<?php include '../assets/admin_sidebar/sidebar.php';?>

 <div class="dashboard-container">
        <h2 onclick="history.back()"><i class="fa-solid fa-arrow-left"></i><a href="#">Users Enrolled in <?php echo htmlspecialchars($course['title']); ?></a></h2>
       <!-- <h1>Users Enrolled in <?php echo htmlspecialchars($course['title']); ?></h1> -->
        <?php if (empty($enrolled_users)): ?>
            <p>No users are currently enrolled in this course.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Enrolled Date</th>
                        <th>Completion Progress</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($enrolled_users as $user): ?>
                        <?php 
                        $progress = get_course_progress($user['id'], $course_id);
                        $progress_percentage = ($progress['total_chapters'] > 0) 
                            ? ($progress['completed_chapters'] / $progress['total_chapters']) * 100 
                            : 0;
                        ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo $user['enrolled_at']; ?></td>
                            <td>
                                <div class="progress-bar">
                                    <span class="progress-bar-fill" style="width: <?php echo $progress_percentage; ?>%;"></span>
                                </div>
                                <?php echo $progress['completed_chapters']; ?> / <?php echo $progress['total_chapters']; ?> chapters completed
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>