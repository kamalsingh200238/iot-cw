// Function to update the cart on page load
function updateCartOnLoad() {
  // Read cart items from local storage
  const cartItems = getCartItems();

  // Update displayed quantities for each product
  cartItems.forEach((item) => {
    const quantitySpan = document.getElementById(`${item.id}-quantity`);
    if (quantitySpan) {
      quantitySpan.textContent = item.quantity;
      // if (item.quantity === 10) {
      //   // If quantity reaches 10, display a message indicating the limit
      //   document.getElementById("cart-text").textContent = "Cart (Max)";
      // }
    }
  });

  // Update the cart count
  updateCartCount();
}
updateCartOnLoad();

// Function to retrieve cart items from local storage
function getCartItems() {
  return JSON.parse(localStorage.getItem("cart")) || [];
}

// Function to save cart items to local storage
function saveCartItems(cart) {
  localStorage.setItem("cart", JSON.stringify(cart));
}

// Function to add item to cart
function addItemToCart(id) {
  const cartItems = getCartItems();
  const existingItemIndex = cartItems.findIndex((item) => item.id === id);
  if (existingItemIndex !== -1) {
    // If item already exists, update its quantity if less than 10
    if (cartItems[existingItemIndex].quantity < 10) {
      cartItems[existingItemIndex].quantity += 1;
    } else {
      alert(
        "Sorry, you can't add more than 10 units of a single product to your cart."
      );
    }
  } else {
    // If item does not exist, add it to the cart
    cartItems.push({ id: id, quantity: 1 });
  }
  saveCartItems(cartItems);
  updateDisplayedQuantity(id);
  updateCartCount();
}

// Function to remove item from cart
function removeItemFromCart(id) {
  const cartItems = getCartItems();
  const existingItemIndex = cartItems.findIndex((item) => item.id === id);
  if (existingItemIndex !== -1) {
    // If item exists, decrease its quantity
    cartItems[existingItemIndex].quantity -= 1;
    if (cartItems[existingItemIndex].quantity === 0) {
      // If quantity becomes 0, remove the item from the cart
      cartItems.splice(existingItemIndex, 1);
    }
    saveCartItems(cartItems);
    updateDisplayedQuantity(id);
    updateCartCount();
  }

  console.log(cartItems);
}

// Function to update the displayed quantity on the page
function updateDisplayedQuantity(id) {
  const quantitySpan = document.getElementById(`${id}-quantity`);
  const cartItems = getCartItems();
  const cartItem = cartItems.find((item) => item.id === id);
  if (cartItem) {
    quantitySpan.textContent = cartItem.quantity;
  } else {
    quantitySpan.textContent = 0;
  }
}

// Function to update the cart count
function updateCartCount() {
  const cartItems = getCartItems();
  let totalCount = 0;
  cartItems.forEach((item) => {
    totalCount += item.quantity;
  });
  document.getElementById("cart-quantity").textContent = `(${totalCount})`;
}

// Get the button element
const cartButton = document.getElementById("cart-button");

// Add click event listener to the button
cartButton.addEventListener("click", () => {
  // Retrieve data from Local Storage
  const cartData = localStorage.getItem("cart");

  // Check if cartData exists in Local Storage
  if (cartData) {
    // Construct the URL with cartData as a query parameter
    const url = "cart.php?data=" + encodeURIComponent(cartData);

    // Redirect the user to the cart page with the data in the URL
    window.location.href = url;
  } else {
    // Handle the case where cartData does not exist in Local Storage
    alert("No data in cart.");
  }
});
