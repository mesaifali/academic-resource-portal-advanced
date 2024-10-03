<?php
session_start();
require_once '../includes/functions.php';

checkAdminSession();

// Fetch categories
$categories = get_course_categories();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitizeInput($_POST['title']);
    $description = $_POST['description']; // Don't use htmlspecialchars or htmlentities here
    $intro_video_url = $_POST['intro_video_url'];
    $assets_url = $_POST['assets_url'];
    $thumbnail_url = sanitizeInput($_POST['thumbnail_url']);
    $category_id = (int)$_POST['category_id'];

    $course_id = create_course($title, $description, $intro_video_url, $assets_url, $thumbnail_url, $category_id);

    if ($course_id) {
        // Handle dynamic chapter creation
        $chapter_count = count($_POST['chapter_title']);
        for ($i = 0; $i < $chapter_count; $i++) {
            $chapter_title = sanitizeInput($_POST["chapter_title"][$i]);
            $video_link = sanitizeInput($_POST["video_link"][$i]);
            if (!empty($chapter_title) && !empty($video_link)) {
                add_course_chapter($course_id, $chapter_title, $video_link, $i + 1);
            }
        }

        header("Location: manage-courses.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Course</title>
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="../assets/admin_sidebar/sidebar.css?v=<?php echo $version; ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
   <?php include '../assets/admin_sidebar/sidebar.php';?>

  <div class="dashboard-container">
    <h2 onclick="history.back()" ><i class="fa-solid fa-arrow-left"></i><a href="#">Create New Course</a></h2>
        
        <form action="" method="post" id="course-form">
            <div class="form-group">
                <label for="title">Course Title:</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required rows="20"></textarea>
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
                <input type="url" id="thumbnail_url" name="thumbnail_url" required>
            </div>

            <div class="form-group">
                <label for="intro_video_url">Intro Video URL:</label>
                <input type="url" id="intro_video_url" name="intro_video_url" placeholder="https://your-video-host.com/embed/...">
                <p class="help-text">Provide the embed URL for your course intro video.</p>
            </div>

            <div class="form-group">
                <label for="category_id">Category:</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Select a category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" id="chapters-container">
                <h2 style="padding-bottom: 12px;">Chapters</h2>
                <hr class="dashed">
                <div id="chapter-list">
                    <!-- Chapters will be added here dynamically -->
                </div>
                <button type="button" id="add-chapter" class="dash-btn">Add New Chapter</button>
            </div>

            <div class="form-group">
                <label for="assets_url">Course Assets URL:</label>
                <input type="url" id="assets_url" name="assets_url" placeholder="https://example.com/course-assets.zip">
                <p class="help-text">Provide a URL for downloadable course assets (e.g., PDFs, source code, etc.)</p>
            </div>

            <button type="submit" class="dash-btn">Create Course</button>
        </form>
    </div>

    <script>
    $(document).ready(function() {
        let chapterCount = 0;

        function addChapter() {
            chapterCount++;
            const newChapter = `
                <div class="chapter">
                    <h3>Chapter ${chapterCount}</h3>
                    <label for="chapter_title_${chapterCount}">Chapter Title:</label>
                    <input type="text" id="chapter_title_${chapterCount}" name="chapter_title[]" required>
                    <label for="video_link_${chapterCount}">Video Link:</label>
                    <input type="url" id="video_link_${chapterCount}" name="video_link[]" required>
                </div>
            `;
            $('#chapter-list').append(newChapter);
        }

        $('#add-chapter').click(addChapter);

        // Add the first chapter by default
        addChapter();
    });
    </script>
</body>
</html>
