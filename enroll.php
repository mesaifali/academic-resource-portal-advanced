<?php
session_start();
require_once 'includes/functions.php';

// Check if user is logged in
checkUserSession();

if (!isset($_GET['course_id']) || !is_numeric($_GET['course_id'])) {
    header("Location: courses.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$course_id = (int)$_GET['course_id'];

if (is_user_enrolled($user_id, $course_id)) {
    $_SESSION['message'] = "You are already enrolled in this course.";
    header("Location: view_course.php?id=$course_id");
    exit();
}

if (enroll_user($user_id, $course_id)) {
    $_SESSION['message'] = "You have successfully enrolled in the course!";
} else {
    $_SESSION['message'] = "Error enrolling in the course. Please try again.";
}

header("Location: view_course.php?id=$course_id");
exit();