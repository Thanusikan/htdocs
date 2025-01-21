<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_teacher";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get session and type from the query parameters
$session = isset($_GET['session']) ? $_GET['session'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';

if ($session && $type === 'image') {
    // Fetch the image file path for the given session
    $stmt = $conn->prepare("SELECT image_file FROM uploads WHERE session_name = ?");
    $stmt->bind_param("s", $session);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($image_file);

    // Check if the image file exists for the session
    if ($stmt->fetch()) {
        // Set headers to trigger a file download
        $file_path = $image_file;

        if (file_exists($file_path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
            header('Content-Length: ' . filesize($file_path));
            readfile($file_path);
            exit;
        } else {
            echo "Error: File not found.";
        }
    } else {
        echo "Error: No image found for this session.";
    }

    $stmt->close();
}

// Close the connection
$conn->close();
?>
