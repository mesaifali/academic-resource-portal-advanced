<?php
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $symbol_no = sanitizeInput($_POST['symbol_no']);
    $faculty_id = (int)$_POST['faculty_id'];
    $semester_id = (int)$_POST['semester_id'];
    $result = get_student_results($symbol_no, $faculty_id, $semester_id);

    if ($result !== null) {
        // Existing code for displaying "Pass" result (no changes here)
        echo '<div class="result-status ' . $result . '">';
        if ($result == 'pass') {
            echo 'Congratulations! You Passed';
        } else {
            echo 'You are listed as Failed';
        }
        echo '</div>';
        echo '<p><strong>Symbol Number:</strong> ' . htmlspecialchars($symbol_no) . '</p>';
        echo '<p class="note">Please cross-check your result with the official university PDF.</p>';
    } else {
        // Style the "No results found" message as "Fail"
        echo '<div class="result-status fail">Symbol Number Not Found (Failed)</div>';
        echo '<p><strong>Symbol Number:</strong> ' . htmlspecialchars($symbol_no) . '</p>';
        echo '<p class="note">Please cross-check your result with the official university PDF.</p>'; 
    }
}
?>