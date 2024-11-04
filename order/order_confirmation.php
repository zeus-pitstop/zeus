<?php
session_start();
require_once "../admin/database.php"; // Connect to the database

// Check if order_id is set in the URL
if (!isset($_GET['order_id'])) {
    echo "<div class='alert alert-danger'>Order ID is missing. Please try again.</div>";
    exit();
}

$order_id = $_GET['order_id'];

// Fetch order details
$order_query = "SELECT * FROM orders WHERE order_id = '$order_id'";
$order_result = mysqli_query($conn, $order_query);

if (mysqli_num_rows($order_result) == 0) {
    echo "<div class='alert alert-danger'>Order not found. Please try again.</div>";
    exit();
}

$order = mysqli_fetch_assoc($order_result);

// Fetch order items and update stock
$items_query = "SELECT oi.*, p.name, p.price, p.stock FROM order_items oi JOIN products p ON oi.product_id = p.product_id WHERE oi.order_id = '$order_id'";
$items_result = mysqli_query($conn, $items_query);

// Reduce the stock for each ordered item
while ($item = mysqli_fetch_assoc($items_result)) {
    $ordered_quantity = $item['quantity'];
    $product_id = $item['product_id'];

    // Calculate the new available quantity
    $new_stock = $item['stock'] - $ordered_quantity;

    // Ensure the stock does not go below 0
    if ($new_stock < 0) {
        $new_stock = 0; // Set to 0 if it goes below
    }

    // Update the stock quantity in the products table
    $update_query = "UPDATE products SET stock = ? WHERE product_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ii", $new_stock, $product_id);
    $update_stmt->execute();
    $update_stmt->close();
}

// Re-fetch the order items for displaying the order details
$items_result = mysqli_query($conn, $items_query);

date_default_timezone_set('Asia/Kolkata');

// Log the page view
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if ($user_id) {
    $action_performed = 'User Placed an Order, Order ID ' . $order_id;
    $action_date = date('Y-m-d H:i:s');

    // Prepare and execute the log query
    $log_query = "INSERT INTO user_activity (user_id, action, action_date) VALUES (?, ?, ?)";
    $log_stmt = $conn->prepare($log_query);
    $log_stmt->bind_param("iss", $user_id, $action_performed, $action_date);
    $log_stmt->execute();
    $log_stmt->close();
}

// Reset the cart
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, remove items from the database cart
    $delete_cart_query = "DELETE FROM cart WHERE user_id = ?";
    $delete_stmt = $conn->prepare($delete_cart_query);
    $delete_stmt->bind_param("i", $_SESSION['user_id']);
    $delete_stmt->execute();
    $delete_stmt->close();
} else {
    // If the user is not logged in, reset the session cart
    unset($_SESSION['cart']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Motorcycle Accessories Store</title>
    <link rel="stylesheet" href="../home/main.css">
    <link rel="stylesheet" href="order_confirmation.css">
</head>

<body>
    <?php include '../home/header.php'; ?>
    <main>
        <section class="order-confirmation">
            <div class="container">
                <h1>Order Confirmation</h1>
                <p>Thank you for your order! Your order ID is <strong><?php echo htmlspecialchars($order_id); ?></strong>.</p>

                <!-- Display address, phone number, and payment method -->
                <p><strong>Shipping Address:</strong> <?php echo htmlspecialchars($order['shipping_address']); ?></p>
                <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($order['phone_number']); ?></p>
                <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>

                <h2>Order Details</h2>
                <table class="order-details">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($item = mysqli_fetch_assoc($items_result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                <td>₹<?php echo htmlspecialchars($item['price']); ?></td>
                                <td>₹<?php echo htmlspecialchars($item['price'] * $item['quantity']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <p class="total-cost">Total Cost: ₹<?php echo htmlspecialchars($order['total']); ?></p>
                <a href="../home/index.php" class="btn">Return to Home</a>
            </div>
        </section>

    </main>

    <footer>
        <?php include '../home/footer.php'; ?>
    </footer>
</body>

</html>

<?php
mysqli_close($conn);
?>