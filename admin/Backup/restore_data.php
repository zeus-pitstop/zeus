<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
require_once "../database.php";

if (isset($_POST['restore_data']) && isset($_FILES['backup_file'])) {
    $backup_file = $_FILES['backup_file']['tmp_name'];
    if (move_uploaded_file($_FILES['backup_file']['tmp_name'], 'uploads/' . $_FILES['backup_file']['name'])) {
        $command = "mysql --user={$dbUser} --password={$dbPassword} --host={$hostName} {$dbName} < uploads/" . $_FILES['backup_file']['name'];
        
        system($command, $result);

        if ($result === 0) {
            echo "<div class='alert alert-success'>Database restored successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error restoring database.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Failed to upload backup file.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restore Data</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_style.css">
    <script src="admin_scripts.js" defer></script>
    <script src="login-register/other_js_files.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Restore Data</h1>
        <form action="restore_data.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="backup_file" class="form-label">Select Backup File</label>
                <input type="file" class="form-control" id="backup_file" name="backup_file" required>
            </div>
            <button type="submit" name="restore_data" class="btn btn-primary">Restore Data</button>
        </form>
    </div>
</body>
</html>
