<?php
session_start();
require_once '../admin/database.php';

// Initialize the cart session if not already done
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if form was submitted with updated quantities
if (isset($_POST['quantity']) && is_array($_POST['quantity'])) {
    foreach ($_POST['quantity'] as $productId => $quantity) {
        $productId = intval($productId);
        $quantity = intval($quantity);

        if ($productId > 0) {
            if ($quantity > 0) {
                // Fetch product details to ensure valid product
                $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
                $stmt->bind_param("i", $productId);
                $stmt->execute();
                $product = $stmt->get_result()->fetch_assoc();
                $stmt->close();

                if ($product) {
                    // Update quantity in the session cart
                    $_SESSION['cart'][$productId] = [
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'quantity' => $quantity,
                    ];
                }
            } else {
                // Remove product if quantity is zero or less
                unset($_SESSION['cart'][$productId]);
            }
        }
    }
}

// Check if individual update was requested
if (isset($_POST['update_quantity']) && is_array($_POST['update_quantity'])) {
    foreach ($_POST['update_quantity'] as $productId => $action) {
        $productId = intval($productId);

        if ($productId > 0 && isset($_SESSION['cart'][$productId])) {
            // Fetch the updated quantity from session
            $quantity = $_SESSION['cart'][$productId]['quantity'];

            // Update the quantity in the database
            $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
            $stmt->bind_param("iii", $quantity, $_SESSION['user_id'], $productId);
            if ($stmt->execute()) {
                // Optionally log success
                // echo "Item updated successfully.";
            } else {
                // Log or echo the error for debugging
                echo "Error updating item in cart: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

// Redirect back to the cart page
header('Location: cart.php');
exit();
?>
