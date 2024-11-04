<?php
session_start();
require_once '../admin/database.php';

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Fetch cart items from the database for logged-in users
    $query = "SELECT p.*, c.quantity FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();    

    $cartItems = [];
    while ($row = $result->fetch_assoc()) {
        $cartItems[$row['product_id']] = $row;
    }
    $stmt->close();
} else {
    // Show message to log in if the user is not logged in
    echo "<script>
        alert('You are not logged in. Please log in to view your cart items.');
        window.location.href = '../account/login.php'; // Redirect to login page
    </script>";
    exit(); // Stop further script execution
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - Motorcycle Accessories Store</title>
    <link rel="stylesheet" href="cart.css">
    <link rel="stylesheet" href="../home/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="../image/logo.png" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
    <script src="cart.js" defer></script>
    <script src="../home/search.js"></script>
</head>

<body>
    <?php include '../home/header.php'; ?>
    <main>
        <section class="cart">
            <div class="container">
                <h1>Your Shopping Cart</h1>
                <?php if (empty($cartItems)): ?>
                    <p>Your cart is empty.</p>
                    <a href="../all/all.php" class="btn">Browse Products</a>
                <?php else: ?>
                    <form action="update_cart.php" method="post">
                        <table>
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Image</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cartItems as $productId => $product): ?>
                                    <tr id="cart-item-<?php echo $productId; ?>">
                                        <td><?php echo htmlspecialchars($product['name'] ?? 'Unknown Product'); ?></td>
                                        <td>
                                            <img src="../image/<?php echo htmlspecialchars($product['image'] ?? 'default.jpg'); ?>"
                                                alt="<?php echo htmlspecialchars($product['name'] ?? 'Product Image'); ?>"
                                                class="cart-item-image">
                                        </td>
                                        <td>₹<?php echo htmlspecialchars($product['price'] ?? '0'); ?></td>
                                        <td>
                                            <input type="number" name="quantity[<?php echo htmlspecialchars($productId); ?>]"
                                                value="<?php echo htmlspecialchars($product['quantity'] ?? '1'); ?>" min="1"
                                                required>
                                        </td>
                                        <td>₹<?php echo htmlspecialchars(($product['price'] ?? 0) * ($product['quantity'] ?? 1)); ?></td>
                                        <td>
                                            <a href="remove_from_cart.php?productId=<?php echo urlencode($productId); ?>" class="btn btn-remove" data-product-id="<?php echo $productId; ?>">Remove</a>
                                            <button type="submit" name="update_quantity[<?php echo htmlspecialchars($productId); ?>]"
                                                value="Update"
                                                class="btn btn-update">Update</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </form>
                    <a href="../order/checkout.php" class="btn">Proceed to Checkout</a>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <footer>
        <?php include '../home/footer.php'; ?>
    </footer>

    <script>
        $(document).ready(function() {
            // Update cart count on page load
            updateCartCount();

            $('.btn-remove').on('click', function(e) {
                e.preventDefault();
                var productId = $(this).data('product-id');
                $.ajax({
                    type: 'GET',
                    url: 'remove_from_cart.php',
                    data: {
                        productId: productId
                    },
                    success: function(response) {
                        const data = JSON.parse(response);
                        $('#cart-item-' + productId).remove(); // Remove item from cart display
                        const cartCountElement = document.getElementById('cart-count'); // Adjust the ID as needed
                        cartCountElement.innerText = data.count; // Update the cart count display
                    },
                    error: function(err) {
                        console.error('Error removing item:', err);
                    }
                });
            });

        });

        function updateCartCount() {
            $.ajax({
                url: 'get_cart_count.php',
                method: 'GET',
                success: function(response) {
                    const data = JSON.parse(response);
                    const cartCountElement = document.getElementById('cart-count'); // Adjust the ID as needed
                    cartCountElement.innerText = data.count; // Update the cart count display
                },
                error: function(err) {
                    console.error('Error fetching cart count:', err);
                }
            });
        }
    </script>
</body>

</html>