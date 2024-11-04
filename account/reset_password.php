<?php
require_once '../admin/database.php';

$error = '';
$success = '';
$token = $_GET['token'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password === $confirm_password) {
        // Verify the token
        $query = "SELECT user_id FROM password_resets WHERE token = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id);
            $stmt->fetch();

            // Hash the new password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Update the user's password in the database
            $update_query = "UPDATE site_users SET password = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("si", $hashed_password, $user_id);
            $update_stmt->execute();

            // Delete the token to prevent reuse
            $delete_query = "DELETE FROM password_resets WHERE token = ?";
            $delete_stmt = $conn->prepare($delete_query);
            $delete_stmt->bind_param("s", $token);
            $delete_stmt->execute();

            $success = "Your password has been reset successfully. You can now <a href='login.php'>login</a>.";
        } else {
            $error = "Invalid or expired token.";
        }
    } else {
        $error = "Passwords do not match.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../home/main.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="auth-section">
        <div class="auth-container">
            <form action="reset_password.php?token=<?php echo $token; ?>" method="POST">
                <h2>Reset Password</h2>
                <?php
                if ($error) {
                    echo "<div class='error-message'>$error</div>";
                }
                if ($success) {
                    echo "<div class='success-message'>$success</div>";
                }
                ?>
                <div class="input-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="input-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="auth-btn">Reset Password</button>
            </form>
        </div>
    </div>
</body>
</html>
