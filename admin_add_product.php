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

$errorMessage = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate product name and price
  $productName = trim($_POST["productName"]);
  $productPrice = trim($_POST["productPrice"]);

  if (empty($productName) || empty($productPrice)) {
    $errorMessage = "Product name or price cannot be empty";
  } else {
    // Prepare an insert statement
    $stmt = $conn->prepare("INSERT INTO products (name, price) VALUES (?, ?)");
    $stmt->bind_param("sd", $productName, $productPrice);

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
      // Redirect to the admin page
      header("Location: admin.php");
      exit;
    } else {
      $errorMessage = "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
  }
}

// Close connection
$conn->close();
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Pawsome | Add Product</title>
  <link rel="stylesheet" href="main.css" />
</head>

<body>
  <main class="flex justify-center items-center min-h-screen">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="w-full max-w-md bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4">
      <h1 class="mb-8 text-3xl font-bold text-sky-950 text-center">Add Product</h1>
      <?php if (!empty($errorMessage)) : ?>
        <div class="mb-5 px-5 py-3 rounded-md bg-rose-100 text-red-950 text-center"><?php echo $errorMessage; ?></div>
      <?php endif; ?>
      <div class="mb-4">
        <label for="productName" class="block text-gray-700 text-sm font-bold mb-2">Product Name</label>
        <input required id="productName" name="productName" type="text" class="w-full border rounded-md py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
      </div>
      <div class="mb-6">
        <label for="productPrice" class="block text-gray-700 text-sm font-bold mb-2">Product Price</label>
        <input required id="productPrice" name="productPrice" type="number" step="0.01" class="w-full border rounded-md py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
      </div>
      <div class="mb-6">
        <button type="submit" class="w-full bg-sky-950 hover:bg-sky-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
          Add Product
        </button>
      </div>
      <p class="text-center text-sm text-gray-600"><a href="admin.php" class="text-blue-500 hover:underline">Back to Admin
          Page</a></p>
    </form>
  </main>
</body>

</html>