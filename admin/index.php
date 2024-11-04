<?php
include 'database.php';
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

// Fetch total users
$result = $conn->query("SELECT COUNT(*) AS total_users FROM site_users");
$row = $result->fetch_assoc();
$total_users = $row['total_users'];

// Fetch total products
$result = $conn->query("SELECT COUNT(*) AS total_products FROM products");
$row = $result->fetch_assoc();
$total_products = $row['total_products'];

// Fetch total orders
$result = $conn->query("SELECT COUNT(*) AS total_orders FROM orders");
$row = $result->fetch_assoc();
$total_orders = $row['total_orders'];

// Fetch total feedback
$result = $conn->query("SELECT COUNT(*) AS total_feedback FROM feedback");
$row = $result->fetch_assoc();
$total_feedback = $row['total_feedback'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="admin_style.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="Products/add_product.css">
    <link rel="stylesheet" href="Products/manage_products.css">
    <link rel="stylesheet" href="Products/manage_categories.css">
    <link rel="stylesheet" href="Orders/view_orders.css">
    <link rel="stylesheet" href="Orders/update_order_status.css">
    <link rel="stylesheet" href="Orders/order_details.css">
    <link rel="stylesheet" href="Users/view_users.css">
    <link rel="stylesheet" href="Users/edit_user.css">
    <link rel="stylesheet" href="Users/manage_permissions.css">
    <link rel="stylesheet" href="Inventory/inventory.css">
    <link rel="stylesheet" href="Inventory/stock_alerts.css">
    <link rel="stylesheet" href="Reviews/manage_reviews.css">
    <link rel="stylesheet" href="Reviews/manage_feedback.css">
    <link rel="stylesheet" href="Settings/site_settings.css">
    <link rel="stylesheet" href="Settings/payment_settings.css">
    <link rel="stylesheet" href="Settings/shipping_settings.css">
    <link rel="stylesheet" href="Security/access_logs.css">
    <link rel="stylesheet" href="Security/system_updates.css">
    <link rel="stylesheet" href="Backup/database_backup.css">
    <link rel="stylesheet" href="Backup/restore_data.css">
    <link rel="stylesheet" href="Support/support_requests.css">
    <script src="admin_scripts.js" defer></script>
    <script src="other_js_files.js" defer></script>
</head>

<body>
    <div class="sidebar">
        <h2>Navigation</h2>
        <ul>
            <li>
                <ul>
                    <li><a href="search_product.php">Search By ID</a></li>
                    <li><a href="Orders/view_orders.php">View Orders</a></li>
                </ul>
            </li>
            <li>
                <ul>
                    <li><a href="Products/add_product.php">Add New Product</a></li>
                    <li><a href="Products/manage_products.php">Manage Products</a></li>
                    <li><a href="Products/manage_categories.php">Manage Categories</a></li>
                </ul>
            </li>
            <li>
                <ul>
                    <li><a href="Users/view_users.php">View Users</a></li>
                </ul>
            </li>
            <li>
                <ul>
                    <li><a href="Inventory/inventory.php">Inventory</a></li>
                    <li><a href="Inventory/stock_alerts.php">Stock Alerts</a></li>
                </ul>
            </li>
            <li>
                <ul>
                    <li><a href="Reviews/manage_feedback.php">Manage Feedback</a></li>
                </ul>
            </li>
            <li>
                <ul>
                    <li><a href="Security/access_logs.php">Access Logs</a></li>
                </ul>
            </li>
            <li>
                <ul>
                    <li><a href="Backup/database_backup.php">Database Backup</a></li>
                </ul>
            </li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <main>
        <h2>Welcome to the Admin Dashboard</h2>
        <section class="overview">
            <h3>Overview</h3>
            <div class="card-container">
                <div class="card">
                    <h4>Total Users</h4>
                    <p id="total-users"><?php echo $total_users; ?></p>
                </div>
                <div class="card">
                    <h4>Total Products</h4>
                    <p id="total-products"><?php echo $total_products; ?></p>
                </div>
                <div class="card">
                    <h4>Total Orders</h4>
                    <p id="total-orders"><?php echo $total_orders; ?></p>
                </div>
                <div class="card">
                    <h4>Total Feedbacks</h4>
                    <p id="total-feedback"><?php echo $total_feedback; ?></p>
                </div>
            </div>
        </section>

        <section class="recent-activity">
            <h3>Recent Activity</h3>
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch recent user activities
                    $sql = "SELECT su.username, ua.action, ua.action_date 
                    FROM user_activity ua 
                    JOIN site_users su ON ua.user_id = su.id 
                    ORDER BY ua.action_date DESC 
                    LIMIT 3";
                    $result = $conn->query($sql);

                    // Check if results exist and display them
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['action']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['action_date']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No recent activity</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
        <form>
        <div class="mb-3">
            <a href="search.php" class="btn btn-primary">Search All by ID</a>
        </div>
        </form>
    </main>
</body>

</html>