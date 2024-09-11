<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/functions.php';
include 'include/version.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: courses.php");
    exit();
}

$course_id = (int)$_GET['id'];
$course = get_course($course_id);

if (!$course) {
    header("Location: courses.php");
    exit();
}

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$is_enrolled = $user_id ? is_user_enrolled($user_id, $course_id) : false;
$chapters = get_course_chapters($course_id);

// Fetch related courses
$related_courses = get_related_courses($course_id, $course['category_id'], 3);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="icon" type="image/jpg" href="https://saifali.sirv.com/favicon/favicon-32x32.png">
    <title><?php echo htmlspecialchars($course['title']); ?> - Course Details</title>

</head>
<body>
    <header>
        <?php include 'includes/header.php'; ?>
    </header>

    <main class="event-details-container1">
        <div class="event-header5">
            <h1 class="event-title3"><?php echo htmlspecialchars($course['title']); ?></h1>
          

</div>


 <?php if (!empty($course['intro_video_url'])): ?>
    <div class="course-intro-video">
        <h2>Course Introduction</h2>
        <div class="course-video-container">
            <iframe src="<?php echo htmlspecialchars($course['intro_video_url']); ?>" 
                    frameborder="0" 
                    allow="autoplay; fullscreen" 
                    allowfullscreen>
            </iframe>
        </div>
    </div>
<?php endif; ?>  




            <div class="event-info-container1">
                <ul class="event-meta-list9">
                    <li class="event-meta-item6">
                        <span class="event-meta-icon2">📚 Category:</span>
                        <span><?php echo htmlspecialchars(get_category_name($course['category_id'])); ?></span>
                    </li>
                    <li class="event-meta-item6">
                        <span class="event-meta-icon2">📅 Created on:</span>
                        <span><?php echo date('M d, Y', strtotime($course['created_at'])); ?></span>
                    </li>
                    <li class="event-meta-item6">
                        <span class="event-meta-icon2">📊 Total Chapters:</span>
                        <span><?php echo count($chapters); ?></span>
                    </li>
                    <?php if ($is_enrolled): ?>
                        <li class="event-meta-item6">
                            <span class="event-meta-icon2">🏆 Your Progress:</span>
                            <span>
                                <?php
                                $progress = get_course_progress($user_id, $course_id);
                                echo "{$progress['completed_chapters']} / {$progress['total_chapters']} chapters completed";
                                ?>
                            </span>
                        </li>
                    <?php endif; ?>
                </ul>

                <?php if (!$is_enrolled): ?>
                    <a href="enroll.php?course_id=<?php echo $course_id; ?>" class="register-btn4">Enroll in Course</a>
                <?php else: ?>
                    <a href="#chapters" class="register-btn4">Continue Learning</a>
                <?php endif; ?>
            </div>
        </div>




        <div class="course-content-wrapper2">
            <div class="course-image-wrapper2">
                <img src="<?php echo htmlspecialchars($course['thumbnail_url']); ?>" alt="<?php echo htmlspecialchars($course['title']); ?>" class="course-thumbnail5">
            </div>



        <?php if ($is_enrolled): ?>
            <div id="chapters">
                <h2>Course Chapters</h2>
                <ul class="chapters-list">
                    <?php foreach ($chapters as $chapter): ?>
                        <li class="chapter-item">
                            <a href="view_chapter.php?id=<?php echo $chapter['id']; ?>" class="course-chapter-title">
                                <?php echo htmlspecialchars($chapter['title']); ?>
                                <?php if (is_chapter_completed($user_id, $chapter['id'])): ?>
                                    <span class="completed-badge">✓ Completed</span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                     

                    <?php if (!empty($course['assets_url'])): ?>
    <div class="course-assets">
        <h2>Course Assets</h2>
        <a href="<?php echo htmlspecialchars($course['assets_url']); ?>" class="download-assets-btn" target="_blank">Download All Assets</a>
    </div>
<?php endif; ?>

                </ul>
            </div>

            <?php
            $is_course_completed = check_course_completion($user_id, $course_id);
            if ($is_course_completed):
            ?>
                <div class="registration-status1">
                    <h3>Congratulations! You have completed this course.</h3>
                    <p>Thank you for taking this course. We hope you found it valuable!</p>
                </div>
            <?php endif; ?>
        <?php endif; ?>

       <!-- <?php if (!empty($related_courses)): ?>
            <section class="related-events">
                <h3>Related Courses</h3>
                <div class="related-events-container">
                    <?php foreach ($related_courses as $related_course): ?>
                        <div class="related-event-card">
                            <img src="<?php echo htmlspecialchars($related_course['thumbnail_url']); ?>" alt="<?php echo htmlspecialchars($related_course['title']); ?>">
                            <h4><?php echo htmlspecialchars($related_course['title']); ?></h4>
                            <p>Created: <?php echo date('M d, Y', strtotime($related_course['created_at'])); ?></p>
                            <a href="course-details.php?id=<?php echo $related_course['id']; ?>" class="view-event-btn">View Course</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>  -->
         <div class="event-description8">
    <?php echo parse_course_description($course['description']); ?>
</div>
    </main>
    <footer>
        <?php include 'includes/footer.php'; ?>
    </footer>
</body>
</html>