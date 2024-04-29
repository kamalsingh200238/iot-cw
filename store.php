<?php

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

// Check if the query parameter is not empty
if (!empty($_GET['query'])) {
    $query = mysqli_real_escape_string($conn, $_GET['query']);
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE CONCAT('%', ?, '%') LIMIT 5");
    $stmt->bind_param("s", $query);
    
    // Execute prepared statement
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }

} else {
    // If no query is provided, fetch all products
    $stmt = $conn->prepare("SELECT * FROM products");

    // Execute prepared statement
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }
}

// Get result
$result = $stmt->get_result();

// Generate HTML for results
$resultsHtml = '';
while ($row = $result->fetch_assoc()) {
    $resultsHtml .= '<li><a href="product_details.php?id=' . $row['id'] . '">' . $row['name'] . '</a></li>';
}

// Set appropriate HTTP header
header('Content-Type: text/html');

// Output results
echo $resultsHtml;

// Close statement and connection
$stmt->close();
$conn->close();
?>
