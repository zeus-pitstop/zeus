<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

require_once "../database.php";

// Check if 'id' is set in GET request
if (isset($_GET['id'])) {
    $order_id = $_GET['id'];
    
    // Sanitize input
    $order_id = mysqli_real_escape_string($conn, $order_id);

    // Fetch order details
    $order_result = mysqli_query($conn, "SELECT * FROM orders WHERE order_id = '$order_id'");
    
    if ($order_result && mysqli_num_rows($order_result) > 0) {
        $order = mysqli_fetch_assoc($order_result);
        
        // Fetch customer details from the site_users table
        $customer_result = mysqli_query($conn, "SELECT username, email FROM site_users WHERE id = '{$order['id']}'");
        
        if ($customer_result && mysqli_num_rows($customer_result) > 0) {
            $customer = mysqli_fetch_assoc($customer_result);
        } else {
            echo "<div class='alert alert-danger'>Customer details not found.</div>";
            exit();
        }
    } else {
        echo "<div class='alert alert-danger'>Order not found or invalid ID.</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger'>Order ID not provided.</div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_style.css">
    <script src="admin_scripts.js" defer></script>
    <script src="login-register/other_js_files.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Order Details</h1>
        <h2>Order ID: <?php echo htmlspecialchars($order['order_id']); ?></h2>
        <p>Customer Name: <?php echo htmlspecialchars($customer['username']); ?></p>
        <p>Customer Email: <?php echo htmlspecialchars($customer['email']); ?></p>
        <p>Total Amount: ₹<?php echo number_format($order['price'], 2); ?></p>
        <p>Order Date: <?php echo htmlspecialchars($order['created_at']); ?></p>

        <h3>Order Items</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $items_result = mysqli_query($conn, "SELECT product_id, quantity, price FROM orders WHERE order_id = '$order_id'");
                while ($item = mysqli_fetch_assoc($items_result)) {
                    echo "<tr>
                            <td>" . htmlspecialchars($item['product_id']) . "</td>
                            <td>" . htmlspecialchars($item['quantity']) . "</td>
                            <td>₹" . number_format($item['price'], 2) . "</td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
