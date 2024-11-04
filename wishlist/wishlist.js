document.addEventListener("DOMContentLoaded", function () {
    const wishlistLinks = document.querySelectorAll(".wishlist-link");
    const addToCartButtons = document.querySelectorAll(".add-to-cart-btn");

    // Load wishlist from localStorage and mark active hearts
    let savedWishlist = JSON.parse(localStorage.getItem("wishlist")) || [];

    // Add active state to hearts for products already in the wishlist
    wishlistLinks.forEach((link) => {
        const productId = link.getAttribute("data-product-id");
        if (savedWishlist.includes(productId)) {
            link.classList.add("active");
            link.querySelector("i").style.color = "#ff0000"; // Make heart red
        }
    });

    // Handle click event on the wishlist link
    wishlistLinks.forEach((link) => {
        link.addEventListener("click", function (event) {
            event.preventDefault(); // Prevent default action

            const productId = this.getAttribute("data-product-id");
            const heartIcon = this.querySelector("i");

            // Toggle the heart icon's active state
            this.classList.toggle("active");

            if (this.classList.contains("active")) {
                heartIcon.style.color = "#ff0000"; // Turn heart red
                // Add to localStorage wishlist
                if (!savedWishlist.includes(productId)) {
                    savedWishlist.push(productId);
                    localStorage.setItem("wishlist", JSON.stringify(savedWishlist));
                }

                // AJAX to add product to server-side wishlist
                $.ajax({
                    url: "../all/add_to_wishlist.php", // Adjust the path as necessary
                    type: "POST",
                    data: { product_id: productId },
                    success: function (response) {
                        const data = JSON.parse(response);
                        if (data.status === "success") {
                            alert(data.message); // Show success message
                        } else {
                            alert(data.message); // Show error message
                        }
                    },
                    error: function () {
                        alert("An error occurred while adding to wishlist.");
                    },
                });

            } else {
                heartIcon.style.color = "grey"; // Turn heart grey
                // Remove from localStorage wishlist
                savedWishlist = savedWishlist.filter((id) => id !== productId);
                localStorage.setItem("wishlist", JSON.stringify(savedWishlist));

                // AJAX to remove product from server-side wishlist
                $.ajax({
                    url: "remove_from_wishlist.php", // Adjust the path as necessary
                    type: "POST",
                    data: { product_id: productId },
                    success: function (response) {
                        const data = JSON.parse(response);
                        if (data.status === "success") {
                            alert(data.message); // Show success message
                        } else {
                            alert(data.message); // Show error message
                        }
                    },
                    error: function () {
                        alert("An error occurred while removing from wishlist.");
                    },
                });
            }
        });
    });

    // Handle "Add to Cart" button clicks
    addToCartButtons.forEach((button) => {
        button.addEventListener("click", function (event) {
            event.preventDefault();

            const productId = this.getAttribute("data-product-id");

            // AJAX to add product to cart
            $.ajax({
                url: "../all/add_to_cart.php", // Adjust the path as necessary
                type: "POST",
                data: { product_id: productId, quantity: 1 }, // Assuming quantity 1 by default
                success: function (response) {
                    if (response.status === "success") {
                        alert(response.message); // Show success message
                    } else {
                        alert(response.message); // Show error message
                    }
                },
                error: function () {
                    alert("An error occurred while adding the product to the cart.");
                },
            });
        });
    });
});
