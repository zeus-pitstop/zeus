document.addEventListener("DOMContentLoaded", function () {
  // Carousel Functionality
  const prevButton = document.querySelector(".carousel-control-prev");
  const nextButton = document.querySelector(".carousel-control-next");
  const carousel = document.querySelector(".carousel");
  const items = document.querySelectorAll(".carousel-item");
  let currentIndex = 0;

  function updateCarousel() {
    const offset = -currentIndex * 100; // 100% of the carousel width
    carousel.style.transform = `translateX(${offset}%)`;
  }

  if (prevButton && nextButton) {
    prevButton.addEventListener("click", function () {
      clearInterval(autoSlideInterval);
      currentIndex = currentIndex > 0 ? currentIndex - 1 : items.length - 1;
      updateCarousel();
    });

    nextButton.addEventListener("click", function () {
      clearInterval(autoSlideInterval);
      currentIndex = currentIndex < items.length - 1 ? currentIndex + 1 : 0;
      updateCarousel();
    });
  }

  // Ensure carousel items are displayed inline
  carousel.style.display = "flex";
  items.forEach((item) => {
    item.style.flex = "0 0 100%"; // Each item takes up 100% of the carousel width
  });

  // Automatic image slide feature
  let autoSlideInterval = setInterval(() => {
    currentIndex = currentIndex < items.length - 1 ? currentIndex + 1 : 0;
    updateCarousel();
  }, 5000); // Change the slide every 5 seconds

  // Feedback Form Functionality
  const ratingInput = document.getElementById("rating");
  const ratingValue = document.getElementById("rating-value");
  const submitFeedbackButton = document.querySelector(".submit-feedback");
  const feedbackMessage = document.getElementById("feedback-message");
  const successMessage = document.querySelector(".feedback-success");
  const errorMessage = document.querySelector(".feedback-error");

  // Update rating value display
  if (ratingInput && ratingValue) {
    ratingInput.addEventListener("input", () => {
      ratingValue.textContent = ratingInput.value;
    });
  }

  // Handle feedback submission
  if (submitFeedbackButton) {
    submitFeedbackButton.addEventListener("click", function (event) {
      event.preventDefault(); // Prevent the form from submitting the traditional way

      const productId = submitFeedbackButton.getAttribute("data-product-id"); // Retrieve the product ID
      const rating = ratingInput.value;
      const message = feedbackMessage.value.trim();

      // Validate feedback message
      if (!message) {
        errorMessage.textContent = "Feedback message cannot be empty.";
        errorMessage.style.display = "block";
        successMessage.style.display = "none";
        return;
      }

      // AJAX request for feedback submission
      const xhr = new XMLHttpRequest();
      xhr.open("POST", "submit_feedback.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

      xhr.onload = function () {
        if (xhr.status === 200) {
          if (xhr.responseText === "success") {
            successMessage.style.display = "block";
            errorMessage.style.display = "none";
            feedbackMessage.value = "";
            ratingInput.value = 0;
            ratingValue.textContent = "0";
          } else {
            errorMessage.textContent = xhr.responseText;
            errorMessage.style.display = "block";
            successMessage.style.display = "none";
          }
        } else {
          errorMessage.textContent = "An error occurred. Please try again.";
          errorMessage.style.display = "block";
          successMessage.style.display = "none";
        }
      };

      xhr.send(
        `product_id=${productId}&rating=${rating}&message=${encodeURIComponent(
          message
        )}`
      );
    });
  }

  // Handle rating sliders for multiple elements (if applicable)
  const ratingSliders = document.querySelectorAll('input[type="range"]');
  ratingSliders.forEach((slider) => {
    const sliderId = slider.id.split("-")[1];
    const ratingValueElement = document.getElementById(
      `rating-value-${sliderId}`
    );

    slider.addEventListener("input", () => {
      if (ratingValueElement) {
        ratingValueElement.textContent = slider.value;
      }
    });
  });

  // Review Deletion Functionality
  const deleteButtons = document.querySelectorAll(".delete-review");
  deleteButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const reviewId = this.getAttribute("data-review-id");

      if (confirm("Are you sure you want to delete this review?")) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_review.php", true);
        xhr.setRequestHeader(
          "Content-Type",
          "application/x-www-form-urlencoded"
        );

        xhr.onload = function () {
          if (xhr.status === 200 && xhr.responseText === "success") {
            location.reload(); // Reload the page upon successful deletion
          } else {
            alert("Error deleting review. Please try again.");
          }
        };

        xhr.send("review_id=" + encodeURIComponent(reviewId));
      }
    });
  });

  // Wishlist Functionality
  const wishlistButton = document.querySelector(".wishlist-btn");
  if (wishlistButton) {
    wishlistButton.addEventListener("click", function () {
      const productId = this.getAttribute("data-product-id");

      const xhr = new XMLHttpRequest();
      xhr.open("POST", "add_to_wishlist.php", true); // Adjust path as necessary
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

      xhr.onload = function () {
        if (xhr.status === 200) {
          const response = JSON.parse(xhr.responseText);
          alert(response.message); // Show appropriate success or error message
        } else {
          alert("An error occurred. Please try again.");
        }
      };

      xhr.send("product_id=" + encodeURIComponent(productId));
    });
  }

// Add to Cart Functionality
const cartButton = document.querySelector(".add-to-cart");
if (cartButton) {
    cartButton.closest("form").addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent default form submission
        const productId = this.querySelector('input[name="product_id"]').value;
        const quantity = this.querySelector('input[name="quantity"]').value;

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "../all/add_to_cart.php", true); // Adjust path as necessary
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                alert(response.message); // Show appropriate success or error message
                if (response.status === "success") {
                    updateCartCount(); // Update the cart count if you have this functionality
                }
            } else {
                alert("An error occurred. Please try again.");
            }
        };

        xhr.send("product_id=" + encodeURIComponent(productId) + "&quantity=" + encodeURIComponent(quantity));
    });
}

});
