<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Academic Resource Portal</title>
    <link rel="stylesheet" href="assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="icon" type="image/jpg" href="https://saifali.sirv.com/favicon/favicon-32x32.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
       <header>
        <?php include 'includes/header.php'; ?>
    </header>

    <div class="faq-container-01">
        <h1>Frequently Asked Questions</h1>
        
        <div class="search-container">
            <input type="text" id="search" placeholder="Search FAQ...">
            <div id="search-suggestions"></div>
        </div>

        <div class="category-chips">
            <div class="category-chip active" data-category="">All Categories</div>
            <div class="category-chip" data-category="general">General</div>
            <div class="category-chip" data-category="resources">Resources</div>
            <div class="category-chip" data-category="resources">Courses</div>
            <div class="category-chip" data-category="events">Events</div>
            <div class="category-chip" data-category="account">Account</div>
        </div>

        <h2 class="category-header">General</h2>

        <div class="faq-item" data-search="what is academic resource portal" data-category="general">
            <input type="checkbox" id="q1">
            <label class="faq-question" for="q1">What is the Academic Resource Portal?</label>
            <p class="faq-answer">The Academic Resource Portal is an online platform where users can share and download a wide variety of academic resources, such as books, notes, and exam questions. Additionally, the portal now features an "Events" section, offering access to academic-related events like workshops, webinars, and hackathons.Now we have a new "Courses" section where users can enroll and watch educational videos.</p>
        </div>

        <div class="faq-item" data-search="is the portal free to use cost" data-category="general">
            <input type="checkbox" id="q2">
            <label class="faq-question" for="q2">Is the portal free to use?</label>
            <p class="faq-answer">Yes, the Academic Resource Portal is completely free for everyone. We believe in making knowledge accessible to all.</p>
        </div>

        <div class="faq-item" data-search="who can use academic resource portal" data-category="general">
            <input type="checkbox" id="q3">
            <label class="faq-question" for="q3">Who can use the Academic Resource Portal?</label>
            <p class="faq-answer">The portal is open to students, educators, researchers, and anyone interested in academic resources. It's designed to cater to a wide range of academic levels and subjects.</p>
        </div>

        <div class="faq-item" data-search="how to get started on academic resource portal" data-category="general">
            <input type="checkbox" id="q4">
            <label class="faq-question" for="q4">How do I get started on the Academic Resource Portal?</label>
            <p class="faq-answer">To get started, simply create an account on our website. Once registered, you can browse resources, download materials, and participate in events. If you wish to contribute, you can also upload your own resources.</p>
        </div>

        <div class="faq-item" data-search="is there a mobile app for academic resource portal" data-category="general">
            <input type="checkbox" id="q5">
            <label class="faq-question" for="q5">Is there a mobile app for the Academic Resource Portal?</label>
            <p class="faq-answer">Currently, we don't have a dedicated mobile app, but our website is fully responsive and optimized for mobile devices. You can access all features through your mobile browser.</p>
        </div>

        <h2 class="category-header">Resources</h2>

        <div class="faq-item" data-search="how to upload academic resources" data-category="resources">
            <input type="checkbox" id="q6">
            <label class="faq-question" for="q6">How do I upload my own academic resources?</label>
            <p class="faq-answer">After logging in, navigate to your dashboard and click on "Upload Resource". Fill in the necessary details about your resource, select the file you want to upload, and submit. Our team will review the submission before making it available on the portal.</p>
        </div>

        <div class="faq-item" data-search="limit on resource uploads" data-category="resources">
            <input type="checkbox" id="q7">
            <label class="faq-question" for="q7">Is there a limit to how many resources I can upload?</label>
            <p class="faq-answer">There is no strict limit on the number of resources you can upload. However, we encourage quality over quantity. All uploads are subject to review to maintain the quality of our database.</p>
        </div>

        <div class="faq-item" data-search="types of resources available" data-category="resources">
            <input type="checkbox" id="q8">
            <label class="faq-question" for="q8">What types of resources can I find on the portal?</label>
            <p class="faq-answer">Our portal hosts a wide variety of academic resources including textbooks, lecture notes, past exam papers, research papers, study guides, and more. These cover various subjects and academic levels.</p>
        </div>

        <div class="faq-item" data-search="resource vetting process" data-category="resources">
            <input type="checkbox" id="q9">
            <label class="faq-question" for="q9">How are resources vetted before being published?</label>
            <p class="faq-answer">All uploaded resources go through a review process by our team. We check for relevance, quality, and copyright issues. This helps ensure that all materials on our portal are valuable and legally shared.</p>
        </div>

        <div class="faq-item" data-search="download resources without account" data-category="resources">
            <input type="checkbox" id="q10">
            <label class="faq-question" for="q10">Can I download resources without an account?</label>
            <p class="faq-answer">To maintain the quality and security of our portal, we require users to create a free account before downloading resources. This also allows us to provide personalized recommendations and track your downloads.</p>
        </div>

<!-- for course section -->
      <h2 class="category-header">courses</h2>

        <div class="faq-item" data-search="how can i enroll in courses?" data-category="courses">
            <input type="checkbox" id="q21">
            <label class="faq-question" for="21">How can I enroll in courses?</label>
            <p class="faq-answer">To enroll in a course, simply sign in or register on the portal, navigate to the "Courses" section, and select the course you're interested in. Once enrolled, you can access video content.</p>
        </div>

        <div class="faq-item" data-search="what are the benefits of the courses section?" data-category="courses">
            <input type="checkbox" id="q22">
            <label class="faq-question" for="q22">What are the benefits of the Courses section?</label>
            <p class="faq-answer">The Courses section offers a structured way to learn with video modules divided into chapters. Users can track their progress, resume courses where they left off, and learn at their own pace.</p>
        </div>

        <div class="faq-item" data-search="track my course progress?" data-category="courses">
            <input type="checkbox" id="q23">
            <label class="faq-question" for="23">Can I track my course progress?</label>
            <p class="faq-answer">Yes, once you enroll in a course, your progress will be saved. You can continue from where you last left off, and track your progress in your user panel.</p>
        </div>

<!-- end of course section -->


        <h2 class="category-header">Events</h2>

        <div class="faq-item" data-search="how to access events section" data-category="events">
            <input type="checkbox" id="q11">
            <label class="faq-question" for="q11">How can I access the events section?</label>
            <p class="faq-answer">Once logged in, you'll find an "Events" tab in the main navigation menu. Click on this to view all upcoming and past events.</p>
        </div>

        <div class="faq-item" data-search="who can participate in events" data-category="events">
            <input type="checkbox" id="q12">
            <label class="faq-question" for="q12">Who can participate in these events?</label>
            <p class="faq-answer">All registered users of the portal can participate in the listed events. Some events might have specific requirements or be targeted at particular academic levels, which will be clearly stated in the event description.</p>
        </div>

        <div class="faq-item" data-search="are events free to attend" data-category="events">
            <input type="checkbox" id="q13">
            <label class="faq-question" for="q13">Are the events free to attend?</label>
            <p class="faq-answer">Many events on our portal are free to attend. However, some specialized workshops or webinars might have a nominal fee. The cost, if any, will be clearly indicated on the event page.</p>
        </div>

        <div class="faq-item" data-search="submit own events" data-category="events">
            <input type="checkbox" id="q14">
            <label class="faq-question" for="q14">Can I submit my own events?</label>
            <p class="faq-answer">Currently, event submissions are managed by our team only. However, if you have an academic event you'd like to host on our platform, please contact our support team with the details, and we'll be happy to consider it.</p>
        </div>

        <div class="faq-item" data-search="how to register for an event" data-category="events">
            <input type="checkbox" id="q15">
            <label class="faq-question" for="q15">How do I register for an event?</label>
            <p class="faq-answer">To register for an event, navigate to the event page and click the "Register" button. You'll be prompted to confirm your registration and, if applicable, complete any payment process.</p>
        </div>

        <h2 class="category-header">Account</h2>

        <div class="faq-item" data-search="how to create account" data-category="account">
            <input type="checkbox" id="q16">
            <label class="faq-question" for="q16">How do I create an account?</label>
            <p class="faq-answer">To create an account, click on the "Sign Up" button on the top right of our homepage. Fill in the required information, including your email address and a secure password. Verify your email, and you're all set!</p>
        </div>

        <div class="faq-item" data-search="change username" data-category="account">
            <input type="checkbox" id="q17">
            <label class="faq-question" for="q17">Can I change my username?</label>
            <p class="faq-answer">No, you can't change your username.</p>
        </div>

      <!--  <div class="faq-item" data-search="forgot password reset" data-category="account">
            <input type="checkbox" id="q18">
            <label class="faq-question" for="q18">What should I do if I forget my password?</label>
            <p class="faq-answer">If you forget your password, click on the "Forgot Password" link on the login page. Enter the email associated with your account, and we'll send you instructions to reset your password.</p>
        </div> -->

        <div class="faq-item" data-search="delete account" data-category="account">
            <input type="checkbox" id="q19">
            <label class="faq-question" for="q19">How can I delete my account?</label>
            <p class="faq-answer">To delete your account, for now you have to contact us through mail or any other contact options. Please note that this action is irreversible and will remove all your data from our system.</p>
        </div>

        <div class="faq-item" data-search="personal information security" data-category="account">
            <input type="checkbox" id="q20">
            <label class="faq-question" for="q20">Is my personal information secure?</label>
            <p class="faq-answer">Yes, we take data security very seriously. We use industry-standard encryption and security measures to protect your personal information. We never share your data with third parties without your explicit consent.</p>
        </div>

    </div>

    <footer>
        <?php include 'includes/footer.php'; ?>
    </footer>
        <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const suggestionsContainer = document.getElementById('search-suggestions');
        const categoryChips = document.querySelectorAll('.category-chip');
        const faqItems = document.querySelectorAll('.faq-item');
        const categoryHeaders = document.querySelectorAll('.category-header');

        let selectedCategory = '';

        function updateResults() {
            const searchTerm = searchInput.value.toLowerCase();

            faqItems.forEach(item => {
                const matchesSearch = item.dataset.search.toLowerCase().includes(searchTerm);
                const matchesCategory = selectedCategory === '' || item.dataset.category === selectedCategory;
                item.style.display = matchesSearch && matchesCategory ? 'block' : 'none';
            });

            categoryHeaders.forEach(header => {
                const category = header.textContent.toLowerCase();
                const hasVisibleItems = Array.from(faqItems).some(item => 
                    item.style.display === 'block' && item.dataset.category === category
                );
                header.style.display = hasVisibleItems ? 'block' : 'none';
            });
        }

        function showSuggestions() {
            const searchTerm = searchInput.value.toLowerCase();
            suggestionsContainer.innerHTML = '';
            suggestionsContainer.style.display = 'none';

            if (searchTerm.length > 1) {
                const suggestions = Array.from(faqItems)
                    .filter(item => item.dataset.search.toLowerCase().includes(searchTerm))
                    .slice(0, 5)
                    .map(item => item.querySelector('.faq-question').textContent);

                if (suggestions.length > 0) {
                    suggestions.forEach(suggestion => {
                        const div = document.createElement('div');
                        div.className = 'suggestion';
                        div.textContent = suggestion;
                        div.addEventListener('click', () => {
                            searchInput.value = suggestion;
                            suggestionsContainer.style.display = 'none';
                            updateResults();
                        });
                        suggestionsContainer.appendChild(div);
                    });
                    suggestionsContainer.style.display = 'block';
                }
            }
        }

        searchInput.addEventListener('input', function() {
            showSuggestions();
            updateResults();
        });

        categoryChips.forEach(chip => {
            chip.addEventListener('click', function() {
                categoryChips.forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                selectedCategory = this.dataset.category;
                updateResults();
            });
        });

        document.addEventListener('click', function(e) {
            if (!suggestionsContainer.contains(e.target) && e.target !== searchInput) {
                suggestionsContainer.style.display = 'none';
            }
        });

        // Initially show all FAQ items
        updateResults();
    });
    </script>
</body>
</html>