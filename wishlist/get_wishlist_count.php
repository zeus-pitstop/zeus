<?php
session_start();
require_once '../admin/database.php'; // Include your database connection file

// Prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // User is not logged in; return 0 or an error message
    echo json_encode(["error" => "User not logged in"]);
    exit();
}

$user_id = $_SESSION['user_id'];

// Prepare and execute the query to count items in the user's wishlist
$query = "SELECT COUNT(*) AS wishlist_count FROM wishlist WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

// Return the count as JSON
echo json_encode(["wishlist_count" => $data['wishlist_count']]);
?>
