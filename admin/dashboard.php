<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

// Set session timeout (in seconds)
$timeout = 900; // 15 minutes

// Check if the timeout period has passed
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// Update last activity time
$_SESSION['last_activity'] = time();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Cache-Control" content="post-check=0, pre-check=0">
    <meta http-equiv="Pragma" content="no-cache">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_style.css">
    <link rel="stylesheet" href="logout.php">
    <script src="admin_scripts.js" defer></script>
    <script src="login-register/other_js_files.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Dashboard Overview</h1>
        <div class="row">
            <div class="col-md-3">
                <h3>Total Sales</h3>
                <?php
                require_once "database.php";
                $result = mysqli_query($conn, "SELECT SUM(price) as total_sales FROM orders WHERE status='completed'");
                $data = mysqli_fetch_assoc($result);
                echo "<p>₹" . number_format($data['total_sales'], 2) . "</p>";
                ?>
            </div>
            <div class="col-md-3">
                <h3>Number of Orders</h3>
                <?php
                $result = mysqli_query($conn, "SELECT COUNT(*) as total_orders FROM orders");
                $data = mysqli_fetch_assoc($result);
                echo "<p>" . $data['total_orders'] . "</p>";
                ?>
            </div>
            <div class="col-md-3">
                <h3>Number of Products</h3>
                <?php
                $result = mysqli_query($conn, "SELECT COUNT(*) as total_products FROM products");
                $data = mysqli_fetch_assoc($result);
                echo "<p>" . $data['total_products'] . "</p>";
                ?>
            </div>
            <div class="col-md-3">
                <h3>User Registrations</h3>
                <?php
                $result = mysqli_query($conn, "SELECT COUNT(*) as total_users FROM users");
                $data = mysqli_fetch_assoc($result);
                echo "<p>" . $data['total_users'] . "</p>";
                ?>
            </div>
        </div>
        <h2>Recent Activities</h2>
        <h3>Recent Orders</h3>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM orders ORDER BY order_date DESC LIMIT 5");
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p>Order ID: " . $row['order_id'] . " - Status: " . $row['status'] . "</p>";
        }
        ?>
        <h3>Recent Reviews</h3>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM reviews ORDER BY review_date DESC LIMIT 5");
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p>Product ID: " . $row['product_id'] . " - Rating: " . $row['rating'] . "</p>";
        }
        ?>
    </div>
</body>
</html>
