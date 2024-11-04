<?php
session_start();
require_once '../admin/database.php'; // Adjust the path if necessary

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if new passwords match
    if ($new_password !== $confirm_password) {
        echo 'New passwords do not match.';
        exit();
    }

    // Fetch the current hashed password from the database
    $query = "SELECT password FROM site_users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    $stmt->close();

    // Verify current password
    if (!password_verify($current_password, $hashed_password)) {
        echo 'Current password is incorrect.';
        exit();
    }

    // Hash the new password and update in the database
    $new_hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
    $query_update = "UPDATE site_users SET password = ? WHERE id = ?";
    $stmt_update = $conn->prepare($query_update);
    $stmt_update->bind_param("si", $new_hashed_password, $user_id);
    $stmt_update->execute();
    $stmt_update->close();

    echo 'Password changed successfully.';
}
$conn->close();
?>
