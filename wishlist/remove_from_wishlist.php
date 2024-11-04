<?php
session_start();
require_once '../admin/database.php'; // Include your database connection file

// Check if product_id and user_id are set
if (isset($_GET['product_id']) && isset($_SESSION['user_id'])) {
    $product_id = intval($_GET['product_id']); // Sanitize product ID
    $user_id = $_SESSION['user_id'];

    // Prepare and execute the query to remove product from wishlist
    $query = "DELETE FROM wishlist WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die("Database error: " . $conn->error);
    }
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $stmt->close();

    // Update session wishlist array
    if (isset($_SESSION['wishlist']) && ($key = array_search($product_id, $_SESSION['wishlist'])) !== false) {
        unset($_SESSION['wishlist'][$key]);
    }

    // Redirect back to wishlist page
    header("Location: wishlist.php");
    exit();
} else {
    // Handle case where product_id or user_id is not set
    echo "Error: Missing product ID or user ID.";
    exit();
}
?>
