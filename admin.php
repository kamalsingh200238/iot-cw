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
</head>

<body>
  <h2>Welcome, Admin!</h2>
  <p>This is the admin page.</p>
  <a href="admin_add_user.php">Add user</a>
  <a href="admin_add_product.php">Add product</a>
  <table>
    <thead>
      <tr>
        <th>Id</th>
        <th>Name</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $user) : ?>
        <tr>
          <td><?php echo $user['id']; ?></td>
          <td><?php echo $user['username']; ?></td>
          <td><a href="admin_delete_user.php?id=<?php echo$user['id']?>">Delete</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <table>
    <thead>
      <tr>
        <th>Product id</th>
        <th>Product name</th>
        <th>Product price</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $product) : ?>
        <tr>
          <td><?php echo $product['id']; ?></td>
          <td><?php echo $product['name']; ?></td>
          <td><?php echo $product['price']; ?></td>
          <td><a href="admin_delete_product.php?id=<?php echo$product['id']?>">Delete</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>

</html>