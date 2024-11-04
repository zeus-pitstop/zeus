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

// Fetch bikes from the database
$bikes_query = "SELECT * FROM bikes";
$bikes_result = $conn->query($bikes_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Motorcycle Accessories Store</title>
    <link rel="stylesheet" href="../home/main.css">
    <link rel="stylesheet" href="bikes.css"> <!-- Link to your custom CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="../image/logo.png" type="image/x-icon">
    <script src="../account/toggleform.js" defer></script>
    <script src="../home/search.js"></script>
</head>

<body>
    <?php include '../home/header.php'; ?>
    <main>
        <section class="products">
            <div class="container">
                <h1>Our Bikes</h1>
                <p>Explore our range of bikes. Click on a product for more details.</p>
                <div class="product-list">
                    <?php
                    if ($bikes_result->num_rows > 0) {
                        while ($bike = $bikes_result->fetch_assoc()) {
                            echo '<div class="product-item">';
                            echo '<a href="../all/bike_detail.php?id=' . htmlspecialchars($bike['bike_id']) . '">';
                            echo '<img src="../image/' . htmlspecialchars($bike['cover_image']) . '" alt="' . htmlspecialchars($bike['name']) . '" class="product-image">';
                            echo '<h2 class="product-title">' . htmlspecialchars($bike['name']) . '</h2>';
                            echo '</a>'; // Make entire block clickable
                            echo '<p class="product-description">' . htmlspecialchars($bike['description']) . '</p>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No bikes found.</p>';
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <?php include '../home/footer.php'; ?>
    </footer>
</body>

</html>