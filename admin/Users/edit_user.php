<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
require_once "../database.php";

// Check if 'id' is present in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='alert alert-danger'>No user ID specified.</div>";
    exit();
}

$user_id = $_GET['id'];

if (isset($_POST['update_user'])) {
    $username = $_POST['username']; // Updated variable name
    $email = $_POST['email'];
    $status = $_POST['status'];

    // Update query for the site_users table
    $sql = "UPDATE site_users SET username = '$username', email = '$email', status = '$status' WHERE id = '$user_id'";
    if (mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>User updated successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating user: " . mysqli_error($conn) . "</div>";
    }
}

// Fetch the user details from site_users table
$result = mysqli_query($conn, "SELECT * FROM site_users WHERE id = '$user_id'");
if (mysqli_num_rows($result) == 0) {
    echo "<div class='alert alert-danger'>User not found.</div>";
    exit();
}
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_style.css">
    <script src="admin_scripts.js" defer></script>
    <script src="login-register/other_js_files.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Edit User</h1>
        <form action="edit_user.php?id=<?php echo htmlspecialchars($user_id); ?>" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label> <!-- Updated label -->
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required> <!-- Updated input name -->
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <button type="submit" name="update_user" class="btn btn-primary">Update User</button>
        </form>
    </div>
</body>
</html>
