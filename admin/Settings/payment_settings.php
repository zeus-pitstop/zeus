<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
require_once "../database.php";

// Fetch payment settings
$sql = "SELECT payment_gateway FROM settings";
$result = mysqli_query($conn, $sql);

if ($result) {
    $settings = mysqli_fetch_assoc($result);
    
    if ($settings === null) {
        echo "<div class='alert alert-danger'>No payment settings found in the database.</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger'>Error fetching payment settings: " . mysqli_error($conn) . "</div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Settings</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="payment_settings.css">
    <script src="admin_scripts.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Payment Settings</h1>
        <form action="payment_settings.php" method="post">
            <div class="mb-3">
                <label for="payment_gateway" class="form-label">Payment Gateway</label>
                <input type="text" class="form-control" id="payment_gateway" name="payment_gateway" value="<?php echo htmlspecialchars($settings['payment_gateway'] ?? ''); ?>" required>
            </div>
            <button type="submit" name="update_payment_settings" class="btn btn-primary">Save Settings</button>
        </form>
    </div>
</body>
</html>
