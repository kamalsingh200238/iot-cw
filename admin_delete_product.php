<?php
// Start the session
session_start();

// Check if the user is not logged in as admin, redirect to admin login page
if (!isset($_SESSION["admin_loggedin"]) || $_SESSION["admin_loggedin"] !== true) {
    header("location: admin_login.php");
    exit;
}

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

// Check if product ID is provided in the query parameter
if (isset($_GET['id'])) {
    // Escape product ID to prevent SQL injection
    $productId = $conn->real_escape_string($_GET['id']);

    // Prepare a delete statement
    $sql = "DELETE FROM products WHERE id = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("i", $productId);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect back to admin.php after deletion
        header("location: admin.php");
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
