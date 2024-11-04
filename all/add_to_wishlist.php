<?php
session_start();
require_once '../admin/database.php'; // Your database connection file

header('Content-Type: application/json'); // Ensure that only JSON is returned

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
        $user_id = $_SESSION['user_id'];
        $product_id = intval($_POST['product_id']);

        // Check if product is already in wishlist
        $check_query = "SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("ii", $user_id, $product_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            // Product is already in wishlist
            echo json_encode(["status" => "error", "message" => "Product already in wishlist"]);
        } else {
            // Add the product to the wishlist
            $query = "INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $user_id, $product_id);

            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Product added to wishlist"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to add product"]);
            }

            $stmt->close();
        }

        $check_stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Missing product ID"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
}
?>
