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

$error = '';
$success = '';

// Fetch categories
$categories = get_course_categories();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_course'])) {
        $title = sanitizeInput($_POST['title']);
        $description = $_POST['description']; // Don't use htmlspecialchars or htmlentities here
        //$description = mysqli_real_escape_string($conn, $_POST['description']);
        // $description = sanitizeInput($_POST['description']);
        $thumbnail_url = sanitizeInput($_POST['thumbnail_url']);
            $assets_url = $_POST['assets_url'];
         $intro_video_url = $_POST['intro_video_url'];
        $category_id = (int)$_POST['category_id'];

       if  (update_course($course_id, $title, $description, $intro_video_url, $assets_url, $thumbnail_url, $category_id)) {
            $success = "Course updated successfully.";
            $course = get_course($course_id); // Refresh course data
        } else {
            $error = "Failed to update course.";
        }
    } elseif (isset($_POST['add_chapter'])) {
        $chapter_title = sanitizeInput($_POST['chapter_title']);
        $video_link = sanitizeInput($_POST['video_link']);
        $order_num = count(get_course_chapters($course_id)) + 1;

        if (add_course_chapter($course_id, $chapter_title, $video_link, $order_num)) {
            $success = "Chapter added successfully.";
        } else {
            $error = "Failed to add chapter.";
        }
    } elseif (isset($_POST['delete_chapter'])) {
        $chapter_id = (int)$_POST['delete_chapter'];
        if (delete_chapter($chapter_id)) {
            $success = "Chapter (ID: $chapter_id) deleted successfully.";
        } else {
            $error = "Failed to delete chapter (ID: $chapter_id). Please check the error logs.";
        }
    } elseif (isset($_POST['reorder_chapters'])) {
        $chapter_orders = $_POST['chapter_order'];
        if (update_chapter_order($course_id, $chapter_orders)) {
            $success = "Chapter order updated successfully.";
        } else {
            $error = "Failed to update chapter order.";
        }
    }
}

$chapters = get_course_chapters($course_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="../assets/admin_sidebar/sidebar.css?v=<?php echo $version; ?>">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
</head>
<body>
<?php include '../assets/admin_sidebar/sidebar.php';?>

  <div class="dashboard-container">
    <h2 onclick="history.back()"><i class="fa-solid fa-arrow-left"></i><a href="#">Edit Course</a></h2>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>

        <form action="" method="post">
         <div class="form-group">
            <label for="title">Course Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($course['title']); ?>" required>
</div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" required rows="10"><?php echo htmlspecialchars($course['description']); ?></textarea>
            <p class="help-text">
                You can use the following markup:
                <br>* for bullet points
                <br>1. for numbered lists
                <br>## for subheadings
                <br>Leave a blank line for new paragraphs
            </p>
        </div>
 <div class="form-group">
            <label for="thumbnail_url">Thumbnail URL:</label>
            <input type="url" id="thumbnail_url" name="thumbnail_url" value="<?php echo htmlspecialchars($course['thumbnail_url']); ?>" required>
</div>

<div class="form-group">
    <label for="intro_video_url">Intro Video URL:</label>
    <input type="url" id="intro_video_url" name="intro_video_url" placeholder="https://your-video-host.com/embed/...">
    <p class="help-text">Provide the embed URL for your course intro video.</p>
</div>

<div class="form-group">
    <label for="assets_url">Course Assets URL:</label>
    <input type="url" id="assets_url" name="assets_url" value="<?php echo htmlspecialchars($course['assets_url']); ?>">
    <p class="help-text">Provide a URL for downloadable course assets (e.g., PDFs, source code, etc.)</p>
</div>

 <div class="form-group">
            <label for="category_id">Category:</label>
            <select id="category_id" name="category_id" required>
                <option value="">Select a category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>" <?php echo ($course['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" name="update_course"  class="btn-get-started">Update Course</button>
            </div>
        </form>

       <h2>Chapters Order</h2>
        <form action="" method="post">
            <ul id="chapter-list">
                <?php foreach ($chapters as $chapter): ?>
                    <li  style=" cursor: move;" class="ui-state-default" id="chapter-<?php echo $chapter['id']; ?>">
                        <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                        <span class="chapter-title"><?php echo htmlspecialchars($chapter['title']); ?></span>
                        <a href="edit-chapter.php?id=<?php echo $chapter['id']; ?>" class="edit-chapter-btn">Edit</a>
                        <button type="submit" name="delete_chapter" value="<?php echo $chapter['id']; ?>" class="delete-chapter-btn" onclick="return confirm('Are you sure you want to delete this chapter?');">Delete</button>
                        <input type="hidden" name="chapter_order[<?php echo $chapter['id']; ?>]" value="<?php echo $chapter['order_num']; ?>">
                    </li>
                <?php endforeach; ?>
            </ul>
            <button type="submit" name="reorder_chapters"  class="btn-get-started">Save Chapter Order</button>
        </form>

        <h2 style="padding-top: 26px;">Add New Chapter</h2>
        <form action="" method="post">
         <div class="form-group">
            <label for="chapter_title">Chapter Title:</label>
            <input type="text" id="chapter_title" name="chapter_title" required>

         </div>   
 <div class="form-group">
            <label for="video_link">Video Link:</label>
            <input type="url" id="video_link" name="video_link" required>
</div>
            <button type="submit" name="add_chapter"  class="dash-btn">Add Chapter</button>
        </form>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
$(document).ready(function() {
    $("#chapter-list").sortable({
        handle: ".ui-icon",
        update: function(event, ui) {
            $('#chapter-list li').each(function(index) {
                $(this).find('input[name^="chapter_order"]').val(index + 1);
            });
        }
    });
    $("#chapter-list").disableSelection();

    // Prevent drag start when clicking on the edit or delete buttons
    $(".edit-chapter-btn, .delete-chapter-btn").on("mousedown", function(e) {
        e.stopPropagation();
    });
});
</script>
</body>
</html>