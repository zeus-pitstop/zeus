<?php
session_start();
require_once '../admin/database.php'; // Include your database connection file

// Initialize variables
$login_success = false;
$error = ''; // Initialize the error variable

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute query to check user credentials
    $query = "SELECT id, password FROM site_users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $hashed_password);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Login successful
            $_SESSION['user_id'] = $user_id;
            $login_success = true;

            date_default_timezone_set('Asia/Kolkata');

            // Log the login action
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $action_performed = 'User Logged In';
            $action_date = date('Y-m-d H:i:s');
            $log_query = "INSERT INTO user_activity (user_id, action, action_date) VALUES (?, ?, ?)";
            $log_stmt = $conn->prepare($log_query);
            $log_stmt->bind_param("iss", $user_id, $action_performed, $action_date);
            $log_stmt->execute();
            $log_stmt->close();
        } else {
            $error = 'Invalid username or password.';
        }
    } else {
        $error = 'Invalid username or password.';
    }
    $stmt->close();

    // Redirect based on login success or show error message
    if ($login_success) {
        header('Location: ../home/index.php');
        exit();
    } else {
        echo "<script>
            alert('$error');
            window.location.href = 'login.php'; // Redirect back to login page
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Motorcycle Accessories Store</title>
    <link rel="stylesheet" href="../home/main.css">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="../image/logo.png" type="image/x-icon">
    <script src="loginValidation.js" defer></script>
    <script src="toggleForm.js" defer></script>
    <script src="../home/search.js"></script>
</head>
<body>
<?php include '../home/header.php'; ?>
<div class="auth-section">
    <div class="auth-container">
        <form id="loginForm" action="login.php" method="POST" onsubmit="return validateLoginForm()">
            <h2>Login</h2>
            <div class="input-group">
                <label for="login-username">Username</label>
                <input type="text" id="login-username" name="username" required>
            </div>
            <div class="input-group">
    <label for="login-password">Password</label>
    <div class="password-wrapper">
        <input type="password" id="login-password" name="password" required>
        <i class="fas fa-eye" id="togglePassword"></i>
    </div>
</div>
            <?php
            if ($error) {
                echo "<div class='error-message'>$error</div>";
            }
            ?>
            <button type="submit" class="auth-btn">Login</button>
        </form>
        <div class="create-account-link">
    <a href="forgot_password.php">Forgot Password?</a> <!-- New link -->
    <a href="create.php">Create an account</a>
</div>
    </div>
</div>
</body>
</html>
