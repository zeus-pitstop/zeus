<?php
require_once '../admin/database.php'; // Database connection
session_start(); // Ensure session is started

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the 'Move to Cart' button is pressed
if (isset($_POST['move_to_cart'])) {
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id']; // Assuming the user is logged in
        $productId = $_POST['product_id'];

        // Check stock availability
        $stockCheck = "SELECT stock FROM products WHERE product_id = ?";
        $stmtStock = $conn->prepare($stockCheck);
        $stmtStock->bind_param("i", $productId);
        $stmtStock->execute();
        $resultStock = $stmtStock->get_result();

        if ($resultStock->num_rows > 0) {
            $rowStock = $resultStock->fetch_assoc();
            $availableStock = $rowStock['stock'];

            if ($availableStock > 0) {
                // Insert the product into the cart
                $cartInsert = "INSERT INTO cart (user_id, product_id, quantity) 
                               VALUES (?, ?, 1) 
                               ON DUPLICATE KEY UPDATE quantity = quantity + 1";
                $stmtCart = $conn->prepare($cartInsert);
                $stmtCart->bind_param("ii", $userId, $productId);
                
                if ($stmtCart->execute()) {
                    echo "<script>alert('Product successfully moved to cart!');</script>";
                } else {
                    echo "<script>alert('Failed to execute query: " . $stmtCart->error . "');</script>";
                }
            } else {
                echo "<script>alert('Sorry, this product is out of stock.');</script>";
            }
        } else {
            echo "<script>alert('Product not found.');</script>";
        }
    } else {
        echo "<script>alert('User not logged in.');</script>";
    }
    exit; // Prevent further output
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="search.css"> <!-- Link to the CSS file -->
</head>
<body>
    <?php include 'header.php'; ?>

    <?php
    // Check if 'query' is set, otherwise provide a fallback
    $query = isset($_GET['query']) ? mysqli_real_escape_string($conn, $_GET['query']) : '';

    // If there's a query, perform the search
    if ($query) {
        $sql = "SELECT * FROM products WHERE name LIKE '%$query%' OR description LIKE '%$query%'"; // Enhanced to search in description too
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<div class='search-results'>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='product-item'>";
                echo "<div class='product-image-container'>";
                echo "<img src='../image/" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "' class='product-image'>";
                echo "</div>";
                echo "<h2>" . htmlspecialchars($row['name']) . "</h2>";
                echo "<p>₹" . htmlspecialchars($row['price']) . "</p>";
                echo "<a href='../all/product_detail.php?id=" . htmlspecialchars($row['product_id']) . "' class='btn add-to-cart'>View Product</a>";
                echo "<form method='post' action='search.php' class='move-to-cart-form'>";
                echo "<input type='hidden' name='product_id' value='" . htmlspecialchars($row['product_id']) . "'>";
                echo "<button type='submit' name='move_to_cart' class='move-to-cart-btn'>Move to Cart</button>";
                echo "</form>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p class='no-results'>No products found matching your search.</p>";
        }
    } else {
        echo "<p class='no-results'>Please enter a search query.</p>";
    }
    ?>
</body>
</html>
