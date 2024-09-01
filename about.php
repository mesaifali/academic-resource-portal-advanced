<?php
include 'includes/db.php';
include 'include/version.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="https://saifali.sirv.com/favicon/favicon-32x32.png">
    <link rel="stylesheet" href="assets/css/styles.css?v=<?php echo $version; ?>">
    <title>About Us - Academic Resource Portal</title>
    <script>
        // FAQ toggle script
        document.addEventListener('DOMContentLoaded', function () {
            const faqs = document.querySelectorAll('.faq');
            faqs.forEach(faq => {
                faq.querySelector('h4').addEventListener('click', function () {
                    faq.classList.toggle('active');
                });
            });
        });
    </script>
</head>
<body>
<header>
        <?php include 'includes/header.php'; ?>
</header>
    <main>
        <!-- About Us Section -->
        <div class="about-container">
            <div class="about-image">
                <img src="https://saifali.sirv.com/1up/stand/stand.spin?image=20&gif.lossy=0&w=500&h=500" alt="About Us">
            </div>
            <div class="about-content">
                <h2>About Us</h2>
                <h3>Your Hub for Academic Resources and Events</h3>
                <p>At the Academic Resource Portal, we’re dedicated to empowering students, educators, and researchers with top-quality academic materials and opportunities. Our platform not only provides access to essential resources like textbooks, notes, and exam questions but now also connects you to enriching academic events such as workshops, webinars, and hackathons. We believe in the power of knowledge and the importance of continuous learning. Join us as we make education a dynamic, lifelong journey.</p>
            </div>
        </div>

        <!-- Who We Are Section -->
        <div class="team-container">
            <h2>Who We Are</h2>
            <div class="team-card">
                <div class="team-member">
                    <img src="https://saifali.sirv.com/1up/avatar/20.%20Stylish%20Young%20Man.png" alt="Saif">
                    <h3>Saif Ali -- Founder</h3>
                    <p>Full Stack Developer with a passion for building web applications that solve real-world problems.</p>
                    <a href="https://saif.com">View Portfolio</a>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-github"></i></a>
                    </div>
                </div>
                <div class="team-member">
                    <img src="https://saifali.sirv.com/1up/avatar/1.%20Police.png" alt="saif">
                    <h3>Groot Ali</h3>
                    <p>UI/UX Designer focused on creating intuitive and user-friendly interfaces that enhance the user experience.</p>
                    <a href="https://portfolio.janesmith.com">View Portfolio</a>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-dribbble"></i></a>
                    </div>
                </div>
                <div class="team-member">
                    <img src="https://saifali.sirv.com/1up/avatar/20.%20Stylish%20Young%20Man.png" alt="Johnson">
                    <h3>Johnson</h3>
                    <p>Backend Developer with expertise in database management and server-side logic. Ensuring security and efficiency.</p>
                    <a href="https://portfolio.emilyjohnson.com">View Portfolio</a>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-github"></i></a>
                    </div>
                </div>
            </div>
        </div>

         <!-- FAQ Section -->
   <div class="faq-container-01">
        <h1>Frequently Asked Questions</h1>

        <div class="faq-item" data-search="what is your return policy">
            <input type="checkbox" id="q1">
            <label class="faq-question" for="q1">What is the Academic Resource Portal?</label>
            <p class="faq-answer">The Academic Resource Portal is an online platform where users can share and download a wide variety of academic resources, such as books, notes, and exam questions. Additionally, the portal now features an "Events" section, offering access to academic-related events like workshops, webinars, and hackathons.</p>
        </div>

        <div class="faq-item" data-search="how long does shipping take">
            <input type="checkbox" id="q2">
            <label class="faq-question" for="q2">Is the portal free to use?</label>
            <p class="faq-answer">Yes, the Academic Resource Portal is completely free for everyone. We believe in making knowledge accessible to all.</p>
        </div>

        <div class="faq-item" data-search="do you offer international shipping">
            <input type="checkbox" id="q3">
            <label class="faq-question" for="q3">How do I upload my own academic resources?</label>
            <p class="faq-answer">After logging in, click on "Upload Resource" in your dashboard, fill in the necessary details, and submit your material.</p>
        </div>
        <a href="faq.php" class="btn-get-started" style="margin-left: 40%;">View more</a>

    </main>
    <footer>
        <?php include 'includes/footer.php'; ?>
    </footer>
</body>
</html>


