<?php
include 'includes/db.php';
include 'includes/version.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $profile_picture = $_FILES['profile_picture']['name'];

    // Password validation
    $password_regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-z\d!@#$%^&*()_+]{8,}$/';
    if (!preg_match($password_regex, $password)) {
        $error = "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one symbol.";
    } else {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $error = "Username already taken.";
        } else {
            // Check if email already exists
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $error = "Email already taken.";
            } else {
                $password = password_hash($password, PASSWORD_BCRYPT);

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
                            $stmt = $conn->prepare("INSERT INTO users (name, username, email, phone, profile_picture, password) VALUES (?, ?, ?, ?, ?, ?)");
                            $stmt->bind_param("ssssss", $name, $username, $email, $phone, $profile_picture, $password);

                            if ($stmt->execute()) {
                                header("Location: signin.php");
                                exit();
                            } else {
                                $error = "Error: " . $stmt->error;
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
        }
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <?php include 'includes/header.php'; ?>
    </header>
    <main>
        <div class="sign-container">
            <form action="signup.php" method="POST" enctype="multipart/form-data" id="signupForm">
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
                    <span id="username-status"></span>
                    <span id="username-requirements"></span>
                </div>
                <div class="sign-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" placeholder="Email" required>
                    <span id="email-status"></span>
                    <span id="email-requirements"></span>
                </div>
                <div class="sign-group">
                    <label for="phone">Phone Number:</label>
                    <input type="number" name="phone" id="phone" placeholder="Phone Number" maxlength="10" required>
                </div>
                <div class="sign-group">
                    <label for="profile_picture">Profile Picture:</label>
                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*" required>
                    <img id="profile-picture-preview" style="display:none; max-width:60px; max-height:60px; margin-top:4px;">
                    <span id="profile-picture-status"></span>
                </div>
                <div class="sign-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <div id="password-strength"></div>
                    <div id="password-criteria">
                        <p><span id="length-check">○</span> At least 8 characters long</p>
                        <p><span id="uppercase-check">○</span> At least one uppercase letter</p>
                        <p><span id="lowercase-check">○</span> At least one lowercase letter</p>
                        <p><span id="number-check">○</span> At least one number</p>
                        <p><span id="symbol-check">○</span> At least one symbol</p>
                    </div>
                </div>
                <button type="submit" class="button">Sign Up</button>
                <p>Already have an account? <a href="signin.php">Sign In</a></p>
            </form>
        </div>
    </main>
    <script src="assets/js/signup-validation.js"></script>
</body>
</html>