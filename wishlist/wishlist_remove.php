<?php
session_start();
require_once '../admin/database.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../account/login.php"); // Redirect to login if not logged in
    exit();
}

if (isset($_POST['product_id']) && is_numeric($_POST['product_id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']); // Sanitize and convert to integer

    // Prepare the SQL query to remove the product from the wishlist
    $query = "DELETE FROM wishlist WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        // Handle preparation errors
        header("Location: wishlist.php?status=error&message=Database%20error");
        exit();
    }
    $stmt->bind_param("ii", $user_id, $product_id);

    if ($stmt->execute()) {
        // Redirect to the wishlist page with a success message
        header("Location: wishlist.php?status=success");
    } else {
        // Redirect to the wishlist page with an error message
        header("Location: wishlist.php?status=error&message=Failed%20to%20remove%20product");
    }

    $stmt->close();
} else {
    // Redirect to wishlist page if no valid product ID is provided
    header("Location: wishlist.php?status=error&message=Invalid%20product%20ID");
}

?>
