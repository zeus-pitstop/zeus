<?php
// Start session
session_start();

// Include database connection
require_once '../admin/database.php';

// Variable to hold messages
$message = '';

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    echo 'No user logged in. Please log in to submit feedback.';
    exit(); // Stop further execution if no user is logged in
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = intval($_POST['product_id']); // Get product_id from POST request
    $userId = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0; // Ensure user_id exists
    $message = mysqli_real_escape_string($conn, trim($_POST['message']));
    $rating = intval($_POST['rating']);

    // Validation
    if (empty($message) || empty($rating)) {
        echo 'Both message and rating are required.';
    } elseif ($rating < 1 || $rating > 10) {
        echo 'Rating must be between 1 and 10.';
    } else {
        // Prepare the insert query for feedback
        $insertQuery = "INSERT INTO feedback (user_id, product_id, message, rating, feedback_date) VALUES (?, ?, ?, ?, NOW())";
        if ($stmt = $conn->prepare($insertQuery)) {
            $stmt->bind_param("iisi", $userId, $productId, $message, $rating);

            date_default_timezone_set('Asia/Kolkata');

            // Execute the statement
            if ($stmt->execute()) {
                // Log the user activity
                $action_performed = 'User Submitted Feedback for Product ID ' . $productId;
                $action_date = date('Y-m-d H:i:s');

                $log_query = "INSERT INTO user_activity (user_id, action, action_date) VALUES (?, ?, ?)";
                $log_stmt = $conn->prepare($log_query);
                $log_stmt->bind_param("iss", $userId, $action_performed, $action_date);
                $log_stmt->execute();
                $log_stmt->close();

                echo 'success'; // Return 'success' on successful insertion
            } else {
                echo 'Error: Could not submit feedback. Please try again.';
            }

            $stmt->close();
        } else {
            echo 'Error: Could not prepare the statement. Please try again.';
        }
    }
}

// Close the database connection
$conn->close();
?>
