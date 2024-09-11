<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../includes/functions.php';

// Check if user is logged in
checkUserSession();

$user_id = $_SESSION['user_id'];
$enrolled_courses = get_user_enrolled_courses($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses</title>
       <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="../assets/user_sidebar/sidebar.css?v=<?php echo $version; ?>">
    <link rel="icon" type="image/jpg" href="https://saifali.sirv.com/favicon/favicon-32x32.png">
</head>
<body>
<?php include '../assets/user_sidebar/sidebar.php';?>
    <div class="user-course-container">
    <h2>My Courses</h2>
        <div class="course-grid">
            <?php foreach ($enrolled_courses as $course): ?>
                <div class="course-card">
    <img src="<?php echo htmlspecialchars($course['thumbnail_url']); ?>" alt="<?php echo htmlspecialchars($course['title']); ?>" class="course-image">
    <div class="course-content">
        <h2 class="course-title"><?php echo htmlspecialchars($course['title']); ?></h2>
        <?php 
        $progress = get_course_progress($user_id, $course['id']);
        $progress_percentage = $progress['completed_chapters'] / $progress['total_chapters'] * 100;
        $is_completed = check_course_completion($user_id, $course['id']);
        ?>
        <div class="progress-bar-main">
            <div class="progress-num" style="width: <?php echo $progress_percentage; ?>%;"></div>
        </div>
        <p style="padding-top:8px;"><?php echo $progress['completed_chapters']; ?> / <?php echo $progress['total_chapters']; ?> chapters completed</p>
        <?php if ($is_completed): ?>
            <p class="completion-status">Completed</p>
        <?php else: ?>
            <a href="../view_course.php?id=<?php echo $course['id']; ?>" class="user-course-dash-btn">Continue Course</a>
        <?php endif; ?>
    </div>
</div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>