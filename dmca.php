<?php include 'includes/version.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DMCA Policy</title> 
<link rel="icon" type="image/jpg" href="https://saifali.sirv.com/favicon/favicon-32x32.png">
    <link rel="stylesheet" href="assets/css/styles.css?v=<?php echo $version; ?>">
    <style>

.dmca-container {
    max-width: 900px;
    margin: 50px auto;
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

h1, h2 {
    color: var(--color-blue);
    text-align: center;
}

h2 {
    margin-top: 40px;
}

ul {
    padding-left: 20px;
}

ul li {
    margin: 10px 0;
}

p {
    line-height: 1.6;
}

strong {
    color: var(--color-blue);
}

.dmca-form {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-top: 20px;
}

.dmca-form label {
    font-weight: bold;
}

.dmca-form .required {
    color: var(--color-red);
    margin-left: 2px;
}

.dmca-form input[type="text"],
.dmca-form input[type="email"],
.dmca-form input[type="tel"],
.dmca-form input[type="url"],
.dmca-form textarea {
    padding: 10px;
    border: 1px solid var(--form-border);
    border-radius: 4px;
    background-color: var(--input-bg);
    font-size: 16px;
    width: 100%;
    box-sizing: border-box;
}

.dmca-form textarea {
    resize: vertical;
}

    </style>
</head>
<body>
<header>
        <?php include 'includes/header.php'; ?>
        </header>

    <div class="dmca-container">
        <h1 style="color:var(--color-blue);">DMCA Policy</h1>
        <p>
            At Academic Resource Portal, we respect the intellectual property rights of others and comply with the Digital Millennium Copyright Act (DMCA).
            If you believe that your work has been copied in a way that constitutes copyright infringement, please notify us using the form below.
        </p>
        
        <h2>Filing a DMCA Complaint</h2>
        <p>To file a DMCA complaint, please provide the following information in the form:</p>
        <ul>
            <li>Your full name and contact information (email, phone, and address).</li>
            <li>A description of the copyrighted work that you claim has been infringed.</li>
            <li>The exact link (URL) where the infringing content is located.</li>
            <li>A statement of good faith that you believe the use is unauthorized.</li>
            <li>Your electronic or physical signature.</li>
        </ul>
        <p>
            Alternatively, you can send the complaint to our email address: <strong>help.academicresources@gmail.com</strong>.
        </p>
        
        <h2>DMCA Complaint Form</h2>
        <form action="https://formspree.io/f/xrbzdjaz" method="POST" class="dmca-form">
            <label for="full_name">Full Name<span class="required">*</span>:</label>
            <input type="text" id="full_name" name="full_name" required>

            <label for="email">Email Address<span class="required">*</span>:</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone">

            <label for="address">Address:</label>
            <textarea id="address" name="address" rows="2"></textarea>

            <label for="copyright_work">Description of Copyrighted Work<span class="required">*</span>:</label>
            <textarea id="copyright_work" name="copyright_work" 
                      required rows="3"></textarea>

            <label for="infringing_url">URL of Infringing Content<span class="required">*</span>:</label>
            <input type="url" id="infringing_url" name="infringing_url" required>

            <label for="good_faith">Statement of Good Faith<span class="required">*</span>:</label>
            <textarea id="good_faith" name="good_faith" 
                      required rows="3">I believe in good faith that the use of the material in the manner complained of is not authorized by the copyright owner, its agent, or the law.</textarea>

            <label for="signature">Signature<span class="required">*</span>:</label>
            <input type="text" id="signature" name="signature" 
                   placeholder="Type your full name as a signature" required>

            <button type="submit" class="button">Submit Complaint</button>
        </form>

        <h2>Counter Notification</h2>
        <p>If you believe that the material was mistakenly removed, you can file a counter-notification with the following information:</p>
        <ul>
            <li>Your full name and contact information.</li>
            <li>The material's location before it was removed.</li>
            <li>A statement of good faith that you believe the removal was in error.</li>
            <li>Your electronic or physical signature.</li>
        </ul>
        <p>Contact us for further assistance.</p>
    </div>
       <footer>
        <?php include 'includes/footer.php'; ?>
    </footer>
</body>
</html>
