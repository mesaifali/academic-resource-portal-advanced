<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php");
    exit();
}
include '../includes/db.php';
include '../includes/version.php';

$admin_id = $_SESSION['admin_id'];
$sql_admin = "SELECT * FROM admin WHERE id='$admin_id'";
$result_admin = $conn->query($sql_admin);
$admin = $result_admin->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
    $sql_update_password = "UPDATE admin SET password='$new_password' WHERE id='$admin_id'";
    $conn->query($sql_update_password);
    echo "Password updated successfully.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="../assets/admin_sidebar/sidebar.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">


    <title>View My Info - Academic Resource Portal</title>
</head>
<body>
<?php include '../assets/admin_sidebar/sidebar.php';?>
    <div class="admin-account-container">
    <div class="admin-profile-section">
    <div> <img src="https://saifali.sirv.com/1up/avatar/20.%20Stylish%20Young%20Man.png" alt="Profile" style="height:100px; width:100px;    border-radius: 50%; border: 0.5px solid gray;">
    </div>
       
       <div style="padding-left: 40px;">
        <h2>View My Info</h2>
       <p>Name: admin </p>
       <!-- <p>Name: <?php echo $admin['name']; ?></p> -->
        <p>Email: <?php echo $admin['email']; ?></p>
        <p>Username: <?php echo $admin['username']; ?></p>
        </div>
</div>
        <form action="" method="POST" style="padding-top: 50px">
            <h3>Change Password</h3>
           <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" placeholder="New Password" required>
                 </div>
            <button type="submit" name="update_password" class="dash-btn">Update Password</button>           
        </form>
    </div>
    <script src="../assets/user_sidebar/sidebar.js"></script>

</body>
</html>
