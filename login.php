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

// Initialize error message
$errorMessage = "";

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
                header("location: index.html");
                exit;
            } else {
                // Set error message if password is not valid
                $errorMessage = "The password you entered was not valid.";
            }
        }
    } else {
        // Set error message if username doesn't exist
        $errorMessage = "No account found with that username.";
    }

    $stmt->close();
}

$conn->close();
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Pawsome | Login</title>
  <link rel="stylesheet" href="main.css" />
</head>

<body>
  <main>
    <div class="grid grid-cols-2 place-items-center">
      <img src="images/dog-sitting.jpg" alt="dog sitting" class="w-full max-h-screen object-cover object-bottom" />
        <form action="login.php" method="post">
          <div class="w-full px-10 flex flex-col items-center">
            <h1 class="mb-8 text-3xl font-bold text-sky-950">Login</h1>
            <?php if (!empty($errorMessage)): ?>
              <div class="mb-5 px-5 py-3 rounded-md bg-rose-100 text-red-950"><?php echo $errorMessage; ?></div>
            <?php endif; ?>
            <div class="flex flex-col gap-1 mb-10">
              <label for="username">Username</label>
              <input id="username" name="username" type="text" class="w-96 rounded-md border-gray-300" />
            </div>
            <div class="flex flex-col gap-1 mb-10">
              <label for="password">Password</label>
              <input id="password" type="password" name="password" class="w-96 rounded-md border-gray-300" />
            </div>
            <div class="mb-5">
              <button class="bg-sky-950 w-96 py-3 text-white font-bold rounded-lg hover:bg-sky-800 transition-all">
                Login
              </button>
            </div>
            <div>
              <a href="signup.php" class="text-blue-500 hover:underline transition-all">New user? Sign up</a>
            </div>
          </div>
        </form>
    </div>
  </main>
</body>

</html>
