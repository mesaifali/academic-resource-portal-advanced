<?php
session_start();
require_once '../includes/functions.php';
checkAdminSession();

$faculties = get_all_faculties();
$semesters = get_all_semesters();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_faculty'])) {
        $faculty_name = sanitizeInput($_POST['faculty_name']);
        if (add_faculty($faculty_name)) {
            $success = "Faculty added successfully.";
            $faculties = get_all_faculties(); // Refresh faculties list
        } else {
            $error = "Error adding faculty.";
        }
    } elseif (isset($_POST['add_semester'])) {
        $faculty_id = (int)$_POST['faculty_id'];
        $semester_name = sanitizeInput($_POST['semester_name']);
        if (add_semester($faculty_id, $semester_name)) {
            $success = "Semester added successfully.";
            $semesters = get_all_semesters(); // Refresh semesters list
        } else {
            $error = "Error adding semester.";
        }
    } elseif (isset($_POST['add_result'])) {
        $faculty_id = (int)$_POST['faculty_id'];
        $semester_id = (int)$_POST['semester_id'];
        $batch_year = (int)$_POST['batch_year'];
        $result_date = sanitizeInput($_POST['result_date']);
        $input_method = sanitizeInput($_POST['input_method']);

        // Get all students for this faculty, semester, and batch year
        $all_students = get_students_by_faculty_semester_batch($faculty_id, $semester_id, $batch_year);
        $all_symbol_numbers = array_column($all_students, 'symbol_no');

        $result_id = add_result($faculty_id, $semester_id, $batch_year, $result_date, $input_method);

        if ($result_id) {
            if ($input_method == 'file') {
                $target_dir = "../uploads/results/";
                $target_file = $target_dir . basename($_FILES["result_file"]["name"]);
                $uploadOk = 1;
                $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                // Check if file already exists
                if (file_exists($target_file)) {
                    $error = "Sorry, file already exists.";
                    $uploadOk = 0;
                }

                // Check file size
                if ($_FILES["result_file"]["size"] > 500000) { // 500KB limit
                    $error = "Sorry, your file is too large.";
                    $uploadOk = 0;
                }

                // Allow certain file formats
                if ($fileType != "txt") {
                    $error = "Sorry, only TXT files are allowed.";
                    $uploadOk = 0;
                }

                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 1) {
                    if (move_uploaded_file($_FILES["result_file"]["tmp_name"], $target_file)) {
                        $passed_symbol_numbers = [];
                        if (add_student_results_from_file($result_id, $target_file, $passed_symbol_numbers)) {
                            // Add "fail" entries for students not in the file
                            add_failed_students($result_id, $all_symbol_numbers, $passed_symbol_numbers);
                            $success = "The results have been uploaded and processed.";
                        } else {
                            $error = "Error processing the uploaded file.";
                        }
                    } else {
                        $error = "Sorry, there was an error uploading your file.";
                    }
                }
            } elseif ($input_method == 'manual') {
                $symbol_numbers = preg_split('/\s+/', sanitizeInput($_POST['symbol_numbers']));
                $passed_symbol_numbers = [];
                foreach ($symbol_numbers as $symbol_no) {
                    if (!empty($symbol_no)) {
                        add_student_result($result_id, $symbol_no, 'pass');
                        $passed_symbol_numbers[] = $symbol_no;
                    }
                }
                // Add "fail" entries for students not manually entered
                add_failed_students($result_id, $all_symbol_numbers, $passed_symbol_numbers);
                $success = "The results have been added.";
            }
        } else {
            $error = "Error adding results.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Result</title>
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="../assets/admin_sidebar/sidebar.css?v=<?php echo $version; ?>">
</head>
<body>
    <?php include '../assets/admin_sidebar/sidebar.php'; ?>

    <div class="dashboard-container">
        <h2 onclick="history.back()"><i class="fa-solid fa-arrow-left"></i><a href="#">Add Result</a></h2>

        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>

        <!-- Add Faculty Form -->
        <form action="" method="post" class="admin-form">
            <h3>Add Faculty</h3>
            <div class="form-group">
            <input type="text" name="faculty_name" placeholder="Faculty Name" required>
            <button type="submit" name="add_faculty" class="btn-get-started">Add Faculty</button>
            <div>
        </form>

        <!-- Add Semester Form -->
        <form action="" method="post" class="admin-form">
            <h3>Add Semester</h3>
            <select name="faculty_id" required>
                <option value="">Select Faculty</option>
                <?php foreach ($faculties as $faculty): ?>
                    <option value="<?php echo $faculty['id']; ?>"><?php echo htmlspecialchars($faculty['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="semester_name" placeholder="Semester Name" required>
            <button type="submit" name="add_semester" class="btn-get-started">Add Semester</button>
        </form>

        <!-- Add Result Form -->
        <form action="" method="post" enctype="multipart/form-data" style="padding-top: 50px;">
            <div class="form-group">
                <label for="faculty_id">Faculty:</label>
                <select id="faculty_id" name="faculty_id" required>
                    <option value="">Select Faculty</option>
                    <?php foreach ($faculties as $faculty): ?>
                        <option value="<?php echo $faculty['id']; ?>"><?php echo htmlspecialchars($faculty['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="semester_id">Semester:</label>
                <select id="semester_id" name="semester_id" required>
                    <option value="">Select Semester</option>
                    <?php foreach ($semesters as $semester): ?>
                        <option value="<?php echo $semester['id']; ?>"><?php echo htmlspecialchars($semester['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="batch_year">Batch Year:</label>
                <input type="number" id="batch_year" name="batch_year" required>
            </div>
            <div class="form-group">
                <label for="result_date">Result Date:</label>
                <input type="date" id="result_date" name="result_date" required>
            </div>
            <div class="form-group">
                <label>Input Method:</label>
                <label><input type="radio" name="input_method" value="file" checked> Upload File</label>
                <label><input type="radio" name="input_method" value="manual"> Enter Manually</label>
            </div>
            <div class="form-group file-input" id="fileInput">
                <input type="file" name="result_file" accept=".txt">
            </div>
            <div class="form-group manual-input" id="manualInput" style="display: none;">
                <label for="symbol_numbers">Symbol Numbers (separated by spaces):</label>
                <textarea id="symbol_numbers" name="symbol_numbers" rows="5"></textarea>
            </div>
            <button type="submit" name="add_result" class="dash-btn">Add Result</button>
        </form>
    </div>
    <script>
        const fileInputRadio = document.querySelector('input[name="input_method"][value="file"]');
        const manualInputRadio = document.querySelector('input[name="input_method"][value="manual"]');
        const fileInputDiv = document.getElementById('fileInput');
        const manualInputDiv = document.getElementById('manualInput');

        fileInputRadio.addEventListener('change', function() {
            if (this.checked) {
                fileInputDiv.style.display = 'block';
                manualInputDiv.style.display = 'none';
            }
        });

        manualInputRadio.addEventListener('change', function() {
            if (this.checked) {
                fileInputDiv.style.display = 'none';
                manualInputDiv.style.display = 'block';
            }
        });
    </script>
</body>
</html>