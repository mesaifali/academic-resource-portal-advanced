<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/functions.php';

checkUserSession();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: courses.php");
    exit();
}

$chapter_id = (int)$_GET['id'];
$chapter = get_chapter($chapter_id);

if (!$chapter) {
    echo "Chapter not found.";
    exit();
}

$user_id = $_SESSION['user_id'];
$course_id = $chapter['course_id'];

if (!is_user_enrolled($user_id, $course_id)) {
    header("Location: view_course.php?id=$course_id");
    exit();
}

$is_completed = is_chapter_completed($user_id, $chapter_id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['toggle_completion'])) {
        if ($is_completed) {
            unmark_chapter_complete($user_id, $chapter_id);
            $is_completed = false;
            $_SESSION['message'] = "Chapter marked as incomplete.";
        } else {
            mark_chapter_complete($user_id, $chapter_id);
            $is_completed = true;
            $_SESSION['message'] = "Chapter marked as complete!";
        }
    }
    header("Location: view_chapter.php?id=$chapter_id");
    exit();
}

$course = get_course($course_id);
$chapters = get_course_chapters($course_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($chapter['title']); ?></title>
    <link rel="stylesheet" href="assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="icon" type="image/jpg" href="https://saifali.sirv.com/favicon/favicon-32x32.png">
    <style>

        .view-chapter-container {
         display: flex;
            width: 97%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

 .sidebar-course {
            width: 250px;
            background-color: var(--color-white);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px var(--shadow-color);
            margin-right: 20px;
        }

        .main-content {
            flex: 1;
        }

        h1, h2 {
            color: var(--color-dark-blue);
        }

        .video-container {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 aspect ratio */
            height: 0;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .complete-btn {
            display: inline-block;
            background-color: var(--color-green);
            color: var(--color-white);
            padding: 10px 20px;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .complete-btn:hover {
            background-color: var(--color-light-blue);
        }

        .completed-badge {
            display: inline-block;
            background-color: var(--color-green);
            color: var(--color-white);
            padding: 5px 10px;
            border-radius: 5px;
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }

        .success {
            background-color: var(--color-green);
            color: var(--color-white);
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: var(--color-blue);
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .chapter-list {
            list-style-type: none;
            padding: 0;
        }

        .chapter-list li {
            margin-bottom: 10px;
        }

        .chapter-list a {
            color: var(--color-dark-blue);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .chapter-list a:hover {
            color: var(--color-blue);
        }

        .chapter-list .active {
            font-weight: bold;
            color: var(--color-bright-blue);
        }

        @media (max-width: 768px) {
            .view-chapter-container {
                flex-direction: column;
            }

            .sidebar-course {
                width: 83%;
                margin-right: 0;
                margin-bottom: 20px;
            }
            .video-container iframe {
        
            width: 94%;
            
        }
        }

   .toggle-btn {
            display: inline-block;
            background-color: var(--color-bright-blue);
            color: var(--color-white);
            padding: 10px 20px;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .toggle-btn:hover {
            background-color: var(--color-light-blue);
        }
    </style>
</head>
<body>
    <header>
        <?php include 'includes/header.php'; ?>
    </header>

   <div class="view-chapter-container">
        <div class="sidebar-course">
            <h2>Chapters</h2>
            <ul class="chapter-list">
                <?php foreach ($chapters as $ch): ?>
                    <li>
                        <a href="view_chapter.php?id=<?php echo $ch['id']; ?>" 
                           class="<?php echo ($ch['id'] == $chapter_id) ? 'active' : ''; ?>">
                            <?php echo htmlspecialchars($ch['title']); ?>
                            <?php if (is_chapter_completed($user_id, $ch['id'])): ?>
                                ✓
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
<!-- this is for assets -->
            <?php if (!empty($course['assets_url'])): ?>
    <div class="course-assets">
        <h2>Course Assets</h2>
        <a href="<?php echo htmlspecialchars($course['assets_url']); ?>" class="download-assets-btn" target="_blank">Download All Assets</a>
    </div>
<?php endif; ?>


        </div>

        <div class="main-content">
            <?php
            if (isset($_SESSION['message'])) {
                echo "<div class='message success'>" . htmlspecialchars($_SESSION['message']) . "</div>";
                unset($_SESSION['message']);
            }
            ?>

           <!-- <h1><?php echo htmlspecialchars($course['title']); ?></h1>
            <h2><?php echo htmlspecialchars($chapter['title']); ?></h2> -->

            <div class="video-container">
                <iframe src="<?php echo htmlspecialchars($chapter['video_link']); ?>" frameborder="0" allowfullscreen></iframe>
            </div>

            <form action="view_chapter.php?id=<?php echo $chapter_id; ?>" method="post">
                <button type="submit" name="toggle_completion" class="toggle-btn">
                    <?php echo $is_completed ? 'Mark as Incomplete' : 'Mark as Complete'; ?>
                </button>
            </form>

            <a href="view_course.php?id=<?php echo $course_id; ?>" class="back-link">Back to Course</a>
        </div>
    </div>
</body>
</html>