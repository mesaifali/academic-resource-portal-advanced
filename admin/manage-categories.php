<?php
session_start();
require_once '../includes/functions.php';

checkAdminSession();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_category'])) {
        $name = sanitizeInput($_POST['category_name']);
        add_course_category($name);
    } elseif (isset($_POST['delete_category'])) {
        $id = (int)$_POST['category_id'];
        delete_course_category($id);
    }
}

$categories = get_course_categories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Course Categories</title>
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="../assets/admin_sidebar/sidebar.css?v=<?php echo $version; ?>">
    </head>
<body>
 
<?php include '../assets/admin_sidebar/sidebar.php';?>
  <div class="dashboard-container">
       <h2 onclick="history.back()"><i class="fa-solid fa-arrow-left"></i><a href="#">Manage Course Categories</a></h2>

        
        <form action="" method="post">
        <div class="form-group">
            <input type="text" name="category_name" placeholder="New Category Name" required>
            <button type="submit"class="btn-get-started" name="add_category">Add Category</button>
            </div>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?php echo $category['id']; ?></td>
                    <td><?php echo htmlspecialchars($category['name']); ?></td>
                    <td>
                        <form action="" method="post" onsubmit="return confirm('Are you sure you want to delete this category?');">
                            <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
                            <button type="submit" name="delete_category">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</body>
</html>