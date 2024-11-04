<?php
session_start();
require_once '../admin/database.php';

// Initialize count to 0
$count = 0;

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Prepare and execute the query to get the cart item count
    $query = "SELECT SUM(quantity) as count FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Set the count to the result or 0 if null
    $count = $row['count'] ?? 0;
}

// Return the count as JSON
echo json_encode(['count' => $count]);
?>
