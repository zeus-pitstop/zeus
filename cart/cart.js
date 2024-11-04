$(document).ready(function() {
  $('.add-to-cart-form').on('submit', function(e) {
      e.preventDefault(); // Prevent the form from submitting normally

      // Create a FormData object to gather the form inputs
      var formData = $(this).serialize();

      // Make the AJAX call to add the product to the cart
      $.ajax({
          url: '../all/add_to_cart.php',  // The PHP script where form data will be sent
          type: 'POST',
          data: formData,  // Send form data as POST
          dataType: 'json',  // Expect a JSON response from the server
          success: function(response) {
              if (response.status === 'success') {
                  // Show success message in an alert box
                  alert(response.message);
              } else {
                  // Show error message in an alert box
                  alert(response.message);
              }
          },
          error: function() {
              // Handle any errors
              alert('There was an error adding the product to the cart. Please try again.');
          }
      });
  });
});

function removeFromCart(productId) {
  $.ajax({
      url: "remove_from_cart.php",
      type: "GET",
      data: { productId: productId },
      success: function(response) {
          // Remove the item from the UI
          const itemElement = document.getElementById(`cart-item-${productId}`);
          if (itemElement) {
              itemElement.remove();
          }
      },
      error: function() {
          alert("An error occurred while removing the item.");
      }
  });
}


document.addEventListener("DOMContentLoaded", function () {
    const updateCartForm = document.querySelector("#update-cart-form"); // Adjust selector as needed
  
    if (updateCartForm) {
      updateCartForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent the default form submission
  
        const updatedQuantities = {}; // Object to hold product IDs and their updated quantities
  
        // Collect updated quantities from form inputs
        const quantityInputs = updateCartForm.querySelectorAll(".quantity-input"); // Adjust selector as needed
        quantityInputs.forEach(input => {
          const productId = input.getAttribute("data-product-id"); // Assuming each input has a data attribute with the product ID
          const quantity = parseInt(input.value, 10);
          updatedQuantities[productId] = quantity; // Store the updated quantity
        });
  
        // AJAX request to update cart
        $.ajax({
          url: "update_cart.php",
          type: "POST",
          data: { quantity: updatedQuantities }, // Pass the updated quantities
          success: function (response) {
            const data = JSON.parse(response);
            if (data.status === "success") {
              // Handle success, maybe refresh the cart display
              updateCartCount();
              // Optionally, update the UI with the new quantities
            } else {
              alert(data.message); // Handle any error messages from the server
            }
          },
          error: function () {
            alert("An error occurred while updating the cart.");
          }
        });
      });
    }
  });  

// Event listener for "Remove" buttons
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-remove').forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            const productId = button.getAttribute('data-product-id');  // Get productId from data attribute
            removeFromCart(productId);
        });
    });
});

// Function to reset cart UI after successful payment
function resetCartUI() {
    // Optionally clear all cart items from the UI
    const cartItemsContainer = document.querySelector('.cart-items'); // Assuming cart items are inside a container with class 'cart-items'
    if (cartItemsContainer) {
        cartItemsContainer.innerHTML = ''; // Clear all items
    }
}

// Call resetCartUI after payment confirmation (based on URL parameters)
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('payment') === 'success') {
        resetCartUI();
    }
});