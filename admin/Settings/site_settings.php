<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
require_once "../database.php";

// Fetch site settings
$sql = "SELECT site_name, site_description, contact_email, theme_color FROM settings";
$result = mysqli_query($conn, $sql);

if ($result) {
    $settings = mysqli_fetch_assoc($result);
    
    if ($settings === null) {
        echo "<div class='alert alert-danger'>No settings found in the database.</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger'>Error fetching site settings: " . mysqli_error($conn) . "</div>";
    exit();
}

// Update settings if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_settings'])) {
    $site_name = mysqli_real_escape_string($conn, $_POST['site_name']);
    $site_description = mysqli_real_escape_string($conn, $_POST['site_description']);
    $contact_email = mysqli_real_escape_string($conn, $_POST['contact_email']);
    $theme_color = mysqli_real_escape_string($conn, $_POST['theme_color']);
    
    $update_sql = "UPDATE settings SET 
                    site_name = '$site_name',
                    site_description = '$site_description',
                    contact_email = '$contact_email',
                    theme_color = '$theme_color'";
                    
    if (mysqli_query($conn, $update_sql)) {
        echo "<div class='alert alert-success'>Settings updated successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating settings: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Settings</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="site_settings.css">
    <script src="admin_scripts.js" defer></script>
</head>
<body>
    <div class="container mt-4">
        <h1>Site Settings</h1>
        <form action="site_settings.php" method="post">
            <div class="mb-3">
                <label for="site_name" class="form-label">Site Name</label>
                <input type="text" class="form-control" id="site_name" name="site_name" value="<?php echo htmlspecialchars($settings['site_name'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="site_description" class="form-label">Site Description</label>
                <textarea class="form-control" id="site_description" name="site_description" rows="3"><?php echo htmlspecialchars($settings['site_description'] ?? ''); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="contact_email" class="form-label">Contact Email</label>
                <input type="email" class="form-control" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="theme_color" class="form-label">Theme Color (Hex Code)</label>
                <input type="text" class="form-control" id="theme_color" name="theme_color" value="<?php echo htmlspecialchars($settings['theme_color'] ?? ''); ?>" required>
            </div>
            <button type="submit" name="update_settings" class="btn btn-primary">Save Settings</button>
        </form>
    </div>
</body>
</html>
