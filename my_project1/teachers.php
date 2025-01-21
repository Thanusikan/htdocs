<?php
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school_management";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["message" => "Connection failed: " . $conn->connect_error]));
}

// Handle different HTTP request methods
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST': // Add a new teacher
        $data = json_decode(file_get_contents('php://input'), true);
        $name = $data['name'];
        $age = $data['age'];
        $telephone = $data['telephone'];
        $present_days = $data['present_days'];
        $absent_days = $data['absent_days'];

        $stmt = $conn->prepare("INSERT INTO teachers (name, age, telephone, present_days, absent_days) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sissi", $name, $age, $telephone, $present_days, $absent_days);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Teacher added successfully"]);
        } else {
            echo json_encode(["message" => "Failed to add teacher"]);
        }
        $stmt->close();
        break;

    case 'GET': // Fetch all teachers
        $result = $conn->query("SELECT * FROM teachers");
        $teachers = [];
        while ($row = $result->fetch_assoc()) {
            $teachers[] = $row;
        }
        echo json_encode($teachers);
        break;

    case 'PUT': // Update an existing teacher
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'];
        $name = $data['name'];
        $age = $data['age'];
        $telephone = $data['telephone'];
        $present_days = $data['present_days'];
        $absent_days = $data['absent_days'];

        $stmt = $conn->prepare("UPDATE teachers SET name = ?, age = ?, telephone = ?, present_days = ?, absent_days = ? WHERE id = ?");
        $stmt->bind_param("sissii", $name, $age, $telephone, $present_days, $absent_days, $id);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Teacher updated successfully"]);
        } else {
            echo json_encode(["message" => "Failed to update teacher"]);
        }
        $stmt->close();
        break;

    case 'DELETE': // Delete a teacher
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'];

        $stmt = $conn->prepare("DELETE FROM teachers WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Teacher deleted successfully"]);
        } else {
            echo json_encode(["message" => "Failed to delete teacher"]);
        }
        $stmt->close();
        break;

    default:
        echo json_encode(["message" => "Invalid request"]);
        break;
}

$conn->close();
?>
