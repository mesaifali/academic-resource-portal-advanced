<?php
// Include database connection
require_once 'db.php';

// Existing functions
function sanitizeInput($input) {
    global $conn;
    if (!$conn) {
        die("Database connection failed.");
    }
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($input)));
}

function checkUserSession() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id'])) {
        header("Location: signin.php");
        exit();
    }
}

function checkAdminSession() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['admin_id'])) {
        header("Location: admin-login.php");
        exit();
    }
}

function getCurrentURL() {
    $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    return $url;
}

// Course-related functions
// function create_course($title, $description, $thumbnail_url) {
//     global $conn;
//     $title = sanitizeInput($title);
//     $description = sanitizeInput($description);
//     $thumbnail_url = sanitizeInput($thumbnail_url);
    
//     $query = "INSERT INTO courses (title, description, thumbnail_url) VALUES ('$title', '$description', '$thumbnail_url')";
//     if (mysqli_query($conn, $query)) {
//         return mysqli_insert_id($conn);
//     }
//     return false;
// }

function add_course_chapter($course_id, $title, $video_link, $order_num) {
    global $conn;
    $course_id = (int)$course_id;
    $title = sanitizeInput($title);
    $video_link = sanitizeInput($video_link);
    $order_num = (int)$order_num;
    
    $query = "INSERT INTO course_chapters (course_id, title, video_link, order_num) VALUES ($course_id, '$title', '$video_link', $order_num)";
    return mysqli_query($conn, $query);
}

function get_all_courses() {
    global $conn;
    $query = "SELECT * FROM courses ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function get_course($course_id) {
    global $conn;
    $course_id = (int)$course_id;
    $query = "SELECT * FROM courses WHERE id = $course_id";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

function get_course_chapters($course_id) {
    global $conn;
    $course_id = (int)$course_id;
    $query = "SELECT * FROM course_chapters WHERE course_id = $course_id ORDER BY order_num";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function enroll_user($user_id, $course_id) {
    global $conn;
    $user_id = (int)$user_id;
    $course_id = (int)$course_id;
    $query = "INSERT INTO course_enrollments (user_id, course_id) VALUES ($user_id, $course_id)";
    return mysqli_query($conn, $query);
}

function is_user_enrolled($user_id, $course_id) {
    global $conn;
    $user_id = (int)$user_id;
    $course_id = (int)$course_id;
    $query = "SELECT COUNT(*) FROM course_enrollments WHERE user_id = $user_id AND course_id = $course_id";
    $result = mysqli_query($conn, $query);
    $count = mysqli_fetch_row($result)[0];
    return $count > 0;
}

function mark_chapter_complete($user_id, $chapter_id) {
    global $conn;
    $user_id = (int)$user_id;
    $chapter_id = (int)$chapter_id;
    $query = "INSERT INTO chapter_progress (user_id, chapter_id, completed) VALUES ($user_id, $chapter_id, 1) 
              ON DUPLICATE KEY UPDATE completed = 1";
    return mysqli_query($conn, $query);
}

function is_chapter_completed($user_id, $chapter_id) {
    global $conn;
    $user_id = (int)$user_id;
    $chapter_id = (int)$chapter_id;
    $query = "SELECT completed FROM chapter_progress WHERE user_id = $user_id AND chapter_id = $chapter_id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return isset($row['completed']) && $row['completed'] == 1;
}

function get_chapter($chapter_id) {
    global $conn;
    $chapter_id = (int)$chapter_id;
    $query = "SELECT * FROM course_chapters WHERE id = $chapter_id";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    return mysqli_fetch_assoc($result);
}

function get_user_enrolled_courses($user_id) {
    global $conn;
    $user_id = (int)$user_id;
    $query = "SELECT c.* FROM courses c
              JOIN course_enrollments ce ON c.id = ce.course_id
              WHERE ce.user_id = $user_id";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function get_course_progress($user_id, $course_id) {
    global $conn;
    $user_id = (int)$user_id;
    $course_id = (int)$course_id;
    
    $query = "SELECT COUNT(*) as total FROM course_chapters WHERE course_id = $course_id";
    $result = mysqli_query($conn, $query);
    $total_chapters = mysqli_fetch_assoc($result)['total'];

    $query = "SELECT COUNT(*) as completed FROM chapter_progress cp
              JOIN course_chapters cc ON cp.chapter_id = cc.id
              WHERE cp.user_id = $user_id AND cc.course_id = $course_id AND cp.completed = 1";
    $result = mysqli_query($conn, $query);
    $completed_chapters = mysqli_fetch_assoc($result)['completed'];

    return [
        'total_chapters' => $total_chapters,
        'completed_chapters' => $completed_chapters
    ];
}

function check_course_completion($user_id, $course_id) {
    global $conn;
    $user_id = (int)$user_id;
    $course_id = (int)$course_id;

    $query = "SELECT COUNT(*) as total FROM course_chapters WHERE course_id = $course_id";
    $result = mysqli_query($conn, $query);
    $total_chapters = mysqli_fetch_assoc($result)['total'];

    $query = "SELECT COUNT(*) as completed FROM chapter_progress cp
              JOIN course_chapters cc ON cp.chapter_id = cc.id
              WHERE cp.user_id = $user_id AND cc.course_id = $course_id AND cp.completed = 1";
    $result = mysqli_query($conn, $query);
    $completed_chapters = mysqli_fetch_assoc($result)['completed'];

    if ($completed_chapters == $total_chapters) {
        $query = "INSERT INTO course_completions (user_id, course_id, completion_date)
                  VALUES ($user_id, $course_id, NOW())
                  ON DUPLICATE KEY UPDATE completion_date = NOW()";
        mysqli_query($conn, $query);
        return true;
    }

    return false;
}

// New admin functions
function get_all_users() {
    global $conn;
    $query = "SELECT id, username, email FROM users";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


// function update_course($course_id, $title, $description, $thumbnail_url, $category_id) {
//     global $conn;
//     $course_id = (int)$course_id;
//     $title = sanitizeInput($title);
//     $description = sanitizeInput($description);
//     $thumbnail_url = sanitizeInput($thumbnail_url);
//     $category_id = (int)$category_id;
    
//     $query = "UPDATE courses SET title = ?, description = ?, thumbnail_url = ?, category_id = ? WHERE id = ?";
//     $stmt = mysqli_prepare($conn, $query);
//     mysqli_stmt_bind_param($stmt, "sssii", $title, $description, $thumbnail_url, $category_id, $course_id);
//     return mysqli_stmt_execute($stmt);
// }

function update_course($course_id, $title, $description, $intro_video_url, $assets_url, $thumbnail_url, $category_id) {
    global $conn;
    $course_id = (int)$course_id;
    $category_id = (int)$category_id;
    
    $query = "UPDATE courses SET title = ?, description = ?, intro_video_url = ?, assets_url = ?, thumbnail_url = ?, category_id = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssssii", $title, $description, $intro_video_url, $assets_url, $thumbnail_url, $category_id, $course_id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

// function update_course($course_id, $title, $description, $thumbnail_url) {
//     global $conn;
//     $course_id = (int)$course_id;
//     $title = sanitizeInput($title);
//     $description = sanitizeInput($description);
//     $thumbnail_url = sanitizeInput($thumbnail_url);
    
//     $query = "UPDATE courses SET title = '$title', description = '$description', thumbnail_url = '$thumbnail_url' WHERE id = $course_id";
//     return mysqli_query($conn, $query);
// }

// function delete_course($course_id) {
//     global $conn;
//     $course_id = (int)$course_id;
    
//     // Delete related records first
//     mysqli_query($conn, "DELETE FROM course_enrollments WHERE course_id = $course_id");
//     mysqli_query($conn, "DELETE FROM chapter_progress WHERE chapter_id IN (SELECT id FROM course_chapters WHERE course_id = $course_id)");
//     mysqli_query($conn, "DELETE FROM course_chapters WHERE course_id = $course_id");
//     mysqli_query($conn, "DELETE FROM course_completions WHERE course_id = $course_id");
    
//     // Then delete the course
//     $query = "DELETE FROM courses WHERE id = $course_id";
//     return mysqli_query($conn, $query);
// }

function get_course_enrollments($course_id) {
    global $conn;
    $course_id = (int)$course_id;
    $query = "SELECT u.id, u.username, u.email, ce.enrolled_at 
              FROM users u 
              JOIN course_enrollments ce ON u.id = ce.user_id 
              WHERE ce.course_id = $course_id";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function update_user($user_id, $username, $email) {
    global $conn;
    $user_id = (int)$user_id;
    $username = sanitizeInput($username);
    $email = sanitizeInput($email);
    
    $query = "UPDATE users SET username = '$username', email = '$email' WHERE id = $user_id";
    return mysqli_query($conn, $query);
}

function delete_user($user_id) {
    global $conn;
    $user_id = (int)$user_id;
    
    // Delete related records first
    mysqli_query($conn, "DELETE FROM course_enrollments WHERE user_id = $user_id");
    mysqli_query($conn, "DELETE FROM chapter_progress WHERE user_id = $user_id");
    mysqli_query($conn, "DELETE FROM course_completions WHERE user_id = $user_id");
    
    // Then delete the user
    $query = "DELETE FROM users WHERE id = $user_id";
    return mysqli_query($conn, $query);
}
// Add these functions to your existing functions.php file

function get_course_enrollment_count($course_id) {
    global $conn;
    $course_id = (int)$course_id;
    $query = "SELECT COUNT(*) as count FROM course_enrollments WHERE course_id = $course_id";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result)['count'];
}


function unmark_chapter_complete($user_id, $chapter_id) {
    global $conn;
    $user_id = (int)$user_id;
    $chapter_id = (int)$chapter_id;
    $query = "DELETE FROM chapter_progress WHERE user_id = $user_id AND chapter_id = $chapter_id";
    return mysqli_query($conn, $query);
}

//delete chapter
// function delete_chapter($chapter_id) {
//     global $conn;
//     $chapter_id = (int)$chapter_id;
    
//     // Delete related records first
//     mysqli_query($conn, "DELETE FROM chapter_progress WHERE chapter_id = $chapter_id");
    
//     // Then delete the chapter
//     $query = "DELETE FROM course_chapters WHERE id = $chapter_id";
//     return mysqli_query($conn, $query);
// }



function update_chapter($chapter_id, $title, $video_link) {
    global $conn;
    $chapter_id = (int)$chapter_id;
    $title = sanitizeInput($title);
    $video_link = sanitizeInput($video_link);
    
    $query = "UPDATE course_chapters SET title = '$title', video_link = '$video_link' WHERE id = $chapter_id";
    return mysqli_query($conn, $query);
}

function update_chapter_order($course_id, $chapter_orders) {
    global $conn;
    $course_id = (int)$course_id;
    
    $success = true;
    
    foreach ($chapter_orders as $chapter_id => $order) {
        $chapter_id = (int)$chapter_id;
        $order = (int)$order;
        
        $query = "UPDATE course_chapters SET order_num = $order WHERE id = $chapter_id AND course_id = $course_id";
        if (!mysqli_query($conn, $query)) {
            $success = false;
        }
    }
    
    return $success;
}

//delete course
function delete_course($course_id) {
    global $conn;
    $course_id = (int)$course_id;
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // Delete related records first
        mysqli_query($conn, "DELETE FROM course_enrollments WHERE course_id = $course_id");
        mysqli_query($conn, "DELETE FROM chapter_progress WHERE chapter_id IN (SELECT id FROM course_chapters WHERE course_id = $course_id)");
        mysqli_query($conn, "DELETE FROM course_chapters WHERE course_id = $course_id");
        mysqli_query($conn, "DELETE FROM course_completions WHERE course_id = $course_id");
        
        // Then delete the course
        $query = "DELETE FROM courses WHERE id = $course_id";
        $result = mysqli_query($conn, $query);
        
        if ($result) {
            mysqli_commit($conn);
            return true;
        } else {
            throw new Exception("Failed to delete course");
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        error_log($e->getMessage());
        return false;
    }
}

//delete chapter
function delete_chapter($chapter_id) {
    global $conn;
    $chapter_id = (int)$chapter_id;
    
    mysqli_begin_transaction($conn);
    
    try {
        // Delete related records first
        $progress_query = "DELETE FROM chapter_progress WHERE chapter_id = $chapter_id";
        if (!mysqli_query($conn, $progress_query)) {
            throw new Exception("Failed to delete chapter progress");
        }
        
        // Then delete the chapter
        $chapter_query = "DELETE FROM course_chapters WHERE id = $chapter_id";
        if (!mysqli_query($conn, $chapter_query)) {
            throw new Exception("Failed to delete chapter");
        }
        
        mysqli_commit($conn);
        return true;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        error_log("Error deleting chapter: " . $e->getMessage());
        return false;
    }
}


//course categories
function get_course_categories() {
    global $conn;
    $query = "SELECT * FROM course_categories ORDER BY name";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// function create_course($title, $description, $thumbnail_url, $category_id) {
//     global $conn;
//     $title = sanitizeInput($title);
//     $description = sanitizeInput($description);
//     $thumbnail_url = sanitizeInput($thumbnail_url);
//     $category_id = (int)$category_id;
    
//     $query = "INSERT INTO courses (title, description, thumbnail_url, category_id) VALUES (?, ?, ?, ?)";
//     $stmt = mysqli_prepare($conn, $query);
//     mysqli_stmt_bind_param($stmt, "sssi", $title, $description, $thumbnail_url, $category_id);
    
//     if (mysqli_stmt_execute($stmt)) {
//         return mysqli_insert_id($conn);
//     }
//     return false;
// }
function create_course($title, $description, $intro_video_url, $assets_url, $thumbnail_url, $category_id) {
    global $conn;
    $category_id = (int)$category_id;
    
    $query = "INSERT INTO courses (title, description, intro_video_url, assets_url, thumbnail_url, category_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    
    if ($stmt === false) {
        return false;
    }
    
    mysqli_stmt_bind_param($stmt, "sssssi", $title, $description, $intro_video_url, $assets_url, $thumbnail_url, $category_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $course_id = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);
        return $course_id;
    } else {
        mysqli_stmt_close($stmt);
        return false;
    }
}



function add_course_category($name) {
    global $conn;
    $name = sanitizeInput($name);
    $query = "INSERT INTO course_categories (name) VALUES (?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $name);
    return mysqli_stmt_execute($stmt);
}

function delete_course_category($id) {
    global $conn;
    $id = (int)$id;
    $query = "DELETE FROM course_categories WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    return mysqli_stmt_execute($stmt);
}

// course dashboard
function get_total_courses() {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) as total FROM courses");
    return $result->fetch_assoc()['total'];
}

function get_total_enrollments() {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) as total FROM course_enrollments");
    return $result->fetch_assoc()['total'];
}

function get_total_categories() {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) as total FROM course_categories");
    return $result->fetch_assoc()['total'];
}

function get_recent_courses($limit = 5) {
    global $conn;
    $result = $conn->query("SELECT title FROM courses ORDER BY created_at DESC LIMIT $limit");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function get_courses_by_category() {
    global $conn;
    $result = $conn->query("SELECT cc.name, COUNT(c.id) as count 
                            FROM course_categories cc
                            LEFT JOIN courses c ON cc.id = c.category_id
                            GROUP BY cc.id");
    return $result->fetch_all(MYSQLI_ASSOC);
}
function get_total_completions() {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) as total FROM course_completions");
    return $result->fetch_assoc()['total'];
}

// view course info
function get_category_name($category_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT name FROM course_categories WHERE id = ?");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row['name'];
    }
    return "Uncategorized";
}

function get_related_courses($course_id, $category_id, $limit = 3) {
    global $conn;
    $stmt = $conn->prepare("SELECT id, title, thumbnail_url, created_at FROM courses 
                            WHERE id != ? AND category_id = ? 
                            ORDER BY RAND() LIMIT ?");
    $stmt->bind_param("iii", $course_id, $category_id, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}


//high level decsription
function parse_course_description($description) {
    // Unescape the description and decode HTML entities
    $description = html_entity_decode(stripslashes($description), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    
    // Convert \r\n to actual newlines
    $description = str_replace('\r\n', "\n", $description);
    
    $lines = explode("\n", $description);
    $html = '';
    $in_list = false;
    $list_type = '';

    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) {
            if ($in_list) {
                $html .= $list_type == 'ul' ? '</ul>' : '</ol>';
                $in_list = false;
            }
            $html .= '<br>';
        } elseif (strpos($line, '##') === 0) {
            if ($in_list) {
                $html .= $list_type == 'ul' ? '</ul>' : '</ol>';
                $in_list = false;
            }
            $html .= '<h3>' . htmlspecialchars(substr($line, 2)) . '</h3>';
        } elseif (strpos($line, '* ') === 0) {
            if (!$in_list || $list_type != 'ul') {
                if ($in_list) {
                    $html .= $list_type == 'ul' ? '</ul>' : '</ol>';
                }
                $html .= '<ul>';
                $in_list = true;
                $list_type = 'ul';
            }
            $html .= '<li>' . htmlspecialchars(substr($line, 2)) . '</li>';
        } elseif (preg_match('/^\d+\.\s/', $line)) {
            if (!$in_list || $list_type != 'ol') {
                if ($in_list) {
                    $html .= $list_type == 'ul' ? '</ul>' : '</ol>';
                }
                $html .= '<ol>';
                $in_list = true;
                $list_type = 'ol';
            }
            $html .= '<li>' . htmlspecialchars(preg_replace('/^\d+\.\s/', '', $line)) . '</li>';
        } else {
            if ($in_list) {
                $html .= $list_type == 'ul' ? '</ul>' : '</ol>';
                $in_list = false;
            }
            $html .= '<p>' . htmlspecialchars($line) . '</p>';
        }
    }

    if ($in_list) {
        $html .= $list_type == 'ul' ? '</ul>' : '</ol>';
    }

    return $html;
}

//result section

// Faculty Functions
function add_faculty($name) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO faculties (name) VALUES (?)");
    $stmt->bind_param("s", $name);
    return $stmt->execute();
}

function get_all_faculties() {
    global $conn;
    $result = $conn->query("SELECT * FROM faculties ORDER BY name");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function delete_faculty($faculty_id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM faculties WHERE id = ?");
    $stmt->bind_param("i", $faculty_id);
    return $stmt->execute();
}

// Semester Functions
function add_semester($faculty_id, $name) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO semesters (faculty_id, name) VALUES (?, ?)");
    $stmt->bind_param("is", $faculty_id, $name);
    return $stmt->execute();
}

function get_all_semesters() {
    global $conn;
    $result = $conn->query("SELECT * FROM semesters ORDER BY faculty_id, name");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function delete_semester($semester_id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM semesters WHERE id = ?");
    $stmt->bind_param("i", $semester_id);
    return $stmt->execute();
}

// Result Functions
function add_result($faculty_id, $semester_id, $batch_year, $result_date, $input_method) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO results (faculty_id, semester_id, batch_year, result_date, input_method) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiss", $faculty_id, $semester_id, $batch_year, $result_date, $input_method);
    if ($stmt->execute()) {
        return $stmt->insert_id;
    }
    return false;
}


// function add_student_results_from_file($result_id, $file_path, &$passed_symbol_numbers) {
//     global $conn;
//     $count = 0;
//     $file = fopen($file_path, "r");
//     if ($file) {
//         while (($line = fgets($file)) !== false) {
//             $symbol_no = trim($line);
//             if (!empty($symbol_no)) {
//                 add_student_result($result_id, $symbol_no, 'pass');
//                 $passed_symbol_numbers[] = $symbol_no;
//                 $count++;
//             }
//         }
//         fclose($file);
//         error_log("Added $count symbol numbers from file: $file_path");
//         return true;
//     } else {
//         error_log("Error opening file: " . $file_path);
//         return false;
//     }
// }


function add_student_results_from_file($result_id, $file_path, &$passed_symbol_numbers) {
    global $conn;
    $count = 0;
    $file = fopen($file_path, "r");
    if ($file) {
        while (($line = fgets($file)) !== false) {
            $symbol_no = trim($line);
            error_log("Processing symbol number: " . $symbol_no); // Log each symbol
            if (!empty($symbol_no)) {
                if (add_student_result($result_id, $symbol_no, 'pass')) {
                    $passed_symbol_numbers[] = $symbol_no;
                    $count++;
                    error_log("Successfully added symbol: " . $symbol_no); // Log successful additions
                } else {
                    error_log("Error adding symbol: " . $symbol_no . " - " . mysqli_error($conn)); // Log errors
                }
            }
        }
        fclose($file);
        error_log("Processed $count symbol numbers from file: $file_path");
        return true;
    } else {
        error_log("Error opening file: " . $file_path);
        return false;
    }
}


function add_student_result($result_id, $symbol_no, $status) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO student_results (result_id, symbol_no, status) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $result_id, $symbol_no, $status);
    return $stmt->execute();
}

//delete resullt
function delete_result($result_id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM results WHERE id = ?");
    $stmt->bind_param("i", $result_id);
    if ($stmt->execute()) {
        // Delete associated student results
        $stmt = $conn->prepare("DELETE FROM student_results WHERE result_id = ?");
        $stmt->bind_param("i", $result_id);
        return $stmt->execute();
    }
    return false;
}



function get_all_results() {
    global $conn;
    $result = $conn->query("SELECT * FROM results ORDER BY result_date DESC");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function get_student_results($symbol_no, $faculty_id, $semester_id) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT sr.status 
        FROM student_results sr
        JOIN results r ON sr.result_id = r.id
        WHERE sr.symbol_no = ? AND r.faculty_id = ? AND r.semester_id = ?
    ");
    $stmt->bind_param("sii", $symbol_no, $faculty_id, $semester_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row ? $row['status'] : null;
}


// Function to get students by faculty, semester, and batch year
function get_students_by_faculty_semester_batch($faculty_id, $semester_id, $batch_year) {
    global $conn;
    $stmt = $conn->prepare("SELECT symbol_no FROM students WHERE faculty_id = ? AND semester_id = ? AND batch_year = ?");
    $stmt->bind_param("iii", $faculty_id, $semester_id, $batch_year);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Function to add failed students
function add_failed_students($result_id, $all_symbol_numbers, $passed_symbol_numbers) {
    global $conn;
    $failed_symbol_numbers = array_diff($all_symbol_numbers, $passed_symbol_numbers);
    foreach ($failed_symbol_numbers as $symbol_no) {
        add_student_result($result_id, $symbol_no, 'fail');
    }
}


function get_faculty_name($faculty_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT name FROM faculties WHERE id = ?");
    $stmt->bind_param("i", $faculty_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row ? $row['name'] : '';
}

function get_semester_name($semester_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT name FROM semesters WHERE id = ?");
    $stmt->bind_param("i", $semester_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row ? $row['name'] : '';
}
//for result dashboard
// function get_total_results() {
//     global $conn;
//     $result = $conn->query("SELECT COUNT(*) as total FROM results");
//     return $result->fetch_assoc()['total'];
// }

// function get_total_passed_students() {
//     global $conn;
//     $result = $conn->query("SELECT COUNT(*) as total FROM student_results WHERE status = 'pass'");
//     return $result->fetch_assoc()['total'];
// }

// function get_total_failed_students() {
//     global $conn;
//     $result = $conn->query("SELECT COUNT(*) as total FROM student_results WHERE status = 'fail'");
//     return $result->fetch_assoc()['total'];
// }

// function get_recent_results($limit = 5) {
//     global $conn;
//     $result = $conn->query("SELECT * FROM results ORDER BY result_date DESC LIMIT $limit");
//     return $result->fetch_all(MYSQLI_ASSOC);
// }




?>
