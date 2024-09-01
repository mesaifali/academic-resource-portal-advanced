<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php");
    exit();
}
include '../includes/db.php';

// Count the number of registered users
$sql_users = "SELECT COUNT(*) AS total_users FROM users";
$result_users = $conn->query($sql_users);
$total_users = $result_users->fetch_assoc()['total_users'];

// Count the number of uploaded resources
$sql_resources = "SELECT COUNT(*) AS total_resources FROM resources";
$result_resources = $conn->query($sql_resources);
$total_resources = $result_resources->fetch_assoc()['total_resources'];

// Get the number of pending resources
$sql_pending_resources = "SELECT COUNT(*) AS total_pending FROM resources WHERE status='pending'";
$result_pending_resources = $conn->query($sql_pending_resources);
$total_pending = $result_pending_resources->fetch_assoc()['total_pending'];

// Get the number of approved resources
$sql_approved_resources = "SELECT COUNT(*) AS total_approved FROM resources WHERE status='approved'";
$result_approved_resources = $conn->query($sql_approved_resources);
$total_approved = $result_approved_resources->fetch_assoc()['total_approved'];

// Get the number of declined resources
$sql_declined_resources = "SELECT COUNT(*) AS total_declined FROM resources WHERE status='declined'";
$result_declined_resources = $conn->query($sql_declined_resources);
$total_declined = $result_declined_resources->fetch_assoc()['total_declined'];

// Count total resources by type
$sql_category = "SELECT 
    SUM(CASE WHEN type = 'book' THEN 1 ELSE 0 END) as books,
    SUM(CASE WHEN type = 'note' THEN 1 ELSE 0 END) as notes,
    SUM(CASE WHEN type = 'question' THEN 1 ELSE 0 END) as questions
    FROM resources";
$result_type = $conn->query($sql_category);
$type_data = $result_type->fetch_assoc();

$books = $type_data['books'];
$notes = $type_data['notes'];
$questions = $type_data['questions'];

// Fetch the total downloads across all resources
$sql_total_downloads = "SELECT SUM(download_count) AS total_downloads FROM resources";
$result_downloads = $conn->query($sql_total_downloads);
$total_downloads = $result_downloads->fetch_assoc()['total_downloads'];

// Fetch top downloading resources (top 10)
$sql_top_downloads = "SELECT title, download_count, id FROM resources ORDER BY download_count DESC LIMIT 10";
$result_top_downloads = $conn->query($sql_top_downloads);

// Fetch recent joined users (latest 5)
$sql_recent_users = "SELECT username, created_at FROM users ORDER BY created_at DESC LIMIT 10";
$result_recent_users = $conn->query($sql_recent_users);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/admin_sidebar/sidebar.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <title>Admin Dashboard - Academic Resource Portal</title>

</head>
<body>
<?php include '../assets/admin_sidebar/sidebar.php';?>
<div class="content">
    <div class="overview">
        <h2>Admin Dashboard Overview</h2>
        <div class="main-content">
            <div class="content">
                <div class="activities">
                    <h2>Total Users</h2>
                    <p><?php echo $total_users; ?></p>
                </div>
                <div class="activities">
                    <h2>Total Resources</h2>
                    <p><?php echo $total_resources; ?></p>
                </div>
                <div class="activities">
                    <h2>Pending Resources</h2>
                    <p><?php echo $total_pending; ?></p>
                </div>
                <div class="activities">
                    <h2>Approved Resources</h2>
                    <p><?php echo $total_approved; ?></p>
                </div>
                <div class="activities">
                    <h2>Declined Resources</h2>
                    <p><?php echo $total_declined; ?></p>
                </div>
                <div class="activities">
                    <h2>Total Downloads</h2>
                    <p><?php echo $total_downloads; ?></p> <!-- Display the total downloads -->
                </div>

            </div>
        </div> 
    </div>
    <div class="graph-container">
        <div class="graph-section">
            <h3 style="text-align: center;padding: 0 0 8px 0;">By Resources</h3>
            <canvas id="resourcesChart"></canvas>
        </div>
        <div class="graph-section">
            <h3 style="text-align: center;padding: 0 0 8px 0;">By Category</h3>
            <canvas id="categoryChart"></canvas>
        </div>
    </div>

<div class="section-container">
    <div class="responsive-section">
        <h3>Top Downloading Resources</h3>
        <ul class="resource-list">
            <?php while($row = $result_top_downloads->fetch_assoc()): ?>
                <li>
                    <a href="resource-details.php?id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a>
                    <span class="download-count"><?php echo $row['download_count']; ?> downloads</span>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
    <div class="responsive-section">
        <h3>Recent Joined Users</h3>
        <ul class="user-list">
            <?php while($row = $result_recent_users->fetch_assoc()): ?>
                <li>
                    <span><?php echo $row['username']; ?></span>
                    <small>Joined on <?php echo date('M d, Y', strtotime($row['created_at'])); ?></small>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</div>


</div>

<script>
    // First Pie Chart: Approved vs Rejected vs Pending with 3D effect
    const ctx1 = document.getElementById('resourcesChart').getContext('2d');
    const resourcesChart = new Chart(ctx1, {
        type: 'pie',
        data: {
            labels: ['Pending', 'Approved', 'Rejected'],
            datasets: [{
                label: 'Resources Overview',
                data: [<?php echo $total_pending; ?>, <?php echo $total_approved; ?>, <?php echo $total_declined; ?>],
                backgroundColor: ['#FFDE21','#4CAF50', '#F44336'],
                borderColor: ['#FFFFFF'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            let value = tooltipItem.raw;
                            let total = resourcesChart._metasets[tooltipItem.datasetIndex].total;
                            let percentage = (value / total * 100).toFixed(2);
                            return `${tooltipItem.label}: ${value} (${percentage}%)`;
                        }
                    }
                },
                datalabels: {
                    color: '#fff',
                    formatter: (value, context) => {
                        let total = context.chart._metasets[0].total;
                        let percentage = (value / total * 100).toFixed(2);
                        return `${percentage}%`;
                    }
                }
            },
            animation: {
                animateRotate: true,
                animateScale: true
            },
            plugins: {
                chart3d: {
                    enabled: true,
                    z: 50,
                    depth: 15,
                    perspective: 1000
                }
            }
        }
    });

    // Second Pie Chart: Category Distribution with 3D effect
    const ctx2 = document.getElementById('categoryChart').getContext('2d');
    const categoryChart = new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: ['Books', 'Notes', 'Questions'],
            datasets: [{
                label: 'Resource Categories',
                data: [<?php echo $books; ?>, <?php echo $notes; ?>, <?php echo $questions; ?>],
                backgroundColor: ['#2196F3', '#FFEB3B', '#FF9800'],
                borderColor: ['#FFFFFF'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            let value = tooltipItem.raw;
                            let total = categoryChart._metasets[tooltipItem.datasetIndex].total;
                            let percentage = (value / total * 100).toFixed(2);
                            return `${tooltipItem.label}: ${value} (${percentage}%)`;
                        }
                    }
                },
                datalabels: {
                    color: '#fff',
                    formatter: (value, context) => {
                        let total = context.chart._metasets[0].total;
                        let percentage = (value / total * 100).toFixed(2);
                        return `${percentage}%`;
                    }
                }
            },
            animation: {
                animateRotate: true,
                animateScale: true
            },
            plugins: {
                chart3d: {
                    enabled: true,
                    z: 50,
                    depth: 15,
                    perspective: 1000
                }
            }
        }
    });
</script>
<script src="../assets/user_sidebar/sidebar.js"></script>
</body>
</html>