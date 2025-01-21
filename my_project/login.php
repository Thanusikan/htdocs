<?php
// Check if form fields are set
if (isset($_POST['stemail']) && isset($_POST['stpasswoad'])) {
    // Get the values from the POST request
    $email = $_POST['stemail'];
    $password = $_POST['stpasswoad'];

    // Establish the database connection
    $con = mysqli_connect("localhost", "root", "", "school");

    // Check if the connection was successful
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare the SQL query with correct variables
    $sql = "INSERT INTO login(email, passwoad) VALUES ('$email', '$password')";

    // Execute the query
    $r = mysqli_query($con, $sql);

    // Check if the query was successful
    if ($r) {
        echo "Welcome, Login successful.";
    } else {
        echo "Error: " . mysqli_error($con); // Provide error message if query fails
    }

    // Close the database connection
    mysqli_close($con);
} else {
    echo "Please fill in both email and password.";
}
?>
