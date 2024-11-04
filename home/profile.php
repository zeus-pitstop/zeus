<?php
session_start();
require_once '../admin/database.php'; // Adjust the path if necessary

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../account/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Query to get user information
$query = "SELECT username, email, profile_pic, registration_date FROM site_users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $profile_pic, $registration_date);
$stmt->fetch();
$stmt->close();

// Format the registration date
$formatted_date = date("Y-m-d", strtotime($registration_date));

// Query to get order history with product details
$query_orders = "
    SELECT orders.order_id, orders.order_date, orders.status, orders.total, 
           products.name AS product_name, products.price, products.image, order_items.quantity
    FROM orders
    JOIN order_items ON orders.order_id = order_items.order_id
    JOIN products ON order_items.product_id = products.product_id
    WHERE orders.user_id = ?";
$stmt_orders = $conn->prepare($query_orders);
$stmt_orders->bind_param("i", $user_id);
$stmt_orders->execute();
$stmt_orders->bind_result($order_id, $order_date, $status, $total, $product_name, $price, $image, $quantity);
$orders = [];
while ($stmt_orders->fetch()) {
    $orders[] = [
        'order_id' => $order_id,
        'order_date' => $order_date,
        'status' => $status,
        'total' => $total,
        'product_name' => $product_name,
        'price' => $price,
        'image' => $image,
        'quantity' => $quantity
    ];
}
$stmt_orders->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="profile-section">
        <div class="profile-container">
            <h2>Your Profile</h2>
            <div class="profile-details">
                <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                <p><strong>Registration Date:</strong> <?php echo htmlspecialchars($formatted_date); ?></p>
            </div>

            <div class="order-history">
                <a href="../order/orders.php" class="btn view-orders-btn">View Order History</a>
            </div>

            <div class="profile-actions">
                <a href="profile_edit.php" class="btn edit-profile-btn">Edit Profile</a>
                <button class="btn logout-btn" onclick="logout()">Logout</button>
            </div>

            <div class="delete-account">
                <form id="delete-account-form" action="../account/delete_account.php" method="POST" style="display: none;">
                    <!-- This form will be submitted when the user confirms account deletion -->
                </form>
                <button class="btn delete-btn" onclick="deleteAccount()">Delete Account</button>
            </div>

        </div>
    </div>

    <script src="profile.js"></script>
</body>

</html>