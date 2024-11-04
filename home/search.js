document.addEventListener("DOMContentLoaded", function () {
    // Your search.js code here
    const searchInput = document.querySelector('.search-input'); // Make sure the class/selector is correct
    if (searchInput) {
        searchInput.addEventListener('input', function (e) {
            // Your search logic here
            const query = e.target.value;
            console.log('Search input:', query);
        });
    } else {
        console.error('Search input element not found.');
    }

    // Handle "Move to Cart" button click
    $(document).on('click', '.move-to-cart-btn', function(e) {
        e.preventDefault();
        var productId = $(this).closest('form').find('input[name="product_id"]').val();

        $.ajax({
            url: 'search.php',
            type: 'POST',
            data: {
                move_to_cart: true,
                product_id: productId
            },
            success: function(response) {
                alert(response);
                // Optionally, update cart count or provide user feedback
            }
        });
    });
});
