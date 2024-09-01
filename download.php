<?php
session_start();
include 'includes/db.php';
include 'includes/functions.php';

if (isset($_GET['file']) && isset($_GET['type'])) {
    $file = basename($_GET['file']);
    $type = strtolower($_GET['type']); // Convert type to lowercase
    
    // Construct the file path
    $file_path = 'uploads/' . $type . '/' . $file;
    $real_path = realpath($file_path); // Get the actual file system path

    // Check if the file exists
    if ($real_path && file_exists($real_path)) {
        // Update download count
        $sql = "UPDATE resources SET download_count = download_count + 1 WHERE file_path = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $file);
        $stmt->execute();
        $stmt->close();

        // Clear any output buffer to avoid corruption
        if (ob_get_length()) {
            ob_end_clean();
        }

        // Determine the correct MIME type for the file
        $mime_type = mime_content_type($real_path);

        // Deliver the file to the user
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $mime_type);
        header('Content-Disposition: attachment; filename="' . basename($real_path) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($real_path));
        readfile($real_path);
        exit;
    } else {
        // Redirect to an error page or show an error message
        echo 'File not found at: ' . htmlspecialchars($file_path);
    }
} else {
    // Redirect to an error page or show an error message
    echo 'No file or type specified.';
}
?>
