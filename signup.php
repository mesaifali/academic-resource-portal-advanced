<?php
include 'includes/db.php';
include 'includes/version.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $profile_picture = $_FILES['profile_picture']['name'];

    $target_dir = "uploads/profile_picture/";
    $target_file = $target_dir . basename($profile_picture);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $file_temp = $_FILES['profile_picture']['tmp_name'];
    $file_size = $_FILES['profile_picture']['size'];

    // Validate file type and size
    $allowed_types = ['jpg', 'jpeg', 'png'];
    if (in_array($imageFileType, $allowed_types) && $file_size <= 5000000) { // 5MB limit
        // Check if the uploaded file is an actual image
        $check = getimagesize($file_temp);
        if ($check !== false) {
            if (move_uploaded_file($file_temp, $target_file)) {
                $sql = "INSERT INTO users (name, username, email, phone, profile_picture, password) 
                        VALUES ('$name', '$username', '$email', '$phone', '$profile_picture', '$password')";

                if ($conn->query($sql) === TRUE) {
                    header("Location: signin.php");
                    exit();
                } else {
                    $error = "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        } else {
            $error = "File is not an image.";
        }
    } else {
        $error = "Only JPG, JPEG, and PNG files are allowed, and the file size must be less than 5MB.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css?v=<?php echo $version; ?>">
    <title>Sign Up - Academic Resource Portal</title>
    <link rel="icon" type="image/jpg" href="https://saifali.sirv.com/favicon/favicon-32x32.png">
</head>
<body>
    <header>
        <?php include 'includes/header.php'; ?>
    </header>
    <main>
        <div class="sign-container">
            <form action="signup.php" method="POST" enctype="multipart/form-data">
                <h2>Sign Up</h2>
                <?php if (isset($error)): ?>
                    <p class="message"><?php echo $error; ?></p>
                <?php endif; ?>
                <div class="sign-group">
                    <label for="name">Full Name:</label>
                    <input type="text" name="name" id="name" placeholder="Full Name" required>
                </div>
                <div class="sign-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" placeholder="Username" required>
                </div>
                <div class="sign-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" placeholder="Email" required>
                </div>
                <div class="sign-group">
                    <label for="phone">Phone Number:</label>
                    <input type="number" name="phone" id="phone" placeholder="Phone Number" maxlength="10" required>
                </div>
                <div class="sign-group">
                    <label for="profile_picture">Profile Picture:</label>
                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*" required>
                </div>
                <div class="sign-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" placeholder="Password" required>
                </div>
                <button type="submit" class="button">Sign Up</button>
                <p>Already have an account? <a href="signin.php">Sign In</a></p>
            </form>
        </div>
    </main>
</body>
</html>
