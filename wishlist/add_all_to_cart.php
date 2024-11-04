<?php
session_start();
require_once '../admin/database.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../account/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Prepare query to fetch all products in the wishlist
$query = "SELECT product_id, quantity FROM wishlist WHERE user_id = ?";
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Loop through each wishlist item and add to cart
while ($row = $result->fetch_assoc()) {
    $product_id = $row['product_id'];
    $quantity = $row['quantity'];

    // Check if the product is already in the cart
    $check_query = "SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?";
    $check_stmt = $conn->prepare($check_query);
    if ($check_stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $check_stmt->bind_param("ii", $user_id, $product_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Product is already in the cart, update the quantity
        $existing_quantity = $check_result->fetch_assoc()['quantity'];
        $new_quantity = $existing_quantity + $quantity;

        $update_query = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
        $update_stmt = $conn->prepare($update_query);
        if ($update_stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        $update_stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
        $update_stmt->execute();
        $update_stmt->close();
    } else {
        // Product is not in the cart, insert it
        $insert_query = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        if ($insert_stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        $insert_stmt->bind_param("iii", $user_id, $product_id, $quantity);
        $insert_stmt->execute();
        $insert_stmt->close();
    }
    $check_stmt->close(); // Close the statement for checking cart
}

// Clear the wishlist after adding all items to the cart
$delete_query = "DELETE FROM wishlist WHERE user_id = ?";
$delete_stmt = $conn->prepare($delete_query);
if ($delete_stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$delete_stmt->bind_param("i", $user_id);
$delete_stmt->execute();
$delete_stmt->close();

header('Location: cart.php');
exit();
?>
