<?php
session_start();
require_once '../admin/database.php'; // Include your database connection file

// Log the page view
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$ip_address = $_SERVER['REMOTE_ADDR'];
$accessed_page = $_SERVER['REQUEST_URI'];
$action_performed = 'Page Access';

$query = "INSERT INTO site_user_access_logs (user_id, ip_address, accessed_page, action_performed) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("isss", $user_id, $ip_address, $accessed_page, $action_performed);
$stmt->execute();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motorcycle Accessories Store</title>
    <link rel="stylesheet" href="../home/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="../image/logo.png" type="image/x-icon">

<body>
    <?php include '../home/header.php'; ?>
    <footer>
        <div class="container">
            <p>&copy; 2024 Motorcycle Accessories Store. All rights reserved.</p>
        </div>
    </footer>

    <script src="../account/logout.js" defer></script>
    <script src="../account/loginValidation.js" defer></script>
    <script src="../account/createAccountValidation.js" defer></script>
    <script src="../account/toggleForm.js" defer></script>
    <script src="../home/search.js"></script>
</body>

</html>