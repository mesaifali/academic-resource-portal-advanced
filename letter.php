<?php
session_start();
include 'includes/version.php';

$letterTypes = [
    'general' => 'General Letter',
    'application' => 'Job Application',
    'recommendation' => 'Recommendation Letter',
    'complaint' => 'Complaint Letter'
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['letterData'] = [
        'type' => $_POST["letterType"],
        'date' => $_POST["date"],
        'to' => $_POST["to"],
        'from' => $_POST["from"],
        'subject' => $_POST["subject"],
        'body' => $_POST["body"]
    ];

    header("Location: letter-preview.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Letter - Academic Resource</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/jpg" href="https://saifali.sirv.com/favicon/favicon-32x32.png">
     <link rel="stylesheet" href="assets/css/styles.css?v=<?php echo $version; ?>">
</head>
<body>
<header>
     <?php include 'includes/header.php'; ?>
    </header>
    <div class="ql-container">
        <h1 class="ql-title">Quick Letter Generator</h1>
        <form class="ql-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="ql-form-group">
                <label for="letterType" class="ql-label">Letter Type:</label>
                <select id="letterType" name="letterType" class="ql-select" required>
                    <?php foreach ($letterTypes as $value => $label): ?>
                        <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="ql-form-group">
                <label for="date" class="ql-label">Date:</label>
                <input type="date" id="date" name="date" class="ql-input" required>
            </div>
            <div class="ql-form-group">
                <label for="to" class="ql-label">To:</label>
                <input type="text" id="to" name="to" class="ql-input" required>
            </div>
            <div class="ql-form-group">
                <label for="from" class="ql-label">From:</label>
                <input type="text" id="from" name="from" class="ql-input" required>
            </div>
            <div class="ql-form-group">
                <label for="subject" class="ql-label">Subject:</label>
                <input type="text" id="subject" name="subject" class="ql-input" required>
            </div>
            <div class="ql-form-group">
                <label for="body" class="ql-label">Body:</label>
                <textarea id="body" name="body" class="ql-textarea" rows="5" required></textarea>
            </div>
            <button type="submit" class="ql-button">Generate Letter</button>
        </form>
    </div>

    <script>
        document.getElementById('letterType').addEventListener('change', function() {
            var bodyTemplate = '';
            switch(this.value) {
                case 'application':
                    bodyTemplate = "Dear Hiring Manager,\n\nI am writing to apply for the [Position] role at [Company Name]. With my background in [Relevant Field], I believe I would be a strong candidate for this position.\n\n[Your qualifications and experience]\n\nThank you for your consideration. I look forward to the opportunity to discuss how I can contribute to your team.\n\nSincerely,\n[Your Name]";
                    break;
                case 'recommendation':
                    bodyTemplate = "Dear [Recipient],\n\nI am writing to recommend [Name] for [Position/Opportunity]. I have known [Name] for [duration] in my capacity as [Your Relationship].\n\n[Positive qualities and achievements]\n\nI strongly recommend [Name] for [Position/Opportunity]. Please feel free to contact me if you need any further information.\n\nSincerely,\n[Your Name]";
                    break;
                case 'complaint':
                    bodyTemplate = "Dear Sir/Madam,\n\nI am writing to express my dissatisfaction with [Product/Service] that I [purchased/experienced] on [Date].\n\n[Details of the issue]\n\nI would appreciate your prompt attention to this matter. I look forward to your response and a resolution to my problem.\n\nSincerely,\n[Your Name]";
                    break;
                default:
                    bodyTemplate = "Dear [Recipient],\n\n[Your message here]\n\nSincerely,\n[Your Name]";
            }
            document.getElementById('body').value = bodyTemplate;
        });
    </script>
      <footer>
        <?php include 'includes/footer.php'; ?>
    </footer>
</body>
</html>    