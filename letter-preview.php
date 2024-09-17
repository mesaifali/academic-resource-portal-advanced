<?php
session_start();
include 'includes/version.php';

if (!isset($_SESSION['letterData'])) {
    header("Location: letter.php");
    exit();
}

$letterData = $_SESSION['letterData'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Letter Preview - Academic Resource</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
     <link rel="icon" type="image/jpg" href="https://saifali.sirv.com/favicon/favicon-32x32.png">
    <link rel="stylesheet" href="assets/css/styles.css?v=<?php echo $version; ?>">
</head>
<body>
<header>
     <?php include 'includes/header.php'; ?>
    </header>
    <div class="ql-container">
        <h1 class="ql-title ql-screen-only">Letter Preview</h1>
        <div class="ql-letter-preview" id="letterPreview">
            <div class="ql-letter">
                <p class="ql-letter-date">Date: <?php echo htmlspecialchars($letterData['date']); ?></p>
                <p class="ql-letter-to">To: <?php echo htmlspecialchars($letterData['to']); ?></p>
                <p class="ql-letter-from">From: <?php echo htmlspecialchars($letterData['from']); ?></p>
                <p class="ql-letter-subject">Subject: <?php echo htmlspecialchars($letterData['subject']); ?></p>
                <div class="ql-letter-body"><?php echo nl2br(htmlspecialchars($letterData['body'])); ?></div>
            </div>
        </div>
        <div class="ql-button-group ql-screen-only">
            <button onclick="window.print()" class="ql-button">Download Letter</button>
        </div>
    </div>
      <footer>
        <?php include 'includes/footer.php'; ?>
    </footer>
</body>
</html>