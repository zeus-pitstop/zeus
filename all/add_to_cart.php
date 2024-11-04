<?php
session_start();
require_once "../admin/database.php"; // Connect to the database

header('Content-Type: application/json'); // Ensure that only JSON is returned

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit();
}

// Process POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

    // Debugging: Log the received values
    error_log("Product ID: " . $productId);
    error_log("Quantity: " . $quantity);

    // Ensure the product ID and quantity are valid
    if ($productId && $quantity > 0) {
        $userId = $_SESSION['user_id'];

        // Check if product exists in the database
        $stmt = $conn->prepare("SELECT stock, name FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            $productName = $product['name'];

            if ($product['stock'] == 0) {
                echo json_encode(['status' => 'error', 'message' => 'Product is out of stock.']);
                exit();
            }

            if ($quantity > $product['stock']) {
                echo json_encode(['status' => 'error', 'message' => 'Requested quantity exceeds available stock.']);
                exit();
            }

            // Begin Transaction
            $conn->begin_transaction();

            try {
                // Check if product is already in user's cart
                $checkStmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
                $checkStmt->bind_param("ii", $userId, $productId);
                $checkStmt->execute();
                $checkResult = $checkStmt->get_result();

                if ($checkResult->num_rows > 0) {
                    // Product already in cart, update the quantity
                    $existingCart = $checkResult->fetch_assoc();
                    $newQuantity = $existingCart['quantity'] + $quantity;

                    // Ensure the new quantity does not exceed stock
                    if ($newQuantity > $product['stock']) {
                        echo json_encode(['status' => 'error', 'message' => 'Total quantity in cart exceeds available stock.']);
                        $conn->rollback();
                        exit();
                    }

                    $updateStmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
                    $updateStmt->bind_param("iii", $newQuantity, $userId, $productId);
                    $updateStmt->execute();

                    if ($updateStmt->affected_rows === 0) {
                        throw new Exception("Failed to update cart quantity.");
                    }

                    $updateStmt->close();
                } else {
                    // Product not in cart, insert new record
                    $insertStmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, added_on) VALUES (?, ?, ?, NOW())");
                    $insertStmt->bind_param("iii", $userId, $productId, $quantity);
                    $insertStmt->execute();

                    if ($insertStmt->affected_rows === 0) {
                        throw new Exception("Failed to add product to cart.");
                    }

                    $insertStmt->close();
                }

                // Log the user activity
                $action_performed = 'User added Product ID ' . $productId . ' (' . $productName . ') to Cart';
                $action_date = date('Y-m-d H:i:s');
                $log_query = "INSERT INTO user_activity (user_id, action, action_date) VALUES (?, ?, ?)";
                $log_stmt = $conn->prepare($log_query);
                $log_stmt->bind_param("iss", $userId, $action_performed, $action_date);
                $log_stmt->execute();

                if ($log_stmt->affected_rows === 0) {
                    throw new Exception("Failed to log user activity.");
                }

                $log_stmt->close();

                // Commit Transaction
                $conn->commit();

                // Send success response
                echo json_encode(['status' => 'success', 'message' => 'Product added to cart successfully.']);
            } catch (Exception $e) {
                // Rollback on error
                $conn->rollback();
                error_log("Transaction failed: " . $e->getMessage());
                echo json_encode(['status' => 'error', 'message' => 'Failed to add product to cart. Please try again.']);
            }
        } else {
            // Product not found
            echo json_encode(['status' => 'error', 'message' => 'Product not found.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid quantity or product ID.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
