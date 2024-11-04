<?php
require_once '../admin/database.php';
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include '../home/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $query = "SELECT id FROM site_users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id);
        $stmt->fetch();

        // Generate a unique token
        $token = bin2hex(random_bytes(50));

        // Insert the token into the password_resets table
        $reset_query = "INSERT INTO password_resets (user_id, token) VALUES (?, ?)";
        $reset_stmt = $conn->prepare($reset_query);
        $reset_stmt->bind_param("is", $user_id, $token);
        $reset_stmt->execute();

        // Send reset email using PHPMailer
        $reset_link = "https://yourwebsite.com/reset_password.php?token=" . $token;
        $subject = "Password Reset Request";
        $message = "Please click the following link to reset your password: " . $reset_link;

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;
            $mail->Username   = 'zeuspitstop24@gmail.com'; // Your Gmail address
            $mail->Password   = 'nsvc bbyn kvxa zcll';    // Your Gmail app password (not your usual password)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            //Recipients
            $mail->setFrom('no-reply@yourwebsite.com', 'Your Website');
            $mail->addAddress($email); // Add a recipient

            // Content
            $mail->isHTML(false); // Set email format to plain text
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();
            $success = "A password reset link has been sent to your email.";
        } catch (Exception $e) {
            $error = "Failed to send the reset link. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $error = "No account found with that email address.";
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
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../home/main.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="auth-section">
        <div class="auth-container">
            <form action="forgot_password.php" method="POST">
                <h2>Forgot Password</h2>
                <?php
                if ($error) {
                    echo "<div class='error-message'>$error</div>";
                }
                if ($success) {
                    echo "<div class='success-message'>$success</div>";
                }
                ?>
                <div class="input-group">
                    <label for="email">Enter your email address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <button type="submit" class="auth-btn">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>
