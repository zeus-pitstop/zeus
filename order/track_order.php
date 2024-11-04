<?php
session_start();
require_once '../admin/database.php'; // Include your database connection file

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : null;

if (!$order_id) {
    // Redirect or show error if no order ID is provided
    header("Location: orders.php");
    exit;
}

// Fetch order details
$order_query = "SELECT order_id, order_date, status, total, shipping_address FROM orders WHERE order_id = ?";
$order_stmt = $conn->prepare($order_query);
$order_stmt->bind_param("i", $order_id);
$order_stmt->execute();
$order = $order_stmt->get_result()->fetch_assoc();
$order_stmt->close();

if (!$order) {
    // Handle case where the order is not found
    echo "Order not found.";
    exit;
}

// Fetch order items along with product names
$item_query = "
    SELECT oi.product_id, oi.quantity, oi.price, p.name 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.product_id 
    WHERE oi.order_id = ?
";
$item_stmt = $conn->prepare($item_query);
$item_stmt->bind_param("i", $order_id);
$item_stmt->execute();
$items = $item_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$item_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order #<?php echo htmlspecialchars($order_id); ?></title>
    <link rel="stylesheet" href="../home/main.css">
    <link rel="stylesheet" href="track_order.css">
    <link rel="icon" href="../image/logo.png" type="image/x-icon">
</head>

<body>
    <?php include '../home/header.php'; ?>
    <main>
        <section class="order-tracking">
            <div class="container">
                <h1>Track Order #<?php echo htmlspecialchars($order['order_id']); ?></h1>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
                <p><strong>Total Amount:</strong> ₹<?php echo htmlspecialchars(number_format($order['total'], 2)); ?></p>
                <h2>Items in Your Order</h2>
                <table class="order-items-table">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['product_id']); ?></td>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                <td>₹<?php echo htmlspecialchars(number_format($item['price'], 2)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <a href="orders.php" class="btn">Back to Orders</a>
            </div>
        </section>
    </main>

    <footer>
        <?php include '../home/footer.php'; ?>
    </footer>
</body>

</html>
