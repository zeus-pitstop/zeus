<?php
session_start();
require_once '../admin/database.php'; // Include your database connection file

date_default_timezone_set('Asia/Kolkata');

// Log logout action
$user_id = $_SESSION['user_id'];
$ip_address = $_SERVER['REMOTE_ADDR'];
$action_performed = 'User Logged Out';
$action_date = date('Y-m-d H:i:s');

$query = "INSERT INTO user_activity (user_id, action, action_date) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("iss", $user_id, $action_performed, $action_date);
$stmt->execute();
$stmt->close();

session_destroy();
header('Location: login.php');
exit();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - Motorcycle Accessories Store</title>
    <link rel="stylesheet" href="../home/main.css"> <!-- Reuse the main CSS file -->
    <link rel="stylesheet" href="logout.css"> <!-- Add specific styles for logout page -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="../image/logo.png" type="image/x-icon">
    <script src="logout.js" defer></script>
    <script src="../home/search.js"></script>
</head>

<body>
    <header>
        <div class="header-container">
            <div class="logo-container">
                <a href="../home/index.php">
                    <img src="../image/logo.png" alt="Logo" class="logo">
                </a>
            </div>
            <div class="zeus">
                <a href="../home/index.php">
                    <img src="../image/zeus.png" alt="Zeus" class="alt-name">
                </a>
            </div>
            <div class="search-bar">
                <form action="../home/search.php" method="GET">
                    <input type="text" name="query" placeholder="Search for products..." aria-label="Search" required>
                    <button type="submit">Search</button>
                </form>
                <div class="search-results"></div>
            </div>
            <div class="icons">
                <a href="../wishlist/wishlist.php" class="wishlist" aria-label="Wishlist">
                    <span class="heart-symbol">&#10084;</span>
                    <div class="wishlist-text">Wishlist</div>
                </a>
                <a href="../cart/cart.php" class="cart" aria-label="Cart">
                    <span class="cart-text">Cart (<?php echo isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0; ?>)</span>
                </a>
                <div class="profile-dropdown">
                    <button class="profile-btn" aria-label="Profile Menu">
                        <i class="fas fa-user-circle"></i> <!-- Font Awesome profile icon -->
                    </button>
                    <div class="profile-content">
                        <a href="../admin/index.php">Admin Login</a>
                        <a href="login.php">Login</a>
                        <a href="../home/profile.php">Profile</a>
                        <a href="logout.php" onclick="logout()">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="logout-message">
        <div class="message-container">
            <div id="message" class="message"></div>
            <div id="spinner" class="spinner" style="display:none;"></div>
        </div>
    </div>

    <footer>
        <?php include '../home/footer.php'; ?>
    </footer>
</body>

</html>