<?php
include 'includes/db.php';
include 'includes/functions.php';
include 'include/version.php';

session_start();

if (!isset($_GET['id'])) {
    header("Location: events.php");
    exit();
}

$event_id = intval($_GET['id']);
$sql_event = "SELECT * FROM events WHERE id = $event_id";
$result_event = $conn->query($sql_event);

if ($result_event->num_rows == 0) {
    header("Location: events.php");
    exit();
}

$event = $result_event->fetch_assoc();

// Check if the user is already registered for this event
$user_already_registered = false;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql_check_registration = "SELECT * FROM event_registrations WHERE event_id = $event_id AND user_id = $user_id";
    $result_check_registration = $conn->query($sql_check_registration);
    $user_already_registered = ($result_check_registration->num_rows > 0);
}

// Handle event registration
if (isset($_POST['register']) && isset($_SESSION['user_id']) && !$user_already_registered) {
    $user_id = $_SESSION['user_id'];
    $sql_register = "INSERT INTO event_registrations (event_id, user_id) VALUES ($event_id, $user_id)";
    if ($conn->query($sql_register) === TRUE) {
        echo "<script>alert('You have been registered for this event. We will contact you via email and phone.');</script>";
        $user_already_registered = true;
    } else {
        echo "<script>alert('Error registering for the event. Please try again.');</script>";
    }
}
// Fetch related events (you can customize this query as needed)
$sql_related_events = "SELECT id, title, event_date, event_end_date, thumbnail FROM events 
                       WHERE id != $event_id AND type = '{$event['type']}' AND status != 'archived'
                       ORDER BY RAND() LIMIT 3";
$result_related_events = $conn->query($sql_related_events);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="icon" type="image/jpg" href="https://saifali.sirv.com/favicon/favicon-32x32.png">
    <title><?php echo htmlspecialchars($event['title']); ?> - Academic Resource Portal</title>
</head>
<body>
    <header>
        <?php include 'includes/header.php'; ?>
    </header>
    <main class="event-details-container1">
        <div class="event-header5">
            <h1 class="event-title3"><?php echo htmlspecialchars($event['title']); ?></h1>
            <div class="event-description8">
                <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
            </div>
        </div>
        <div class="event-content-wrapper2">
            <div class="event-image-wrapper2">
                <img src="uploads/event_thumbnails/<?php echo htmlspecialchars($event['thumbnail']); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>" class="event-thumbnail5">
            </div>
            <div class="event-info-container1">
                <ul class="event-meta-list9">
                    <li class="event-meta-item6">
                        <span class="event-meta-icon2">⏰ Registration Deadline:</span>
                        <span> <?php echo date('M d, Y', strtotime($event['register_before'])); ?></span>
                    </li>
                    <li class="event-meta-item6">
                        <span class="event-meta-icon2">📅 Event Start Date:</span>
                        <span><?php echo date('M d, Y', strtotime($event['event_date'])); ?></span>
                    </li>
                    <li class="event-meta-item6">
                        <span class="event-meta-icon2">🏁 Event End Date:</span>
                        <span><?php echo date('M d, Y', strtotime($event['event_end_date'])); ?></span>
                    </li>
                    <li class="event-meta-item6">
                        <span class="event-meta-icon2">📍Location: </span>
                        <span><?php echo htmlspecialchars($event['location']); ?></span>
                    </li>
                    <li class="event-meta-item6">
                        <span class="event-meta-icon2">💻Mode: </span>
                        <span><?php echo ucfirst(htmlspecialchars($event['mode'])); ?></span>
                    </li>
                    <li class="event-meta-item6">
                        <span class="event-meta-icon2">💰Reg. Price: </span>
                        <span><?php echo $event['fee'] > 0 ? 'रु' . number_format($event['fee'], 2) : 'Free'; ?></span>
                    </li>
                    <li class="event-meta-item6">
                        <span class="event-meta-icon2">🏷️Status: </span>
                        <span><?php echo ucfirst($event['status']); ?></span>
                    </li>
                </ul>
                
                <?php if ($event['status'] != 'archived'): ?>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($user_already_registered): ?>
                            <p class="registration-status1">You're registered for this event</p>
                        <?php else: ?>
                            <form action="" method="POST">
                                <button type="submit" name="register" class="register-btn4">Register for Event</button>
                            </form>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="signin.php" class="register-btn4">Sign In to Register</a>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="registration-status1">This event has ended</p>
                <?php endif; ?>
            </div>
        </div>
        <p style="text-align:center;">Note: Paid Events Charges are manual for now. We will contact you for payment after registration.</p>

        <?php if ($result_related_events->num_rows > 0): ?>
            <section class="related-events">
                <h3>Related Events</h3>
                <div class="related-events-container">
                    <?php while ($related_event = $result_related_events->fetch_assoc()): ?>
                        <div class="related-event-card">
                            <img src="uploads/event_thumbnails/<?php echo htmlspecialchars($related_event['thumbnail']); ?>" alt="<?php echo htmlspecialchars($related_event['title']); ?>">
                            <h4><?php echo htmlspecialchars($related_event['title']); ?></h4>
                            <p>Start: <?php echo date('M d, Y', strtotime($related_event['event_date'])); ?></p>
                            <p>End: <?php echo date('M d, Y', strtotime($related_event['event_end_date'])); ?></p>
                            <a href="event-details.php?id=<?php echo $related_event['id']; ?>" class="view-event-btn">View Event</a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </section>
        <?php endif; ?>
    </main>
    <footer>
        <?php include 'includes/footer.php'; ?>
    </footer>
</body>
</html>