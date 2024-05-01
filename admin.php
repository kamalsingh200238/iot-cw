<?php
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

// Query to fetch users
$userSql = "SELECT * FROM users";
$userResult = $conn->query($userSql);
$users = array();
if ($userResult->num_rows > 0) {
  while ($row = $userResult->fetch_assoc()) {
    $users[] = $row;
  }
}

// Query to fetch products
$productSql = "SELECT * FROM products";
$productResult = $conn->query($productSql);
$products = array();
if ($productResult->num_rows > 0) {
  while ($row = $productResult->fetch_assoc()) {
    $products[] = $row;
  }
}

// Close the connection
$conn->close();
// Your admin page content goes here
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Page</title>
  <link href="main.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
  <div class="container mx-auto px-4 py-8">
    <h2 class="text-3xl font-semibold text-center mb-4">Welcome, Admin!</h2>
    <p class="text-center mb-8">This is the admin page.</p>
    <div class="flex justify-center mb-8">
      <a href="admin_add_user.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mr-4 rounded">Add user</a>
      <a href="admin_add_product.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add product</a>
    </div>
    <div class="flex justify-center">
      <table class="table-auto border border-collapse border-gray-600">
        <thead>
          <tr>
            <th class="px-4 py-2 bg-gray-200 border">Id</th>
            <th class="px-4 py-2 bg-gray-200 border">Name</th>
            <th class="px-4 py-2 bg-gray-200 border">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user) : ?>
            <tr>
              <td class="px-4 py-2 border"><?php echo $user['id']; ?></td>
              <td class="px-4 py-2 border"><?php echo $user['username']; ?></td>
              <td class="px-4 py-2 border"><a href="admin_delete_user.php?id=<?php echo $user['id'] ?>" class="text-red-500 hover:text-red-700">Delete</a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="flex justify-center mt-8">
      <table class="table-auto border border-collapse border-gray-600">
        <thead>
          <tr>
            <th class="px-4 py-2 bg-gray-200 border">Product id</th>
            <th class="px-4 py-2 bg-gray-200 border">Product name</th>
            <th class="px-4 py-2 bg-gray-200 border">Product price</th>
            <th class="px-4 py-2 bg-gray-200 border">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $product) : ?>
            <tr>
              <td class="px-4 py-2 border"><?php echo $product['id']; ?></td>
              <td class="px-4 py-2 border"><?php echo $product['name']; ?></td>
              <td class="px-4 py-2 border"><?php echo $product['price']; ?></td>
              <td class="px-4 py-2 border"><a href="admin_delete_product.php?id=<?php echo $product['id'] ?>" class="text-red-500 hover:text-red-700">Delete</a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>
