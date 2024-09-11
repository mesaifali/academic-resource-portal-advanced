<?php
session_start();
require_once '../includes/functions.php';
include '../includes/version.php';
// Check if admin is logged in
checkAdminSession();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Panel</title>
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="../assets/admin_sidebar/sidebar.css?v=<?php echo $version; ?>">
</head>
<body>
    <?php include '../assets/admin_sidebar/sidebar.php';?>
    <div class="result-content">
        <h2>Chat Control</h2>
<br>

<!-- <form method="post" action="discussion-action.php">
         <div class="form-group">
            <label for="expiry_time">Set Message Expiry Time (hours):</label>
            <input type="number" id="expiry_time" name="expiry_time" value="<?php echo $expiry_time; ?>" min="0">
            <button type="submit" name="set_expiry_time" class="admin-btn" style="margin-top: 20px;">Set Expiry Time</button>
            </div>
        </form>
        <br> -->
         <h3>Delete all Conversations</h>
        <form method="post" action="discussion-action.php">
            <button type="submit" name="delete_all_messages" class="admin-btn" style="background-color:red;margin-top: 16px;">Delete All Messages</button>
        </form>
        
    </div>
</body>
</html>