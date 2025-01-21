<?php
header("Content-Type: application/json");
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["message" => "Database connection failed: " . $conn->connect_error]);
    exit();
}

// Handle different request methods
$requestMethod = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents('php://input'), true);

if ($requestMethod === 'POST') {
    // Add a new student
    $stmt = $conn->prepare("INSERT INTO students (name, age, telephone, grade, performance, disabilities) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sissss", $data['name'], $data['age'], $data['telephone'], $data['grade'], $data['performance'], $data['disabilities']);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Student added successfully"]);
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Failed to add student"]);
    }
    $stmt->close();

} elseif ($requestMethod === 'GET') {
    // Fetch all students
    $result = $conn->query("SELECT * FROM students");
    $students = [];

    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
    echo json_encode($students);

} elseif ($requestMethod === 'PUT') {
    // Update an existing student
    $stmt = $conn->prepare("UPDATE students SET name = ?, age = ?, telephone = ?, grade = ?, performance = ?, disabilities = ? WHERE id = ?");
    $stmt->bind_param("sissssi", $data['name'], $data['age'], $data['telephone'], $data['grade'], $data['performance'], $data['disabilities'], $data['id']);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Student updated successfully"]);
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Failed to update student"]);
    }
    $stmt->close();

} elseif ($requestMethod === 'DELETE') {
    // Delete a student
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $data['id']);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Student deleted successfully"]);
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Failed to delete student"]);
    }
    $stmt->close();

} else {
    http_response_code(405);
    echo json_encode(["message" => "Method not allowed"]);
}

$conn->close();
?>
