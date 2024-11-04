<?php
session_start();
require_once '../admin/database.php'; // Include your database connection file

date_default_timezone_set('Asia/Kolkata');

// Log the page view
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if ($user_id !== null) {
    $action_performed = 'Account Created';
    $action_date = date('Y-m-d H:i:s');

    $query = "INSERT INTO user_activity (user_id, action, action_date) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $user_id, $action_performed, $action_date);
    $stmt->execute();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Motorcycle Accessories Store</title>
    <link rel="stylesheet" href="../home/main.css">
    <link rel="stylesheet" href="create.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="../image/logo.png" type="image/x-icon">
</head>
<body>
    <?php include '../home/header.php'; ?>
    <main>
        <section class="auth-section">
            <div class="auth-container">
                <div class="form-container">
                    <h2>Create a New Account</h2>
                    <form id="createAccountForm" action="register.php" method="POST" onsubmit="return validateCreateAccountForm()">
                        <div class="input-group">
                            <label for="new-username">Username</label>
                            <input type="text" id="new-username" name="new-username" required>
                        </div>
                        <div class="input-group">
                            <label for="new-email">Email</label>
                            <input type="email" id="new-email" name="new-email" required>
                        </div>
                        <div class="input-group">
                            <label for="new-password">Password</label>
                            <input type="password" id="new-password" name="new-password" required>
                        </div>
                        <div class="input-group">
                            <label for="confirm-password">Confirm Password</label>
                            <input type="password" id="confirm-password" name="confirm-password" required>
                        </div>
                        <button type="submit" class="auth-btn">Create Account</button>
                        <p class="error-message" id="createAccountErrorMessage"></p>
                    </form>
                    <p class="create-account-link">Already have an account? <a href="login.php">Login here</a></p>
                </div>
            </div>
        </section>
    </main>
    <script src="../home/search.js"></script>
    <script src="createAccountValidation.js"></script>
</body>
</html>
