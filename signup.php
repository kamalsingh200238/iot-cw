<?php

$servername = "127.0.0.1";
$dbUsername = "root";
$dbPassword = "";
$database = "pawsome";

$conn = new mysqli($servername, $dbUsername, $dbPassword, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (empty($username) || empty($password)) {
        $errorMessage = "Username or password cannot be empty";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errorMessage = "Username already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare(
                "INSERT INTO users (username, password) VALUES (?, ?)"
            );
            $stmt->bind_param("ss", $username, $hashed_password);

            if ($stmt->execute()) {
                header("Location: login.php"); // Redirect to a success page or display a success message
                exit;
            } else {
                $errorMessage = "Error: " . $stmt->error;
            }
        }

        $stmt->close();
    }
}

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
  <main class="">
    <div class="grid grid-cols-2 place-items-center">
      <img src="images/dog-sitting.jpg" alt="dog sitting" class="w-full max-h-screen object-cover object-bottom" />
      <form action="signup.php" method="post">
        <div class="w-full px-10 flex flex-col items-center">
          <h1 class="mb-8 text-3xl font-bold text-sky-950">Signup</h1>
          <?php if (!empty($errorMessage)): ?>
            <div class="mb-5 px-5 py-3 rounded-md bg-rose-100 text-red-950"><?php echo $errorMessage; ?></div>
          <?php endif; ?>
          <div class="flex flex-col gap-1 mb-10">
            <label for="username">Username</label>
            <input id="username" name="username" type="text" class="w-96 rounded-md border-gray-300" />
          </div>
          <div class="flex flex-col gap-1 mb-10">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" class="w-96 rounded-md border-gray-300" />
          </div>
          <div class="mb-5">
            <button type="submit"
              class="bg-sky-950 w-96 py-3 text-white font-bold rounded-lg hover:bg-sky-800 transition-all">
              Register
            </button>
          </div>
          <div>
            <a href="./login.html" class="text-blue-500 hover:underline transition-all">Existing user? Login</a>
          </div>
        </div>
      </form>
    </div>
 </main>
</body>

</html>
