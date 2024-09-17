<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.php");
    exit();
}
include '../includes/db.php';
include'../includes/version.php';
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
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
    <style>
  <style>
    body {
        padding-bottom: 60px; /* Adjust based on the height of your navbar */
    }

    .bottom-navbar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        height: 60px;
        background-color: rgb(41, 45, 50);
        display: flex;
        justify-content: space-around;
        align-items: center;
        z-index: 1000;
    }

    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        color: #fff;
        transition: all 0.3s ease;
        position: relative;
    }

    .nav-item i {
        font-size: 24px;
        margin-bottom: 4px;
    }

    .nav-item span {
        font-size: 12px;
    }

    .nav-item::before {
        content: '';
        position: absolute;
        top: -20px;
        left: 50%;
        transform: translateX(-50%);
        width: 40px;
        height: 40px;
        background-color: rgb(89, 95, 247);
        border-radius: 50%;
        opacity: 0;
        transition: all 0.3s ease;
        z-index: -1;
    }

    .nav-item.active {
        color: rgb(89, 95, 247);
    }

    .nav-item.active::before {
        opacity: 0.2;
        top: -10px;
    }

    @media (min-width: 769px) {
        .bottom-navbar {
            display: none;
        }
    }
</style>
</head>

<body>
    <div class="sidebar">
        <button id="sidebar-toggle" class="sidebar-toggle">
            <i class="fa-solid fa-circle-chevron-left"></i> <!-- Menu icon -->
        </button>
        <div class="brand">Welcome<p style="font-size:20px;padding-top:12px;"><?php echo $user['name']; ?></p></div>
        
        <ul class="menu">
            <li class="menu-item active">
                <a href="../user/dashboard.php" class="menu-link">
                    <span class="icon"><i class="ri-layout-masonry-fill"></i></span>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="../user/upload-resource.php" class="menu-link">
                    <span class="icon"><i class="ri-upload-cloud-2-line"></i></span>
                    <span class="text">Upload Resource</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="../user/manage-resources.php" class="menu-link">
                    <span class="icon"><i class="ri-folders-line"></i></span>
                    <span class="text">Manage Resource</span>
                </a>
            </li>
  <li class="menu-item">
                <a href="../user/manage-events.php" class="menu-link">
                    <span class="icon"><i class="fa-regular fa-calendar"></i></span>
                    <span class="text">Events</span>
                </a>
            </li>

          <li class="menu-item dropdown-item">
                    <a href="../user/my_courses.php" class="menu-link">
                         <span class="icon"><i class="ri-folder-video-line"></i></span>
                        <span class="text">My Courses</span>
                    </a>
                </li>

    <li class="menu-item">
                <a href="../index.php" class="menu-link">
                    <span class="icon"><i class="iconoir-home-simple"></i></span>
                    <span class="text">Back To Home</span>
                </a>
            </li>


        </ul>

        <div class="profile menu-item">
            <a href="../user/view-account.php" class="menu-link">
            <img src="../uploads/profile_picture/<?php echo $user['profile_picture']; ?>"
                alt="Profile">
            <span class="username">Account Info</span>
            </a>
        </div>
        
        <div class="menu-item logout">
            <a href="../user/logout.php" class="menu-link">
            <span class="icon"><i class="ri-logout-circle-line"></i></span>
            <span class="text">Logout</span>
            </a>
        </div>
    </div>
<!-- 
this is for overview shows on dashbaord
    <div class="main-content">

        <div class="content">
            <div class="activities">
                <h2>Activities</h2>

                <!-- Activities content goes here -->
           <!--  </div>
            <div class="goals-budget">
                <h2>Goals Budget</h2>
                <div class="goal-card">Travel $55/$99</div>
            </div>
        </div>
    </div> -->
    
<!-- buttom nav bar -->
    <nav class="bottom-navbar">
        <a href="../user/dashboard.php" class="nav-item" data-page="dashboard">
            <i class="ri-layout-masonry-fill"></i>
            <span>Dashboard</span>
        </a>
        <a href="../user/upload-resource.php" class="nav-item" data-page="upload">
            <i class="ri-upload-cloud-2-line"></i>
            <span>Upload</span>
        </a>
        <a href="../user/manage-events.php" class="nav-item" data-page="manage">
           <i class="fa-regular fa-calendar"></i>
            <span>Events</span>
        </a>
        <a href="../user/my_courses.php" class="nav-item" data-page="courses">
            <i class="ri-folder-video-line"></i>
            <span>Courses</span>
        </a>

       <a href="../user/view-account.php" class="nav-item" data-page="courses">
<i class="fa-regular fa-user"></i>
            <span>Profile</span>
        </a>
    </nav>
    
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navItems = document.querySelectorAll('.nav-item');
        const currentPath = window.location.pathname;

        navItems.forEach(item => {
            const itemPath = new URL(item.getAttribute('href'), window.location.origin).pathname;
            if (currentPath === itemPath) {
                item.classList.add('active');
            }

            item.addEventListener('click', function(e) {
                navItems.forEach(navItem => navItem.classList.remove('active'));
                this.classList.add('active');
            });
        });
    });
</script>

    <script src="sidebar.js"></script>
</body>

</html>