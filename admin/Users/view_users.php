<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
require_once "../database.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_style.css">
    <script src="admin_scripts.js" defer></script>
    <script src="login-register/other_js_files.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>View Users</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query to select users from site_users table
                $result = mysqli_query($conn, "SELECT id, username, email FROM site_users");

                // Debugging: Check if the query is successful
                if (!$result) {
                    echo "<tr><td colspan='4'>Error: " . mysqli_error($conn) . "</td></tr>";
                }

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['username']}</td>
                            <td>{$row['email']}</td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
