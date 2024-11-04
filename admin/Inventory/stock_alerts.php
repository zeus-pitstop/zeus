<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
require_once "../database.php";

// Debugging: Print SQL query
$query = "SELECT product_id, name, stock, stock_alert_level FROM products WHERE stock <= stock_alert_level";
echo "<!-- SQL Query: $query -->";

$result = mysqli_query($conn, $query);

if (!$result) {
    echo "<div class='alert alert-danger'>Error executing query: " . mysqli_error($conn) . "</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Alerts</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="edit_product.css">
    <script src="admin_scripts.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Stock Alerts</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Stock Quantity</th>
                    <th>Stock Alert Level</th>
                    <th>Stock Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    // Determine stock status
                    $stockStatus = '';
                    if ($row['stock'] == 0) {
                        $stockStatus = "Sorry! Out Of Stock, Will Be Available Soon";
                    } elseif ($row['stock'] <= 5) {
                        $stockStatus = "Hurry! Only Few Left";
                    } else {
                        $stockStatus = "In Stock";
                    }

                    echo "<tr>
                            <td>{$row['product_id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['stock']}</td>
                            <td>{$row['stock_alert_level']}</td>
                            <td>{$stockStatus}</td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
