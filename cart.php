<?php

session_start(); // Start the session

// Check if the user is not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // User is not logged in, redirect to not_logged_in.php
    header("location: not_logged_in.php");
    exit;
}

$jsonData = $_GET['data'];

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

$subtotal = 0;
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

<div class="bg-white">
  <div class="mx-auto pt-16 pb-24 max-w-7xl px-8">
    <h1 class="text-3xl font-bold text-gray-900">Shopping Cart</h1>
    <div class="mt-12 grid grid-cols-12 gap-x-12 items-start">
      <section aria-labelledby="cart-heading" class="col-span-7">
        <ul role="list" class="border-t border-b border-gray-200 divide-y divide-gray-200">
                        <?php foreach ($rows as $row) :
                            $quantity = 0;
                            foreach ($data_array as $data) {
                                if ($data['id'] == $row['id']) {
                                    $quantity = $data['quantity'];
                                    break;
                                }
                            }
                            $itemTotal = $row['price'] * $quantity;
                            $subtotal += $itemTotal;
                            ?>
          <li class="flex py-6 sm:py-10">
            <div class="flex-shrink-0">
              <img src="<?php echo "images/" . $row[ "id" ] . ".jpg"; ?>" alt="Front of men&#039;s Basic Tee in sienna." class="w-24 h-24 rounded-md object-center object-cover sm:w-48 sm:h-48">
            </div>

            <div class="ml-4 flex-1 flex flex-col justify-between">
              <div class="relative pr-9">
                <div>
                  <div class="flex justify-between">
                    <h3 class="text-sm">
                      <span class="font-medium text-gray-700 hover:text-gray-800"><?php echo $row['name']; ?></span>
                    </h3>
                  </div>
                  <p class="mt-1 text-sm font-medium text-gray-900">&pound; <?php echo $row['price']; ?></p>
                  <p class="mt-1 text-sm font-medium text-gray-900"><?php echo "Quantity: " . $quantity; ?></p>
                </div>
              </div>

              <p class="mt-4 flex text-sm text-gray-700 space-x-2">
                <!-- Heroicon name: solid/check -->
                <svg class="flex-shrink-0 h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                <span>In stock</span>
              </p>
            </div>
          </li>
                    <?php endforeach; ?>
        </ul>
      </section>

      <!-- Order summary -->
      <section aria-labelledby="summary-heading" class="bg-gray-50 rounded-lg p-8 mt-0 col-span-5">
        <h2 id="summary-heading" class="text-lg font-medium text-gray-900">Order summary</h2>

        <dl class="mt-6 space-y-4">
          <div class="flex items-center justify-between">
            <dt class="text-sm text-gray-600">Subtotal</dt>
            <dd class="text-sm font-medium text-gray-900">$99.00</dd>
          </div>
          <div class="border-t border-gray-200 pt-4 flex items-center justify-between">
            <dt class="flex items-center text-sm text-gray-600">
              <span>Shipping estimate</span>
              <span class="ml-2 flex-shrink-0 text-gray-400 hover:text-gray-500">
                <!-- Heroicon name: solid/question-mark-circle -->
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>
              </span>
            </dt>
            <dd class="text-sm font-medium text-gray-900">$5.00</dd>
          </div>
          <div class="border-t border-gray-200 pt-4 flex items-center justify-between">
            <dt class="text-base font-medium text-gray-900">Order total</dt>
            <dd class="text-base font-medium text-gray-900">&pound; <?php echo $subtotal; ?></dd>
          </div>
        </dl>

        <div class="mt-6">
          <button id="checkout" type="submit" class="w-full transition-all bg-sky-950 border border-transparent rounded-md shadow-sm py-3 px-4 text-base font-medium text-white hover:bg-sky-800">Checkout</button>
        </div>
      </section>
    </div>
  </div>
</div>
    <script>
        document.getElementById('checkout').addEventListener('click', function() {
          // Display a confirmation message
          alert("Thank you for your purchase! Your order has been placed.");
            localStorage.removeItem('cart');

          // Redirect to the index page after 1 seconds
          setTimeout(function() {
              window.location.href = "index.html";
          }, 1000);
        });
    </script>
    </body>
</html>
