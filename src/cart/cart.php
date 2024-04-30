<?php

session_start(); // Start the session

// Check if the user is not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // User is not logged in, redirect to not_logged_in.php
    header("location: not_logged_in.php");
    exit;
}

$jsonData = $_GET['data'];
echo "data: " .$jsonData;

// Decode the JSON data
$data_array = json_decode($jsonData, true);

// Check if decoding was successful
if ($data_array === null) {
    echo "Error decoding JSON data.";
    exit;
}

// Extract all IDs from the decoded array
$ids = array_column($data_array, 'id');

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

// Prepare a SQL statement to fetch data for the provided IDs
$stmt = $conn->prepare("SELECT * FROM products WHERE id IN (?" . str_repeat(",?", count($ids) - 1) . ")");

// Bind parameters
$stmt->bind_param(str_repeat("i", count($ids)), ...$ids);

// Execute the prepared statement
$stmt->execute();

// Get the result set
$result = $stmt->get_result();

// Fetch the rows
$rows = $result->fetch_all(MYSQLI_ASSOC);


// Close the statement and connection
$stmt->close();
$conn->close();

// Display the fetched data
foreach ($rows as $row) {
    echo "Product ID: " . $row['id'] . ", Name: " . $row['name'] . "<br>";
}

?>

