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
if (!empty($_GET["query"])) {
    $query = mysqli_real_escape_string($conn, $_GET["query"]);
    $stmt = $conn->prepare(
        "SELECT * FROM products WHERE name LIKE CONCAT('%', ?, '%') LIMIT 5"
    );
    $stmt->bind_param("s", $query);
} else {
    // If no query is provided, fetch all products
    $stmt = $conn->prepare("SELECT * FROM products");
}

// Execute prepared statement
if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}

// Get result
$result = $stmt->get_result();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pawsome | Store</title>
    <link rel="stylesheet" href="main.css" />
    <script defer src="cart-logic.js"></script>
</head>
<body>
<header class="font-bold text-white shadow-md bg-sky-950">
    <div class="flex justify-between items-center py-5 mx-auto max-w-7xl">
        <!-- Logo for the header -->
        <div>
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="3"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="lucide lucide-paw-print"
            >
                <circle cx="11" cy="4" r="2" />
                <circle cx="18" cy="8" r="2" />
                <circle cx="20" cy="16" r="2" />
                <path
                    d="M9 10a5 5 0 0 1 5 5v3.5a3.5 3.5 0 0 1-6.84 1.045Q6.52 17.48 4.46 16.84A3.5 3.5 0 0 1 5.5 10Z"
                />
            </svg>
        </div>

        <!-- Navbar links to different page -->
        <nav>
            <ul class="flex gap-10 justify-between">
                <li>
                    <a class="hover:text-yellow-200 transition-all" href="/">Home</a>
                </li>
                <li>
                    <a
                        href="#"
                        class="border-b border-white hover:text-yellow-200 transition-all"
                    >Store</a
                    >
                </li>
                <li>
                    <a
                        href="about.html"
                        class="hover:text-yellow-200 transition-all"
                    >About us</a
                    >
                </li>
                <li>
                    <a href="blog.html" class="hover:text-yellow-200 transition-all"
                    >Blog</a
                    >
                </li>
            </ul>
        </nav>

        <div class="flex gap-10 justify-between items-center">
            <!-- Cart button -->
            <div>
                <button id="cart-button" class="py-2 px-2 rounded-md relative border-white border-3">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="lucide lucide-shopping-cart fill-white"
                    >
                        <circle cx="8" cy="21" r="1" />
                        <circle cx="19" cy="21" r="1" />
                        <path
                            d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"
                        />
                    </svg>
                    <span
                        id="cart-quantity"
                        class="absolute right-0 top-0 translate-x-1/2 -translate-y-1/2 px-2 py-2 inline-block bg-yellow-100 rounded-full text-sky-950"
                    >0
              </span>
                </button>
            </div>
            <!-- Login button -->
            <div>
                <a
                    href="login.php"
                    class="py-2 px-5 rounded-md border-white border-3"
                >
                    Login
                </a>
            </div>
        </div>
    </div>
</header>
<section class="max-w-7xl mx-auto px-8">
    <form action="store.php" method="get">
        <div class="flex justify-center gap-5 pt-16">
            <input
                type="search"
                placeholder="Search for products...."
                name="query"
                class="max-w-xl rounded-md border-gray-300"
            />
            <button
                type="submit"
                class="py-2 px-5 mr-5 font-bold text-white rounded-md border-3 border-sky-950 bg-sky-950 hover:bg-sky-800 transition-all"
            >
                Search
            </button>
        </div>
    </form>
</section>
<section class="max-w-7xl mx-auto px-8">
    <div class="py-16 px-8 mx-auto max-w-7xl">
        <!-- Cards container -->
        <div class="grid grid-cols-4 gap-5 justify-items-center">
            <!-- Display products -->
            <?php
            while ($row = $result->fetch_assoc()) : ?>
                <div class="flex flex-col gap-5 items-stretch p-5 bg-yellow-100 rounded-lg">
                    <img
                        src="https://cdn.wallpapersafari.com/45/36/E1gFSV.jpg"
                        alt="white image"
                        class="object-cover w-60 rounded-md aspect-square"
                    />
                    <div class="px-2">
                        <p class="mb-5 text-center"><?php echo $row[ "name" ]; ?></p>
                        <p class="flex gap-5 justify-between items-center">
                            <span>&pound; <?php echo $row["price"]; ?></span>
                            <span class="text-sky-950 inline-flex gap-2 items-center">
                                <button onclick="addItemToCart(<?php echo $row[ "id" ]; ?>)">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="32"
                                        height="32"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-circle-plus fill-sky-950"
                                    >
                                      <circle cx="12" cy="12" r="10" />
                                      <path d="M8 12h8" class="stroke-yellow-100" />
                                      <path d="M12 8v8" class="stroke-yellow-100" />
                                    </svg>
                                </button>
                                <span id="<?php echo $row["id"]; ?>-quantity">0</span>
                                <button onclick="removeItemFromCart(<?php echo $row["id"]; ?>)">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="32"
                                        height="32"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-circle-minus"
                                    >
                                      <circle cx="12" cy="12" r="10" />
                                      <path d="M8 12h8" />
                                    </svg>
                                </button>
                            </span>
                        </p>
                    </div>
                </div>
            <?php endwhile;
$stmt->close();
$conn->close();
?>
        </div>
    </div>
</section>
<footer class="p-16 bg-sky-950 text-white font-bold text-center">
    <div>
        <p class="mb-5">Pawsome cares about you</p>
        <p>For any queries contact us on: +44-7777777777</p>
    </div>
</footer>
</body>
</html>
