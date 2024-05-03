<?php
session_start(); // Start the session to access session variables

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
  $username = $_SESSION['username'];
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Pawsome | About us</title>
  <link rel="stylesheet" href="main.css" />
  <script src="cart-logic.js" defer></script>
</head>

<body>
  <header class="font-bold text-white shadow-md bg-sky-950">
    <div class="flex justify-between items-center py-5 mx-auto max-w-7xl">
      <!-- Logo for the header -->
      <div>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-paw-print">
          <circle cx="11" cy="4" r="2" />
          <circle cx="18" cy="8" r="2" />
          <circle cx="20" cy="16" r="2" />
          <path d="M9 10a5 5 0 0 1 5 5v3.5a3.5 3.5 0 0 1-6.84 1.045Q6.52 17.48 4.46 16.84A3.5 3.5 0 0 1 5.5 10Z" />
        </svg>
      </div>

      <!-- Navbar links to different page -->
      <nav>
        <ul class="flex gap-10 justify-between">
          <li>
            <a class="hover:text-yellow-200 transition-all" href="/">Home</a>
          </li>
          <li>
            <a href="store.php" class="hover:text-yellow-200 transition-all">Store</a>
          </li>
          <li>
            <a href="#" class="border-b border-white hover:text-yellow-200 transition-all">About us</a>
          </li>
          <li>
            <a href="blog.php" class="hover:text-yellow-200 transition-all">Blog</a>
          </li>
        </ul>
      </nav>

      <div class="flex gap-10 justify-between items-center">
        <!-- Cart button -->
        <div>
          <button id="cart-button" class="py-2 px-2 rounded-md relative border-white border-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-cart fill-white">
              <circle cx="8" cy="21" r="1" />
              <circle cx="19" cy="21" r="1" />
              <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
            </svg>
            <span id="cart-quantity" class="absolute right-0 top-0 translate-x-1/2 -translate-y-1/2 px-2 py-2 inline-block bg-yellow-100 rounded-full text-sky-950">
              0
            </span>
          </button>
        </div>
        <!-- Login button -->
        <div>
          <?php if (isset($_SESSION['username'])) : ?>
            <span class="py-2 px-5 rounded-md border-white border-3">Hi <?php echo $username; ?></span>
          <?php else : ?>
            <a href="login.php" class="py-2 px-5 rounded-md border-white border-3">
              Login
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </header>
  <section class="max-w-7xl mx-auto px-8 py-16">
    <div>
      <div class="grid place-items-center mb-10">
        <img src="./images/sarah-with-dog.jpg" alt="Sarah with liam, her dog" class="max-w-96 rounded-lg" />
      </div>

      <h2 class="text-2xl font-bold text-sky-950">About Pawsome</h2>
      <p class="mb-8">
        At Pawsome, our story is one of passion, dedication, and a deep love
        for furry companions. Founded by Sarah Michaels, a devoted pet parent
        with a heart for animals, Pawsome was born out of a desire to provide
        pets and their owners with the very best in pet care and supplies.
      </p>

      <h2 class="text-2xl font-bold text-sky-950">
        Founder's Passion for Pets
      </h2>
      <p>
        Sarah's journey with her pets began years ago when she welcomed her
        first rescue pup into her home. As she immersed herself in the world
        of pet care, she became increasingly passionate about ensuring that
        her beloved pets—and pets everywhere—had access to the highest quality
        products and nutrition. She spent countless hours researching,
        testing, and hand-selecting the best supplies, and it wasn't long
        before her friends and neighbors began seeking her advice on all
        things pet-related.
      </p>

      <h2 class="text-2xl font-bold text-sky-950">A Journey of Dedication</h2>
      <p class="mb-8">
        Driven by her love for animals and her desire to make a positive
        difference in the lives of pets and their owners, Sarah decided to
        turn her passion into a business. Thus, Pawsome was born—a pet supply
        store dedicated to providing top-quality products, personalized
        service, and expert guidance to pet lovers everywhere.
      </p>

      <h2 class="text-2xl font-bold text-sky-950">
        Creating a Pawsome Experience
      </h2>
      <p class="mb-8">
        Today, Pawsome continues to uphold Sarah's vision, offering a
        carefully curated selection of premium pet supplies for dogs, cats,
        and small animals. From nutritious food and tasty treats to cozy beds,
        stylish accessories, and essential grooming supplies, every product at
        Pawsome is chosen with the well-being and happiness of pets in mind.
      </p>

      <h2 class="text-2xl font-bold text-sky-950">
        Our Commitment to Quality
      </h2>
      <p class="mb-8">
        As Sarah likes to say, "Our pets are more than just animals—they're
        cherished members of our families. And at Pawsome, we're here to help
        you give them the love and care they deserve."
      </p>

      <h2 class="text-2xl font-bold text-sky-950">
        Join the Pawsome Community
      </h2>
      <p class="mb-8">
        Join us at Pawsome and become part of our growing community of pet
        lovers. Together, we'll make every moment with our furry friends truly
        Pawsome.
      </p>
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
