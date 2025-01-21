<?php
$servername = "localhost";  // Your database server (e.g., localhost)
$username = "root";         // Your MySQL username
$password = "";             // Your MySQL password
$dbname = "student_teacher";  // The database name you created

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Directory to save the uploaded files
    $upload_dir = "uploads/";

    // Ensure the uploads directory exists
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Process the session name
    $session = htmlspecialchars($_POST['session']);

    // Initialize variables for the uploaded files
    $notes_file = '';
    $image_file = '';

    // Handle the notes upload
    if (isset($_FILES['notes']) && $_FILES['notes']['error'] == UPLOAD_ERR_OK) {
        $notes_tmp_name = $_FILES['notes']['tmp_name'];
        $notes_name = basename($_FILES['notes']['name']);
        $notes_target = $upload_dir . $session . "_notes_" . $notes_name;

        if (move_uploaded_file($notes_tmp_name, $notes_target)) {
            $notes_file = $notes_target;
        } else {
            echo "Error uploading notes.<br>";
        }
    }

    // Handle the image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_name = basename($_FILES['image']['name']);
        $image_target = $upload_dir . $session . "_image_" . $image_name;

        if (move_uploaded_file($image_tmp_name, $image_target)) {
            $image_file = $image_target;
        } else {
            echo "Error uploading image.<br>";
        }
    }

    // Insert data into the database
    if ($notes_file || $image_file) {
        $stmt = $conn->prepare("INSERT INTO uploads (session_name, notes_file, image_file) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $session, $notes_file, $image_file);

        if ($stmt->execute()) {
            echo "Files uploaded and data stored successfully!";
        } else {
            echo "Error storing data in the database: " . $conn->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>
