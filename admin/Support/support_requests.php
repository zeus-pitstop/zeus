<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
require_once "../database.php";

if (isset($_POST['resolve_request'])) {
    $request_id = $_POST['request_id'];
    $sql = "UPDATE support_requests SET status = 'Resolved' WHERE request_id = $request_id";
    if (mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Support request resolved.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error resolving support request.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Requests</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_style.css">
    <script src="admin_scripts.js" defer></script>
    <script src="login-register/other_js_files.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Support Requests</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Customer</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($conn, "SELECT * FROM support_requests");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['request_id']}</td>
                            <td>{$row['customer_name']}</td>
                            <td>{$row['subject']}</td>
                            <td>{$row['message']}</td>
                            <td>{$row['status']}</td>
                            <td>
                                <form action='support_requests.php' method='post'>
                                    <input type='hidden' name='request_id' value='{$row['request_id']}'>
                                    <button type='submit' name='resolve_request' class='btn btn-success'>Resolve</button>
                                </form>
                            </td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
