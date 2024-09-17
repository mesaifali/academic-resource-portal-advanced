<?php
include 'includes/db.php';
include 'includes/version.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css?v=<?php echo $version; ?>">
<link rel="icon" type="image/jpg" href="https://saifali.sirv.com/favicon/favicon-32x32.png">

    <title>Academic Resource Portal</title>

   <style>
        /* body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100%;
        } */
        .hero {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            height: 100vh;
            background-color: var(--color-gray);
        }
        .tag {
            background-color: #f0f0f0;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .hero-h1 {
            font-size: 48px;
            font-weight: bold;
            margin: 20px 0;
            max-width: 800px;
        }
        .hero-p {
            
            margin-bottom: 30px;
            max-width: 600px;
        }
        .home-cta-button {
            position: relative;
            display: inline-block;
            padding: 2px;
            background: linear-gradient(45deg, #ff00ff, #00ffff);
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            font-size: 18px;
            transition: all 0.3s ease;
            color: black;
        }
        .home-cta-button span {
            display: block;
            padding: 13px 28px;
            background-color: #000;
            color: #fff;
            border-radius: 28px;
            transition: all 0.3s ease;
        }
        .home-cta-button::before {
            opacity: 0;
            transition: all 0.3s ease;
        }
        .home-cta-button:hover::before {
            opacity: 1;
            filter: blur(5px);
         
        }
        .home-cta-button:hover span {
            background-color: rgba(0, 0, 0, 0.8);
    
        }


/* Demo Popup Styling */
.demo-popup {
  display: flex;
  justify-content: center;
  align-items: center;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent backdrop */
  z-index: 1000; /* Ensure popup is on top */
}

.popup-content-home {
  background-color: #fff;
  padding: 30px;
  border-radius: 10px;
  text-align: center;
  position: relative; 
  max-width: 80%; /* Adjust as needed */
}

.close-btn {
  position: absolute;
  top: 10px;
  right: 15px;
  font-size: 25px;
  cursor: pointer;
  color: #888; 
}

.close-btn:hover {
  color: #333; 
}


    </style>

</head>
<body>
    <header>
     <?php include 'includes/header.php'; ?>
    </header>


<div class="demo-popup" id="demoPopup">
    <div class="popup-content-home">
      <span class="close-btn" onclick="closePopup()">&times;</span>
      <h2>This is a Under Construction Version</h2>
      <img src='https://www.usea.edu.kh/under_construction/Under.jpg'>
      <p>Please note that some Context may be limited. </p>
    </div>
  </div>

    <!-- <main>
        <div class="container">
             Left Side - Content 
            <div class="left-content">
                <h1 class="content-heading">
                    Welcome to the<span> Academic <br> Resource</span> Portal
                </h1>
                <div class="content-section">
                    <p>Explore and share top educational resources. Now, discover events like workshops, webinars, and hackathons. Join us to access, contribute, and excel in your academic journey.</p>

                <p>Join our community to delve into a wealth of resources, participate in events, and contribute your own materials to help others succeed in their academic journey.</p>

                </div>
                <a href="resources.php" class="btn-get-started">Explore Resources</a>
            </div>
            Right Side - Image 
            <div class="right-content">
                <img src="https://saifali.sirv.com/Images/book%20and%20glasses.png" alt="Academic Resources">
            </div>
        </div>
    </main> -->
    <div class="hero">
        <div class="tag">Made by Student, for Students</div>
        <h1 class="hero-h1">Quality resources shared by the community</h1>
        <p class="hero-p">Explore and share top educational resources.Discover resources, Events, and Course. Join us to access, contribute, and excel in your academic journey.</p>
        <a href="resources.php" class="home-cta-button"><span>Get access to  <!--4,958--> resources</span></a>
    </div>


    <!-- Featured Resources Section 
    <section class="featured-resources">
        <div class="feature">
            <h2 class="index-head">Featured Resources</h2>
            <div class="resources-grid">
                <div class="home-card">
                    <h3>Latest Textbook</h3>
                    <p>Discover our most recent addition to the library.</p>
                    <a href="#">Learn more</a>
                </div>
                <div class="home-card">
                    <h3>Top Rated Notes</h3>
                    <p>Access highly rated study materials from peers.</p>
                    <a href="#">Explore notes</a>
                </div>
                <div class="home-card">
                    <h3>Practice Questions</h3>
                    <p>Test your knowledge with our curated question sets.</p>
                    <a href="#">Start practicing</a>
                </div>
            </div>
        </div>
    </section> -->

    <!-- How It Works Section
    <section class="how-it-works">
        <div class="how-it">
            <h2 class="index-head">How It Works</h2>
            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3>Sign Up</h3>
                    <p>Create your free account to get started.</p>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <h3>Browse Resources</h3>
                    <p>Explore our vast library of academic materials.</p>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <h3>Download or Share</h3>
                    <p>Access resources or contribute your own.</p>
                </div>
            </div>
        </div>
    </section>  -->

<!-- How It Works Section -->
<section class="how-it-works">
    <div class="how-it">
        <div class="header-container">
            <div class="tag">OUR STREAMLINED APPROACH</div>
            <h2 class="how-head">How It Works</h2>
        </div>
        <p class="subtitle">Simplify your academic journey with our efficient three-step process</p>
        <div class="steps">
            <div class="step">
                <div class="step-icon">
                    <img src="https://saifali.sirv.com/1up/business/MANIK%20-%20Business%20%26%20Teamwork%20Illustration%20Pack-06.png" alt="Sign Up">
                </div>
                <h3>Sign Up</h3>
                <p>Create your free account to get started and unlock a world of academic resources.</p>
            </div>
            <div class="step">
                <div class="step-icon">
                    <img src="https://saifali.sirv.com/1up/business/MANIK%20-%20Business%20%26%20Teamwork%20Illustration%20Pack-06.png" alt="Browse Resources">
                </div>
                <h3>Browse Resources</h3>
                <p>Explore our vast library of academic materials tailored to your needs.</p>
            </div>
            <div class="step">
                <div class="step-icon">
                    <img src="https://saifali.sirv.com/1up/business/MANIK%20-%20Business%20%26%20Teamwork%20Illustration%20Pack-06.png" alt="Download or Share">
                </div>
                <h3>Download or Share</h3>
                <p>Access resources instantly or contribute your own to help fellow students.</p>
            </div>
        </div>
    </div>
</section>





    <!-- Statistics Section -->
    <section class="statistics">
        <div class="stat-container">
            <h2 class="index-head">Our Impact</h2>
            <div class="stats-grid">
                <div class="stat-item">
                    <p>10,000+</p>
                    <p>Registered Users</p>
                </div>
                <div class="stat-item">
                    <p>50,000+</p>
                    <p>Resources Shared</p>
                </div>
                <div class="stat-item">
                    <p>100+</p>
                    <p>Academic Subjects</p>
                </div>
                <div class="stat-item">
                    <p>1M+</p>
                    <p>Downloads</p>
                </div>
            </div>
        </div>
    </section>

 <!-- New Community Section -->
    <section class="community">
        <div class="com-container">
            <h2 class="index-head">Join Our Academic Community</h2>
            <div class="community-content">
                <div class="community-text">
                    <h3 style="font-weight:500">Connect with fellow students and educators from around the world. Share your knowledge, ask questions, and collaborate on academic projects.</h3>
                    <ul class="ul-list">
                        <li>Participate in discussion forums</li>
                        <li>Join study groups</li>
                        <li>Attend virtual workshops and webinars</li>
                        <li>Contribute to peer review processes</li>
                    </ul>
                    <a href="discussion-forum.php" class="cta-button" style="padding-top:20px">Join Community</a>
                </div>
                <div class="community-image">
                    <img src="https://saifali.sirv.com/1up/business/MANIK%20-%20Business%20%26%20Teamwork%20Illustration%20Pack-06.png" alt="Academic community illustration">
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section 
    <section class="testimonials">
        <div class="testi-container">
            <h2 class="index-head">What Our Users Say</h2>
            <div class="testimonials-grid">
                <div class="testimonial">
                    <p>"This portal has been a game-changer for my studies. The quality of resources is outstanding!"</p>
                    <p><strong>- Sarah J., University Student</strong></p>
                </div>
                <div class="testimonial">
                    <p>"As a teacher, I find the shared materials invaluable for lesson planning and student support."</p>
                    <p><strong>- Mark T., High School Teacher</strong></p>
                </div>
                <div class="testimonial">
                    <p>"The practice questions have significantly improved my exam performance. Highly recommended!"</p>
                    <p><strong>- Emily R., Graduate Student</strong></p>
                </div>
            </div>
        </div>
    </section>-->

    <!-- Call to Action Section
    <section class="cta">
        <div class="cta-container">
            <h2 class="index-head">Ready to Elevate Your Academic Journey?</h2>
            <p>Join our community of learners and educators today!</p>
            <a href="signin.php" class="cta-button">Sign Up Now</a>
        </div>
    </section> -->

        <footer>
        <?php include 'includes/footer.php'; ?>
    </footer>


    <script>
// Close Popup Function
function closePopup() {
  document.getElementById("demoPopup").style.display = "none";
}
    </script>
</body>
</html>
