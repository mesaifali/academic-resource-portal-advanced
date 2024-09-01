<?php
include 'includes/db.php';
include 'includes/functions.php';
include 'include/version.php';

// Enable error reporting for debugging
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Initialize search query and filter
$search_query = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$filter = isset($_GET['filter']) ? sanitizeInput($_GET['filter']) : 'all';

// Pagination settings
$items_per_page = 12;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Modify SQL query to filter based on the search query and filter
$sql_resources = "SELECT * FROM resources WHERE status='approved'";
if (!empty($search_query)) {
    $sql_resources .= " AND (title LIKE '%$search_query%' OR description LIKE '%$search_query%' OR type LIKE '%$search_query%')";
}
if ($filter != 'all') {
    $sql_resources .= " AND type='$filter'";
}

// Count total results for pagination
$count_result = $conn->query($sql_resources);
if (!$count_result) {
    die("Count query failed: " . $conn->error);
}
$total_items = $count_result->num_rows;
$total_pages = ceil($total_items / $items_per_page);

// Add LIMIT and OFFSET to the main query
$sql_resources .= " LIMIT $items_per_page OFFSET $offset";

$result_resources = $conn->query($sql_resources);
if (!$result_resources) {
    die("Main query failed: " . $conn->error);
}

// // Debugging information
// $debug_info = "
//     <div style='background-color: #f0f0f0; padding: 10px; margin: 10px 0; border: 1px solid #ccc;'>
//         <h3>Debugging Information</h3>
//         <p>Total Items: $total_items</p>
//         <p>Total Pages: $total_pages</p>
//         <p>Current Page: $page</p>
//         <p>Items per Page: $items_per_page</p>
//         <p>Offset: $offset</p>
//         <p>SQL Query: " . htmlspecialchars($sql_resources) . "</p>
//     </div>
// ";

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="icon" type="image/jpg" href="https://saifali.sirv.com/favicon/favicon-32x32.png">
    <title>Resources - Academic Resource Portal</title>

</head>
<body>
    <header>
        <?php include 'includes/header.php'; ?>
    </header>
    <main>
        <section class="resources-section">
            <h2>All Resources</h2>

            <?php echo $debug_info; // Display debugging information ?>

            <!-- Search Form -->
            <form action="resources.php" method="GET" class="search-form">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search by title, description, or type">
                <input type="hidden" name="filter" value="<?php echo htmlspecialchars($filter); ?>">
                <button type="submit" class="search-btn">Search</button>
            </form>

            <!-- Filter Chips -->
            <div class="filter-container">
                <a href="resources.php?search=<?php echo urlencode($search_query); ?>&filter=all" class="chip <?php echo $filter == 'all' ? 'active' : ''; ?>">All</a>
                <a href="resources.php?search=<?php echo urlencode($search_query); ?>&filter=book" class="chip <?php echo $filter == 'book' ? 'active' : ''; ?>">Books</a>
                <a href="resources.php?search=<?php echo urlencode($search_query); ?>&filter=note" class="chip <?php echo $filter == 'note' ? 'active' : ''; ?>">Notes</a>
                <a href="resources.php?search=<?php echo urlencode($search_query); ?>&filter=question" class="chip <?php echo $filter == 'question' ? 'active' : ''; ?>">Questions</a>
            </div>

            <!-- Resources Grid -->
            <div class="resources-container">
                <?php if ($result_resources->num_rows > 0) { 
                    while ($resource = $result_resources->fetch_assoc()) { 
                        $thumbnail = 'uploads/thumbnail/' . htmlspecialchars($resource['thumbnail']);
                        $title = htmlspecialchars($resource['title']);
                        $type = ucfirst(htmlspecialchars($resource['type']));
                        $description = htmlspecialchars($resource['description']);
                        $file_path = htmlspecialchars($resource['file_path']);
                        $download_count = htmlspecialchars($resource['download_count']);
                ?>
                    <div class="resource-card">
                        <img src="<?php echo $thumbnail; ?>" alt="<?php echo $title; ?>" class="resource-thumbnail">
                        <div class="resource-info">
                            <h3><?php echo $title; ?></h3>
                            <p class="resource-type">Type: <?php echo $type; ?></p>
                            <p><?php echo $description; ?></p>
                           <!-- <p><strong>Downloads:</strong> <?php echo $download_count; ?></p> -->
                            <a href="<?php echo (isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])) ? 'download.php?file=' . urlencode($file_path) . '&type=' . urlencode(strtolower($type)) : 'signin.php'; ?>" class="download-btn">
                                <?php echo (isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])) ? 'Download' : 'Sign In to Download'; ?>
                            </a>
                        </div>
                    </div>
                <?php } 
                } else { ?>
                    <p>No resources found matching your search.</p>
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