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
  // Validate username and password
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  if (empty($username) || empty($password)) {
    $errorMessage = "Username or password cannot be empty";
  } else {
    // Prepare a select statement to check if the username already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if username already exists
    if ($result->num_rows > 0) {
      $errorMessage = "Username already exists.";
    } else {
      // Hash the password
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      // Prepare an insert statement
      $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
      $stmt->bind_param("ss", $username, $hashed_password);

      // Attempt to execute the prepared statement
      if ($stmt->execute()) {
        header("Location: admin.php");
        exit;
      } else {
        $errorMessage = "Error: " . $stmt->error;
      }
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
  <title>Pawsome | Signup</title>
  <link rel="stylesheet" href="main.css" />
</head>

<body>
  <main class="flex justify-center items-center min-h-screen">
    <form action="admin_add_user.php" method="post" class="w-full max-w-md bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4">
      <h1 class="mb-8 text-3xl font-bold text-sky-950 text-center">Add user</h1>
      <?php if (!empty($errorMessage)) : ?>
        <div class="mb-5 px-5 py-3 rounded-md bg-rose-100 text-red-950 text-center"><?php echo $errorMessage; ?></div>
      <?php endif; ?>
      <div class="mb-4">
        <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username</label>
        <input required id="username" name="username" type="text" class="w-full border rounded-md py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
      </div>
      <div class="mb-6">
        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
        <input required id="password" name="password" type="password" class="w-full border rounded-md py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
      </div>
      <div class="mb-6">
        <button type="submit" class="w-full bg-sky-950 hover:bg-sky-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Create User</button>
      </div>
      <p class="text-center text-sm text-gray-600"><a href="admin.php" class="text-blue-500 hover:underline">Back to Admin
          Page</a></p>
    </form>
  </main>
</body>

</html>