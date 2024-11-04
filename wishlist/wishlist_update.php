<?php
session_start();
require_once '../admin/database.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../account/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle updating quantity for a single product
if (isset($_POST['product_id']) && isset($_POST['quantity']) && is_numeric($_POST['product_id']) && is_numeric($_POST['quantity'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity > 0) {
        // Update the quantity in the wishlist
        $query = "UPDATE wishlist SET quantity = ? WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Database prepare error: " . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("iii", $quantity, $user_id, $product_id);
        if ($stmt->execute()) {
            // Successfully updated
            echo 'Quantity updated successfully';
        } else {
            // Failed to update
            echo 'Failed to update quantity';
        }
        $stmt->close();
    } else {
        // If quantity is less than or equal to 0, remove the product from the wishlist
        $query = "DELETE FROM wishlist WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $stmt->close();
        echo 'Product removed from wishlist due to zero quantity';
    }
} else {
    echo 'Invalid product ID or quantity';
}

// Redirect to wishlist page after updating
header('Location: wishlist.php');
exit();
?>
