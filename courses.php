<?php
include 'includes/db.php';
include 'includes/functions.php';
include 'include/version.php';

// Initialize search query and filter
$search_query = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$filter = isset($_GET['filter']) ? sanitizeInput($_GET['filter']) : 'all';

// Pagination settings
$items_per_page = 12;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Modify SQL query to filter based on the search query and filter
$sql_courses = "SELECT c.*, cc.name as category_name FROM courses c 
                LEFT JOIN course_categories cc ON c.category_id = cc.id 
                WHERE 1=1";
if (!empty($search_query)) {
    $sql_courses .= " AND (c.title LIKE '%$search_query%' OR c.description LIKE '%$search_query%')";
}
if ($filter != 'all') {
    $sql_courses .= " AND c.category_id = '$filter'";
}

// Count total results for pagination
$count_result = $conn->query($sql_courses);
$total_items = $count_result->num_rows;
$total_pages = ceil($total_items / $items_per_page);

// Add LIMIT and OFFSET to the main query
$sql_courses .= " ORDER BY c.created_at DESC LIMIT $items_per_page OFFSET $offset";

$result_courses = $conn->query($sql_courses);

// Fetch all categories for the filter
$sql_categories = "SELECT * FROM course_categories";
$result_categories = $conn->query($sql_categories);
$categories = $result_categories->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="icon" type="image/jpg" href="https://saifali.sirv.com/favicon/favicon-32x32.png">
    <title>Courses - Academic Resource Portal</title>
</head>
<body>
    <header>
        <?php include 'includes/header.php'; ?>
    </header>
    <main>
        <section class="resources-section">
            <h2>All Courses</h2>

            <!-- Search Form -->
            <form action="courses.php" method="GET" class="search-form">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search by title or description">
                <input type="hidden" name="filter" value="<?php echo htmlspecialchars($filter); ?>">
                <button type="submit" class="search-btn">Search</button>
            </form>

            <!-- Filter Chips -->
            <div class="filter-container">
                <a href="courses.php?search=<?php echo urlencode($search_query); ?>&filter=all" class="chip <?php echo $filter == 'all' ? 'active' : ''; ?>">All</a>
                <?php foreach ($categories as $category): ?>
                    <a href="courses.php?search=<?php echo urlencode($search_query); ?>&filter=<?php echo $category['id']; ?>" class="chip <?php echo $filter == $category['id'] ? 'active' : ''; ?>"><?php echo htmlspecialchars($category['name']); ?></a>
                <?php endforeach; ?>
            </div>

            <!-- Courses Grid -->
            <div class="resources-container">
                <?php if ($result_courses->num_rows > 0) { 
                    while ($course = $result_courses->fetch_assoc()) { 
                        $thumbnail = htmlspecialchars($course['thumbnail_url']);
                        $title = htmlspecialchars($course['title']);
                         $description = parse_course_description($course['description']);
                // Limit the description to the first 100 characters
                $short_description = substr(strip_tags($description), 0, 100) . '...';
                        $category = htmlspecialchars($course['category_name']);
                ?>
                    <div class="resource-card">
                        <img src="<?php echo $thumbnail; ?>" alt="<?php echo $title; ?>" class="resource-thumbnail">
                        <div class="resource-info">
                            <h3><?php echo $title; ?></h3>
                            <p class="resource-type">Category: <?php echo $category; ?></p>
                            <div class="course-description"><?php echo $short_description; ?></div>
                            <a href="view_course.php?id=<?php echo $course['id']; ?>" class="download-btn" style="margin-top: 12px;">View Course</a>
                        </div>
                    </div> 
                       
<!-- <div class="resource-card">
    <img src="<?php echo $thumbnail; ?>" alt="<?php echo $title; ?>" class="resource-thumbnail">
    <div class="course-info">
        <h3><?php echo $title; ?></h3>
        <?php if (!empty($course['intro_video_url'])): ?>
            <span class="video-icon" title="This course has an intro video">🎥</span>
        <?php endif; ?>
        <?php if (!empty($course['assets_url'])): ?>
            <span class="assets-icon" title="This course has downloadable assets">📁</span>
        <?php endif; ?>
        <div class="course-description"><?php echo $short_description; ?></div>
        <a href="view_course.php?id=<?php echo $course['id']; ?>" class="download-btn">View Course</a>
    </div>
</div> -->




                <?php } 
                } else { ?>
                    <p>No courses found matching your search.</p>
                <?php } ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search_query); ?>&filter=<?php echo urlencode($filter); ?>" class="page-link">&laquo; Previous</a>
                <?php endif; ?>

                <?php
                $start_page = max(1, $page - 2);
                $end_page = min($total_pages, $page + 2);
                
                for ($i = $start_page; $i <= $end_page; $i++):
                ?>
                    <?php if ($i == $page): ?>
                        <span class="page-link active"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search_query); ?>&filter=<?php echo urlencode($filter); ?>" class="page-link"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search_query); ?>&filter=<?php echo urlencode($filter); ?>" class="page-link">Next &raquo;</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </section>
    </main>
    <footer>
        <?php include 'includes/footer.php'; ?>
    </footer>
</body>
</html>