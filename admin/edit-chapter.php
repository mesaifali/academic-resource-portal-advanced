<?php
session_start();
require_once '../includes/functions.php';

checkAdminSession();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage-courses.php");
    exit();
}

$chapter_id = (int)$_GET['id'];
$chapter = get_chapter($chapter_id);

if (!$chapter) {
    header("Location: manage-courses.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitizeInput($_POST['title']);
    $video_link = sanitizeInput($_POST['video_link']);

    if (update_chapter($chapter_id, $title, $video_link)) {
        $_SESSION['message'] = "Chapter updated successfully.";
        header("Location: edit-course.php?id=" . $chapter['course_id']);
        exit();
    } else {
        $error = "Failed to update chapter.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Chapter</title>
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="../assets/admin_sidebar/sidebar.css?v=<?php echo $version; ?>"></head>
<body>
<?php include '../assets/admin_sidebar/sidebar.php';?>

    <div class="dashboard-container">
    <h2 onclick="history.back()"><i class="fa-solid fa-arrow-left"></i><a href="#">Edit Chapter</a></h2>
        <!-- <h2><i class="fa-solid fa-arrow-left-long"></i>Edit Chapter</h2> -->
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="" method="post">
         <div class="form-group">
            <label for="title">Chapter Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($chapter['title']); ?>" required>
</div>
 <div class="form-group">
            <label for="video_link">Video Link:</label>
            <input type="url" id="video_link" name="video_link" value="<?php echo htmlspecialchars($chapter['video_link']); ?>" required>
</div>
            <button type="submit"  class="dash-btn">Update Chapter</button>
        </form>
    </div>
</body>
</html>