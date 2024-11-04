<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
require_once "../database.php"; // Ensure this file defines $dbUser, $dbPassword, $hostName, and $dbName

if (isset($_POST['backup_database'])) {
    // Specify the directory where you want to save the backup
    $backup_directory = 'C:\\xampp\\htdocs\\zeus\\admin\\Backup\\'; // Use double backslashes and ensure it ends with a backslash
    if (!is_dir($backup_directory)) {
        mkdir($backup_directory, 0755, true); // Create the directory if it doesn't exist
    }

    // Create the backup file path
    $backup_file = $backup_directory . 'backup_' . date('YmdHis') . '.sql';

    // Command to create the backup using mysqldump
    $command = "C:\\xampp\\mysql\\bin\\mysqldump.exe --user={$dbUser} --password={$dbPassword} --host={$hostName} {$dbName} > \"$backup_file\"";

    // Execute the backup command
    system($command, $result);

    if ($result === 0) {
        echo "<div class='alert alert-success'>Database backup created successfully: <a href='Backup/" . basename($backup_file) . "' download>" . basename($backup_file) . "</a></div>";
    } else {
        echo "<div class='alert alert-danger'>Error creating database backup. Please check the database credentials and the path.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Backup</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_style.css">
    <script src="admin_scripts.js" defer></script>
    <script src="login-register/other_js_files.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Database Backup</h1>
        <form action="database_backup.php" method="post">
            <button type="submit" name="backup_database" class="btn btn-primary">Backup Database</button>
        </form>
    </div>
</body>
</html>
