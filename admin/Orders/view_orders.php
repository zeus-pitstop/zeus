<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
require_once "../database.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_style.css">
    <script src="admin_scripts.js" defer></script>
    <script src="login-register/other_js_files.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>View Orders</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "
                    SELECT o.order_id, u.full_name AS customer_name, o.total, o.status, o.order_date
                    FROM orders o
                    JOIN users u ON o.user_id = u.id
                ";
                $result = mysqli_query($conn, $query);
                
                if (!$result) {
                    echo "<tr><td colspan='6' class='text-danger'>Error executing query: " . mysqli_error($conn) . "</td></tr>";
                } else {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>{$row['order_id']}</td>
                                <td>{$row['customer_name']}</td>
                                <td>₹" . number_format($row['total'], 2) . "</td>
                                <td>{$row['status']}</td>
                                <td>{$row['order_date']}</td>
                                <td>
                                    <a href='update_order_status.php?id={$row['order_id']}' class='btn btn-warning'>Update Status</a>
                                </td>
                            </tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
