<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
require_once "../database.php";

// Fetch shipping settings
$sql = "SELECT shipping_method FROM settings";
$result = mysqli_query($conn, $sql);

if ($result) {
    $settings = mysqli_fetch_assoc($result);

    if ($settings === null) {
        echo "<div class='alert alert-danger'>No shipping settings found in the database.</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger'>Error fetching shipping settings: " . mysqli_error($conn) . "</div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Settings</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="shipping_settings.css">
    <script src="admin_scripts.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Shipping Settings</h1>
        <form action="shipping_settings.php" method="post">
            <div class="mb-3">
                <label for="shipping_method" class="form-label">Shipping Method</label>
                <input type="text" class="form-control" id="shipping_method" name="shipping_method" value="<?php echo htmlspecialchars($settings['shipping_method'] ?? ''); ?>" required>
            </div>
            <button type="submit" name="update_shipping_settings" class="btn btn-primary">Save Settings</button>
        </form>
    </div>
</body>
</html>
