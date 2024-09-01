<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.php");
    exit();
}
include '../includes/db.php';
include '../includes/version.php';

$user_id = $_SESSION['user_id'];

// Fetch total uploaded resources
$sql_uploaded = "SELECT COUNT(*) as total FROM resources WHERE user_id='$user_id'";
$result_uploaded = $conn->query($sql_uploaded);
$total_uploaded = $result_uploaded->fetch_assoc()['total'];

// Fetch approved, rejected, and pending resources
$sql_status = "SELECT 
    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
    SUM(CASE WHEN status = 'declined' THEN 1 ELSE 0 END) as declined,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending
    FROM resources WHERE user_id='$user_id'";
$result_status = $conn->query($sql_status);
$status_data = $result_status->fetch_assoc();

$approved = $status_data['approved'];
$declined = $status_data['declined'];
$pending = $status_data['pending'];

// Fetch resources by category
$sql_category = "SELECT 
    SUM(CASE WHEN type = 'book' THEN 1 ELSE 0 END) as books,
    SUM(CASE WHEN type = 'note' THEN 1 ELSE 0 END) as notes,
    SUM(CASE WHEN type = 'question' THEN 1 ELSE 0 END) as questions
    FROM resources WHERE user_id='$user_id'";
$result_type = $conn->query($sql_category);
$type_data = $result_type->fetch_assoc();

$books = $type_data['books'];
$notes = $type_data['notes'];
$questions = $type_data['questions'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/user_sidebar/sidebar.css?v=<?php echo $version; ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>User Dashboard - Academic Resource Portal</title>

</head>
<body>

<?php include '../assets/user_sidebar/sidebar.php';?>

<div class="content" style="margin-left:32px; margin-top:32px;">
    <div class="overview">
        <h2>Dashboard Overview</h2>
        <div class="main-content">

            <div class="content">
                <div class="activities">
                    <h2>Total Uploaded Resources</h2>
                    <p><?php echo $total_uploaded; ?></p>
                </div>

                <div class="activities">
                    <h2>Approved Resources</h2>
                    <p><?php echo $approved; ?></p>
                </div>

                <div class="activities">
                    <h2>Rejected Resources</h2>
                    <p><?php echo $declined; ?></p>
                </div>
            </div>
        </div> 
<div class="graph-container">
        <div class="graph-section">
        <h3 style="text-align: center;padding: 0 0 8px 0;">By Resources</h3>
            <canvas id="resourcesChart"></canvas>
        </div>

        <!-- Second Pie Chart: Categories Distribution -->
        <div class="graph-section">
        <h3  style="text-align: center;padding: 0 0 8px 0;">By Category</h3>
            <canvas id="categoryChart"></canvas>
        </div>
</div>
    </div>
<script>
// First Pie Chart: Approved vs Rejected vs Pending
const ctx1 = document.getElementById('resourcesChart').getContext('2d');
const resourcesChart = new Chart(ctx1, {
    type: 'pie',
    data: {
        labels: ['Pending', 'Approved', 'Declined'],
        datasets: [{
            label: 'Resources Overview',
            data: [<?php echo $pending; ?>, <?php echo $approved; ?>, <?php echo $declined; ?>],
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
            }
        },
        animations: {
            animateRotate: true,
            animateScale: true
        }
    }
});

// Second Pie Chart: Category Distribution
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
            }
        },
        animations: {
            animateRotate: true,
            animateScale: true
        }
    }
});
</script>
<script src="../assets/user_sidebar/sidebar.js"></script>
</body>
</html>
