<?php
include 'version.php'; 
session_start(); // Start the session at the beginning of the file

// Function to check if a user is logged in
function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to check if an admin is logged in
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="../assets/styles.css?v=<?php echo $version; ?>">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Academic Resource Portal</title>

</head>
<body>
    <header>
        <nav class="navbar">
            <a href="index.php" class="navbar-brand"> <img src="https://saifali.sirv.com/favicon/apple-icon-72x72.png"></a>
<span class="menu-icon" id="menuIcon" onclick="toggleMenu()"><i class="fa-solid fa-bars"></i></span>
            <div class="container">
                <ul class="navbar-menu" id="navbarMenu">
                    <li><a class="nav-ani" href="index.php">Home</a></li>
                    <li><a class="nav-ani" href="about.php">About Us</a></li>
                    
                      <li><a class="nav-ani" href="resources.php">Resources</a></li>
                     <li><a class="nav-ani" href="events.php">Events</a></li>
                    <li><a class="nav-ani" href="courses.php">Courses</a></li>
   <?php if (isUserLoggedIn() || isAdminLoggedIn()): ?>
               <li> <a class="nav-ani" href="<?php echo isAdminLoggedIn() ? 'admin/dashboard.php' : 'user/dashboard.php'; ?>">Dashboard</a></li>
               <li> <a href="user/logout.php" style="background: red;padding: 12px;border-radius: 4px;color:white;">Logout</a></li>
                    <?php else: ?>

                        <li><a class="signin-button" href="signin.php">Sign In</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>

    <script>
        function toggleMenu() {
            var menu = document.getElementById('navbarMenu');
            var icon = document.getElementById('menuIcon');
            
            if (menu.classList.contains('show')) {
                menu.classList.remove('show');
                icon.innerHTML = '<i class="fa-solid fa-bars"></i>'; // Change to hamburger icon
            } else {
                menu.classList.add('show');
                icon.innerHTML = '<i class="fa-solid fa-xmark"></i>'; // Change to close icon
            }
        }
    </script>
</body>
</html>