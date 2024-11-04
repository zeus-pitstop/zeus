<?php
// Start session
session_start();

// Include database connection
require_once '../admin/database.php';

// Variable to hold error messages
$error = '';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['new-username']);
    $email = mysqli_real_escape_string($conn, $_POST['new-email']);
    $password = mysqli_real_escape_string($conn, $_POST['new-password']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirm-password']);

    // Server-side validation
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        // Check if the username or email already exists
        $query = "SELECT * FROM site_users WHERE username = '$username' OR email = '$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $error = "Username or Email already exists.";
        } else {
            // Hash the password before storing it
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user into the database
            $insertQuery = "INSERT INTO site_users (username, email, password, registration_date) VALUES ('$username', '$email', '$hashedPassword', NOW())";
            
            if (mysqli_query($conn, $insertQuery)) {
                // Success: Redirect to login page or show a success message
                header("Location: login.php");
                exit();
            } else {
                $error = "Error: Could not create account. Please try again.";
            }
        }
    }
}
?>

<!-- Output any errors -->
<?php if ($error): ?>
    <div class="error-message"><?php echo $error; ?></div>
<?php endif; ?>
