<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php");
    exit();
}
include '../includes/db.php';
include '../includes/version.php';
$user_id = $_SESSION['admin_id'];
$sql = "SELECT * FROM admin WHERE id='$user_id'";
$result = $conn->query($sql);
$admin = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="icon" type="image/jpg" href="https://saifali.sirv.com/favicon/favicon-32x32.png">
    <link rel="stylesheet" href="sidebar.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/iconoir-icons/iconoir@main/css/iconoir.css"/>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>
    <div class="sidebar">
        <button id="sidebar-toggle" class="sidebar-toggle">
            <i class="fa-solid fa-circle-chevron-left"></i> <!-- Menu icon -->
        </button>
        <div class="brand">Welcome<p style="font-size:20px;padding-top:12px;">Admin</p></div>
        
        <ul class="menu">
            <li class="menu-item active">
                <a href="../admin/dashboard.php" class="menu-link">
                    <span class="icon"><i class="ri-layout-masonry-fill"></i></span>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="../admin/manage-users.php" class="menu-link">
                    <span class="icon"><i class="iconoir-community"></i></span>
                    <span class="text">Users</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="#" class="menu-link dropdown-toggle">
                    <span class="icon"><i class="ri-drag-move-line"></i></i></span>
                    <span class="text">Resources</span>
                    <i class="fa-solid fa-chevron-down dropdown-icon" style="padding-left:10px;"></i>
                </a>
            </li>
            <div class="dropdown-content-dash">
                <li class="menu-item dropdown-item-dash">
                    <a href="../admin/manage-resources.php" class="menu-link">
                    <span class="icon"><i class="ri-folders-line"></i></span>
                        <span class="text">Manage Resources</span>
                    </a>
                </li>

                <li class="menu-item">
                <a href="../admin/approve-decline.php" class="menu-link">
                    <span class="icon"><i class="ri-donut-chart-line"></i></span>
                    <span class="text">Approve/Decline</span>
                </a>
                </li>
            
          <li class="menu-item dropdown-item">
                    <a href="../admin/course-dashboard.php" class="menu-link">
                         <span class="icon"><i class="ri-folder-video-line"></i></span>
                        <span class="text">Courses</span>
                    </a>
                </li>
            </div>
                   <li class="menu-item">
                <a href="../admin/result-dashboard.php" class="menu-link">
                    <span class="icon"><i class="ri-file-copy-2-line"></i></span>
                    <span class="text">Results</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="../admin/manage-events.php" class="menu-link">
                    <span class="icon"><i class="fa-regular fa-calendar"></i></span>
                    <span class="text">Events</span>
                </a>
            </li>

              <li class="menu-item">
                <a href="../admin/discussion-dashboard.php" class="menu-link">
                    <span class="icon"><i class="ri-message-3-line"></i></span>
                    <span class="text">Chats</span>
                </a>
            </li>
            
        </ul>

        <div class="profile menu-item">
            <a href="../admin/view-info.php" class="menu-link">
            <img src="https://saifali.sirv.com/1up/avatar/20.%20Stylish%20Young%20Man.png"
                alt="Profile">
            <span class="username">Account Info</span>
            </a>
        </div>
        
         <div class="menu-item logout">
            <a href="../admin/logout.php" class="menu-link">
            <span class="icon"><i class="ri-logout-circle-line"></i></span>
            <span class="text">Logout</span>
            </a>
        </div> 
    </div>
   <script src="../assets/user_sidebar/sidebar.js"></script>
</body>
</html>