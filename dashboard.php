<?php
session_start();
require_once 'admin/database.php'; // Include your database connection

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: <account>login.php');
    exit();
}

$userId = $_SESSION['id'];

// Fetch user details
$userQuery = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($userQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$userResult = $stmt->get_result();
$user = $userResult->fetch_assoc();
$stmt->close();

// Fetch user orders
$orderQuery = "SELECT * FROM orders WHERE user_id = ?";
$stmt = $conn->prepare($orderQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$orderResult = $stmt->get_result();
$orders = [];
while ($order = $orderResult->fetch_assoc()) {
    $orders[] = $order;
}
$stmt->close();

// Log the page view
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Motorcycle Accessories Store</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="image/logo.png" type="image/x-icon">
</head>

<body>
    <?php include 'header.php'; ?>
    <main>
        <section class="dashboard">
            <div class="container">
                <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?></h1>
                <div class="user-details">
                    <h2>Your Details</h2>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
                </div>

                <div class="order-history">
                    <h2>Your Orders</h2>
                    <?php if (empty($orders)): ?>
                        <p>You have not placed any orders yet.</p>
                    <?php else: ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($order['id']); ?></td>
                                        <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                                        <td>₹<?php echo htmlspecialchars($order['total_amount']); ?></td>
                                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                                        <td><a href="order_details.php?order_id=<?php echo htmlspecialchars($order['id']); ?>">View
                                                Details</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>

</html>