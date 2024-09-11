<?php
session_start();
require_once '../includes/functions.php';

checkAdminSession();

$courses = get_all_courses();

// Handle course deletion
if (isset($_POST['delete_course'])) {
    $course_id = (int)$_POST['course_id'];
    if (delete_course($course_id)) {
        $_SESSION['message'] = "Course deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete course.";
    }
    header("Location: manage-courses.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses</title>
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="../assets/admin_sidebar/sidebar.css?v=<?php echo $version; ?>">
    </head>
<body>
   <?php include '../assets/admin_sidebar/sidebar.php';?>

  <div class="dashboard-container">
           <h2 onclick="history.back()"><i class="fa-solid fa-arrow-left"></i><a href="#">Manage Courses</a></h2>
        
        <?php
        if (isset($_SESSION['message'])) {
            echo "<p class='success'>" . $_SESSION['message'] . "</p>";
            unset($_SESSION['message']);
        }
        if (isset($_SESSION['error'])) {
            echo "<p class='error'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);
        }
        ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Actions</th>
                </tr>
            </thead>

            
            <tbody>
                <?php foreach ($courses as $course): ?>
                <tr>
                    <td><?php echo $course['id']; ?></td>
                    <td><?php echo htmlspecialchars($course['title']); ?></td>
                    <td>
                        <a href="edit-course.php?id=<?php echo $course['id']; ?>" class="edit-btn" style="  border-right: 1px solid #8d94e6; padding: 10px;"><i class="fa-regular fa-pen-to-square"></i>Edit</a>
                        <a href="course-users.php?id=<?php echo $course['id']; ?>" class="dwn-btn" style="  border-right: 1px solid #8d94e6; padding: 10px;"><i class="fa-regular fa-user"></i>Enrolled Users</a>
                <form action="manage-courses.php" method="post" style="display:inline;">
                <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                <button type="submit" name="delete_course" onclick="return confirm('Are you sure you want to delete this course?');" style="background-color:red;">Delete</button>
                </form>

                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>