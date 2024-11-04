document.addEventListener("DOMContentLoaded", () => {
  const buttons = document.querySelectorAll(".add-to-cart");

  buttons.forEach((button) => {
      button.addEventListener("click", (event) => {
          const productElement = button.closest(".product-item");
          const id = productElement.getAttribute("data-id");
          const name = productElement.getAttribute("data-name");
          const price = parseFloat(productElement.getAttribute("data-price"));
          const quantity = 1; // Assuming you want to add one item per click

          checkLogin(event, id, name, price, quantity);
      });
  });
});

// Check if the user is logged in before adding to cart
function checkLogin(event, id, name, price, quantity) {
  console.log("Checking if user is logged in...");
  $.ajax({
      url: "../account/check_login.php",
      type: "GET",
      success: function (response) {
          const data = JSON.parse(response);
          console.log(data);

          if (!data.loggedIn) {
              console.log("User is not logged in.");
              event.preventDefault(); // Prevent the form from submitting
              alert("User not logged in, cannot add the product to cart.");
          } else {
              console.log("User is logged in. Proceeding with cart addition.");

              // AJAX request to add item to server-side cart
              $.ajax({
                  type: "POST",
                  url: "../all/add_to_cart.php",
                  data: { product_id: id, quantity: quantity },
                  dataType: "json",
                  success: function (response) {
                      if (response.status === 'success') {
                          alert(response.message);
                      } else {
                          alert(response.message);
                      }
                  },
                  error: function (xhr, status, error) {
                      console.error(error);
                      alert("An error occurred while adding to the cart.");
                  }
              });
          }
      },
      error: function () {
          alert("An error occurred while checking login status.");
      }
  });
}

document.addEventListener("DOMContentLoaded", function () {
  const wishlistLinks = document.querySelectorAll(".wishlist-link");

  // Load wishlist from localStorage and mark active hearts
  let savedWishlist = JSON.parse(localStorage.getItem("wishlist")) || [];

  wishlistLinks.forEach((link) => {
      const productId = link.getAttribute("data-product-id");
      if (savedWishlist.includes(productId)) {
          link.classList.add("active");
          link.querySelector("i").classList.add("filled");
      }
  });

  wishlistLinks.forEach((link) => {
      link.addEventListener("click", function (event) {
          event.preventDefault(); // Prevent default action

          const productId = this.getAttribute("data-product-id");
          savedWishlist = JSON.parse(localStorage.getItem("wishlist")) || [];

          if (!this.classList.contains("active")) {
              // Add to wishlist
              savedWishlist.push(productId);
              this.classList.add("active");
              link.querySelector("i").classList.add("filled");

              // AJAX to sync wishlist with server
              $.ajax({
                  url: "../all/add_to_wishlist.php",
                  type: "POST",
                  data: { product_id: productId },
                  success: function (response) {
                      console.log(response); // Handle server response
                  },
                  error: function () {
                      alert("Failed to add to wishlist. Please try again.");
                  }
              });
          } else {
              // Remove from wishlist
              savedWishlist = savedWishlist.filter(id => id !== productId);
              this.classList.remove("active");
              link.querySelector("i").classList.remove("filled");

              // AJAX to sync wishlist with server
              $.ajax({
                  url: "../all/remove_from_wishlist.php",
                  type: "POST",
                  data: { product_id: productId },
                  success: function (response) {
                      console.log(response); // Handle server response
                  },
                  error: function () {
                      alert("Failed to remove from wishlist. Please try again.");
                  }
              });
          }

          // Update localStorage
          localStorage.setItem("wishlist", JSON.stringify(savedWishlist));
      });
  });
});
