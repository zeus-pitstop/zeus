<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

require_once "../database.php";

// Check if 'order_id' is set in POST request
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    
    // Sanitize input
    $order_id = mysqli_real_escape_string($conn, $order_id);
    $status = mysqli_real_escape_string($conn, $status);
    
    $sql = "UPDATE orders SET status = '$status' WHERE order_id = '$order_id'";
    
    if (mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Order status updated successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating order status: " . mysqli_error($conn) . "</div>";
    }
}

// Check if 'id' is set in GET request
if (isset($_GET['id'])) {
    $order_id = $_GET['id'];
    
    // Sanitize input
    $order_id = mysqli_real_escape_string($conn, $order_id);
    
    $result = mysqli_query($conn, "SELECT * FROM orders WHERE order_id = '$order_id'");
    
    if ($result) {
        $order = mysqli_fetch_assoc($result);
    } else {
        echo "<div class='alert alert-danger'>Error fetching order details: " . mysqli_error($conn) . "</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger'></div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Order Status</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_style.css">
    <script src="admin_scripts.js" defer></script>
    <script src="login-register/other_js_files.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Update Order Status</h1>
        <form action="update_order_status.php" method="post">
            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
            <div class="mb-3">
                <label for="status" class="form-label">Order Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="processing" <?php if ($order['status'] == 'processing') echo 'selected'; ?>>Processing</option>
                    <option value="shipped" <?php if ($order['status'] == 'shipped') echo 'selected'; ?>>Shipped</option>
                    <option value="delivered" <?php if ($order['status'] == 'delivered') echo 'selected'; ?>>Delivered</option>
                </select>
            </div>
            <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
        </form>
    </div>
</body>
</html>
