<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.php");
    exit();
}
include '../includes/db.php';
include '../includes/version.php';

$user_id = $_SESSION['user_id'];

// Fetch events registered by the user
$sql_user_events = "SELECT e.*, er.registration_date 
                    FROM events e 
                    JOIN event_registrations er ON e.id = er.event_id 
                    WHERE er.user_id = '$user_id' 
                    ORDER BY e.event_date DESC";
$result_user_events = $conn->query($sql_user_events);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="../assets/user_sidebar/sidebar.css?v=<?php echo $version; ?>">
    <title>Manage Events - Academic Resource Portal</title>
    <style>
        .thumbnail-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: none;
        }
        .modal-content {
            background-color: var(--popup-content-bg);
            margin: 15% auto;
            padding: 28px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 5px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }


        .confirm-cancel-btn{
padding: 16px 132px;
  background-color: var(--color-red);
  color: var(--color-white);
  border: none;
  border-radius: 5px;
  cursor: pointer;
  text-decoration: none;
  display: flex;
  transition: background-color 0.3s ease;
  margin-top: 28px;
        }
    </style>
</head>
<body>
<?php include '../assets/user_sidebar/sidebar.php';?>

<div class="dashboard-container">
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Event Date</th>
                <th>Type</th>
                <th>Thumbnail</th>
                <th>Registration Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($event = $result_user_events->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($event['title']); ?></td>
                    <td><?php echo date('M d, Y', strtotime($event['event_date'])); ?></td>
                    <td><?php echo ucfirst($event['type']); ?></td>
                    <td>
                        <img src="../uploads/event_thumbnails/<?php echo htmlspecialchars($event['thumbnail']); ?>" 
                             alt="Event Thumbnail" 
                             class="thumbnail-preview">
                    </td>
                    <td><?php echo date('M d, Y', strtotime($event['registration_date'])); ?></td>
                    <td>
                        <a href="#" class="delete-btn" onclick="openCancelModal(<?php echo $event['id']; ?>, '<?php echo addslashes($event['title']); ?>')">
                            <i class="fa-regular fa-calendar-xmark"></i>Cancel
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- The Modal -->
<div id="cancelModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 style="padding-bottom: 28px;">Cancel Event Registration</h2>
        <p>Are you sure you want to cancel your registration for: <strong id="eventTitle"></strong>?</p>
        <button id="confirmCancel" class="confirm-cancel-btn">Confirm Cancellation</button>
    </div>
</div>

<script src="../assets/user_sidebar/sidebar.js"></script>
<script>
// Get the modal
var modal = document.getElementById("cancelModal");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// Function to open the modal
function openCancelModal(eventId, eventTitle) {
    document.getElementById("eventTitle").textContent = eventTitle;
    document.getElementById("confirmCancel").onclick = function() {
        cancelRegistration(eventId);
    };
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// Function to cancel registration
function cancelRegistration(eventId) {
    fetch('cancel-registration.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'event_id=' + eventId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Registration cancelled successfully');
            location.reload(); // Reload the page to reflect the changes
        } else {
            alert('Error cancelling registration: ' + data.message);
        }
        modal.style.display = "none";
    })
    .catch((error) => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
        modal.style.display = "none";
    });
}
</script>
</body>
</html>