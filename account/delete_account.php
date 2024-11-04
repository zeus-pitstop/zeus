<?php
session_start();
require_once '../admin/database.php'; // Adjust the path if necessary

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle account deletion when the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Start transaction
    $conn->begin_transaction();

    try {
        // Log account deletion action in user_activity
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $action_performed = 'User Account Deletion';
        $action_date = date('Y-m-d H:i:s');

        $query_activity = "INSERT INTO user_activity (user_id, action, action_date) VALUES (?, ?, ?)";
        $stmt_activity = $conn->prepare($query_activity);
        $stmt_activity->bind_param("iss", $user_id, $action_performed, $action_date);
        $stmt_activity->execute();
        $stmt_activity->close();

        // Delete the user account
        $query_user = "DELETE FROM site_users WHERE id = ?";
        $stmt_user = $conn->prepare($query_user);
        $stmt_user->bind_param("i", $user_id);
        $stmt_user->execute();
        $stmt_user->close();

        // Commit transaction
        $conn->commit();

        // End the session and redirect to goodbye page
        session_destroy();
        header('Location: ../home/goodbye.php');
        exit();

    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account</title>
    <link rel="stylesheet" href="../home/main.css">
    <link rel="stylesheet" href="delete_account.css">
</head>
<body>
    <div class="profile-section">
        <div class="profile-container">
            <h2>Delete Account</h2>
            <p>Are you sure you want to delete your account? This action cannot be undone.</p>
            <form action="delete_account.php" method="post">
                <button type="submit" class="btn delete-btn">Delete Account</button>
                <a href="../home/profile.php" class="btn cancel-btn">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>
