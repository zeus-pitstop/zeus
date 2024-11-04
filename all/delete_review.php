<?php
session_start();
require_once '../admin/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reviewId = intval($_POST['review_id']);
    $userId = $_SESSION['user_id'];

    // Debugging: Check if the reviewId and userId are correctly received
    error_log("Review ID: $reviewId");
    error_log("User ID: $userId");

    // Verify if the review belongs to the logged-in user
    $query = "DELETE FROM feedback WHERE feedback_id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $reviewId, $userId);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        error_log("Error deleting review: " . $stmt->error);
        echo 'error';
    }

    $stmt->close();
    $conn->close();
}
?>
