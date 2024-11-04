<?php
session_start();
require_once '../admin/database.php'; // Include your database connection file

// Log the page view
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Fetch user's orders from the database
$orders = [];
if ($user_id) {
    // Fetch orders with product names
    $order_query = "
        SELECT o.order_id, o.order_date, o.status, o.total, GROUP_CONCAT(p.name SEPARATOR ', ') as product_names
        FROM orders o
        JOIN order_items oi ON o.order_id = oi.order_id
        JOIN products p ON oi.product_id = p.product_id
        WHERE o.user_id = ?
        GROUP BY o.order_id
        ORDER BY o.order_date DESC";
    $order_stmt = $conn->prepare($order_query);
    $order_stmt->bind_param("i", $user_id);
    $order_stmt->execute();
    $order_result = $order_stmt->get_result();
    while ($row = $order_result->fetch_assoc()) {
        $orders[] = $row;
    }
    $order_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders - Motorcycle Accessories Store</title>
    <link rel="stylesheet" href="../home/main.css">
    <link rel="stylesheet" href="orders.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="../image/logo.png" type="image/x-icon">
    <script src="../account/toggleform.js" defer></script>
    <script src="../home/search.js"></script>
</head>

<body>
    <?php include '../home/header.php'; ?>
    <main>
        <section class="orders">
            <div class="container">
                <h1>Your Orders</h1>
                <?php if (empty($orders)): ?>
                    <p>You currently have no orders. Browse our products and place an order to view it here.</p>
                    <a href="../all/all.php" class="btn">Start Shopping</a>
                <?php else: ?>
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Products</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                                    <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                                    <td><?php echo htmlspecialchars($order['status']); ?></td>
                                    <td>₹<?php echo htmlspecialchars(number_format($order['total'], 2)); ?></td>
                                    <td><?php echo htmlspecialchars($order['product_names']); ?></td>
                                    <td><a href="track_order.php?order_id=<?php echo htmlspecialchars($order['order_id']); ?>"
                                            class="btn track-btn">Track Order</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        <?php include '../home/footer.php'; ?>
    </footer>
</body>

</html>
