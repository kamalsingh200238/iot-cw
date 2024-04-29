<?php

session_start(); // Start the session

// Database connection parameters
$servername = "127.0.0.1";
$dbUsername = "root";
$dbPassword = "";
$database = "pawsome";

// Create connection
$conn = new mysqli($servername, $dbUsername, $dbPassword, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Prepare a select statement
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if username exists, if yes then verify password
    if ($stmt->num_rows == 1) {
        $hashedPassword = "";
        $stmt->bind_result($id, $username, $hashedPassword);
        if ($stmt->fetch()) {
            if (password_verify($password, $hashedPassword)) {
                // Password is correct, so start a new session
                session_regenerate_id();
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $id;
                $_SESSION["username"] = $username;

                // Redirect user to welcome page
                echo "location: welcome.php";
                exit;
            } else {
                // Display an error message if password is not valid
                echo "The password you entered was not valid.";
            }
        }
    } else {
        // Display an error message if username doesn't exist
        echo "No account found with that username.";
    }

    $stmt->close();
}

$conn->close();
