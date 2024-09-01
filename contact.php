<?php include 'includes/version.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="icon" type="image/jpg" href="https://saifali.sirv.com/favicon/favicon-32x32.png">
    <title>Contact Us - Academic Resource Portal</title>
</head>
<body>
    <header>
        <?php include 'includes/header.php'; ?>
    </header>
    
    <main style="padding: 20px 266px 266px 266px;">
        <section class="contact-container">
            <form action="https://formspree.io/f/xjkbaglg" method="POST">
                <h2>Get in Touch</h2>
                <div class="contact-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="contact-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="contact-group">
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>
                <button type="submit" class="button">Send</button>
            </form>
        </section>
    </main>
   <footer>
        <?php include 'includes/footer.php'; ?>
    </footer>
</body>
</html>


