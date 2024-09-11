<?php
require_once 'includes/functions.php';
include 'include/version.php';

$faculties = get_all_faculties();
$semesters = get_all_semesters();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Results</title>
        <link rel="icon" type="image/jpg" href="https://saifali.sirv.com/favicon/favicon-32x32.png">
    <link rel="stylesheet" href="assets/css/styles.css?v=<?php echo $version; ?>">
    <style>
        /* Add styles for the popup and result status */
        .result-popup {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            width: 80%;
            max-width: 500px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .result-popup h3 {
            margin-top: 0;
            color: var(--color-dark-blue);
        }
        .result-popup .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }
        .result-status {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            text-align: center;
            font-weight: bold;
        }
        .pass {
            background-color: var(--color-green);
            color: white;
        }
        .fail {
            background-color: var(--color-red);
            color: white;
        }
        .result-popup .note {
            font-size: 0.8em;
            color: #555;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container-result">
        <h1>Exam Results</h1>
        <form id="resultForm" method="post" class="search-form">
            <input type="text" name="symbol_no" placeholder="Enter Symbol Number" required>
            <select name="faculty_id" class="result-select" required>
                <option value="">Select Faculty</option>
                <?php foreach ($faculties as $faculty): ?>
                    <option value="<?php echo $faculty['id']; ?>"><?php echo htmlspecialchars($faculty['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <select name="semester_id" class="result-select"  required>
                <option value="">Select Semester</option>
                <?php foreach ($semesters as $semester): ?>
                    <option value="<?php echo $semester['id']; ?>"><?php echo htmlspecialchars($semester['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="search-btn">Search</button>
        </form>

        <div id="resultPopup" class="result-popup">
            <span class="close-btn" onclick="closeResultPopup()">&times;</span>
            <h3>Result</h3>
            <div id="resultContent">
                <!-- Result will be loaded here -->
            </div>
        </div>
    </div>
    <h3 class="result-note-head">As of now we only shows you're passed or not.</h3>
<p class="result-note">Please cross-check your result with the official university PDF.</p>

<footer style="margin-top: 500px;">
        <?php include 'includes/footer.php'; ?>
    </footer>

    <script>
        function showResultPopup() {
            document.getElementById("resultPopup").style.display = "block";
        }

        function closeResultPopup() {
            document.getElementById("resultPopup").style.display = "none";
        }

        // AJAX form submission
        const form = document.getElementById('resultForm');
        form.addEventListener('submit', function(event) {
            event.preventDefault(); 

            const formData = new FormData(this);

            fetch('get_result.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('resultContent').innerHTML = data;
                showResultPopup();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again later.');
            });
        });

        // Ensure the popup is hidden by default
        document.addEventListener('DOMContentLoaded', function() {
            closeResultPopup(); 
        });
    </script>

</body>
</html>