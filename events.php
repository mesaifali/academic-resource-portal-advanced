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
$sql_events = "SELECT * FROM events WHERE 1=1";
if (!empty($search_query)) {
    $sql_events .= " AND (title LIKE '%$search_query%' OR description LIKE '%$search_query%' OR type LIKE '%$search_query%')";
}
if ($filter != 'all') {
    $sql_events .= " AND type='$filter'";
}

// Count total results for pagination
$count_result = $conn->query($sql_events);
$total_items = $count_result->num_rows;
$total_pages = ceil($total_items / $items_per_page);

// Add LIMIT and OFFSET to the main query
$sql_events .= " LIMIT $items_per_page OFFSET $offset";

$result_events = $conn->query($sql_events);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css?v=<?php echo $version; ?>">
     <link rel="icon" type="image/jpg" href="https://saifali.sirv.com/favicon/favicon-32x32.png">
    <title>Events - Academic Resource Portal</title>
</head>
<body>
    <header>
        <?php include 'includes/header.php'; ?>
    </header>
    <main>
        <section class="resources-section">
            <h2>All Events</h2>

            <!-- Search Form -->
            <form action="events.php" method="GET" class="search-form">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search by title, description, or type">
                <input type="hidden" name="filter" value="<?php echo htmlspecialchars($filter); ?>">
                <button type="submit" class="search-btn">Search</button>
            </form>

            <!-- Filter Chips -->
            <div class="filter-container">
                <a href="events.php?search=<?php echo urlencode($search_query); ?>&filter=all" class="chip <?php echo $filter == 'all' ? 'active' : ''; ?>">All</a>
                <a href="events.php?search=<?php echo urlencode($search_query); ?>&filter=hackathon" class="chip <?php echo $filter == 'hackathon' ? 'active' : ''; ?>">Hackathons</a>
                <a href="events.php?search=<?php echo urlencode($search_query); ?>&filter=webinar" class="chip <?php echo $filter == 'webinar' ? 'active' : ''; ?>">Webinars</a>
                <a href="events.php?search=<?php echo urlencode($search_query); ?>&filter=workshop" class="chip <?php echo $filter == 'workshop' ? 'active' : ''; ?>">Workshops</a>
            </div>

            <!-- Events Grid -->
            <div class="resources-container">
                <?php if ($result_events->num_rows > 0) { 
                    while ($event = $result_events->fetch_assoc()) { 
                        $thumbnail = 'uploads/event_thumbnails/' . htmlspecialchars($event['thumbnail']);
                        $title = htmlspecialchars($event['title']);
                        $type = ucfirst(htmlspecialchars($event['type']));
                        $description = htmlspecialchars(substr($event['description'], 0, 100)) . '...';
                        $location = htmlspecialchars($event['location']);
                        $date = date('M d, Y', strtotime($event['event_date']));
                ?>
                    <div class="resource-card">
                        <img src="<?php echo $thumbnail; ?>" alt="<?php echo $title; ?>" class="resource-thumbnail">
                        <div class="event-info">
                            <h3><?php echo $title; ?></h3>
                            <p class="event-type">Type: <?php echo $type; ?></p>
                            <p class="event-description"><?php echo $description; ?></p>
                            <p class="event-location">Location: <?php echo $location; ?></p>
                            <p class="event-date">Start Date: <?php echo $date; ?></p>
                            <a href="event-details.php?id=<?php echo $event['id']; ?>" class="download-btn">View More Deatils</a>
                        </div>
                    </div>
                <?php } 
                } else { ?>
                    <p>No events found matching your search.</p>
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