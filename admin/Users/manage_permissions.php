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

// Sanitize the user_id
$user_id = intval($_GET['id']);

// Debugging: Check if $user_id is correctly set
if ($user_id <= 0) {
    echo "<div class='alert alert-danger'>Invalid user ID specified.</div>";
    exit();
}

if (isset($_POST['update_permissions'])) {
    $role = $_POST['role'];

    // Prepare the SQL statement
    $stmt = mysqli_prepare($conn, "UPDATE users SET role = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'si', $role, $user_id);
    if (mysqli_stmt_execute($stmt)) {
        echo "<div class='alert alert-success'>Permissions updated successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating permissions.</div>";
    }
    mysqli_stmt_close($stmt);
}

// Prepare the SQL statement to fetch user details
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    echo "<div class='alert alert-danger'>User not found.</div>";
    exit();
}
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Permissions</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_style.css">
    <script src="admin_scripts.js" defer></script>
    <script src="login-register/other_js_files.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Manage Permissions</h1>
        <form action="manage_permissions.php?id=<?php echo htmlspecialchars($user_id); ?>" method="post">
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                    <option value="editor" <?php if ($user['role'] == 'editor') echo 'selected'; ?>>Editor</option>
                    <option value="viewer" <?php if ($user['role'] == 'viewer') echo 'selected'; ?>>Viewer</option>
                </select>
            </div>
            <button type="submit" name="update_permissions" class="btn btn-primary">Update Permissions</button>
        </form>
    </div>
</body>
</html>
