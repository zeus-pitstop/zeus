<?php
session_start();
require_once '../admin/database.php'; // Include your database connection file

// Check if productId is provided in the URL
if (isset($_GET['productId'])) {
    $productId = intval($_GET['productId']);
    
    // Log the product ID for debugging
    error_log("Attempting to remove product ID: " . $productId);

    // Check if the user is logged in
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        // Log user ID for debugging
        error_log("User ID: " . $userId);

        // Prepare query to remove the item from the cart in the database
        $query = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $userId, $productId);
        
        if ($stmt->execute()) {
            error_log("Product removed from database cart successfully.");
        } else {
            error_log("Error removing product from database cart: " . $stmt->error);
        }
        $stmt->close();
        
    } else {
        // If the user is not logged in, remove the item from the session cart
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
            error_log("Product removed from session cart successfully.");
        } else {
            error_log("Product not found in session cart.");
        }
        
        // No longer recalculating the session cart count for guest users
    }
}

// Return a success message (or an empty response if you prefer)
echo json_encode(['success' => true]);
exit();
?>
