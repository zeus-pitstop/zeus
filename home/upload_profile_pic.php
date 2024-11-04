<?php
session_start();
require_once '../admin/database.php'; // Adjust the path as necessary

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../account/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $fileInfo = pathinfo($_FILES['profile_pic']['name']);
    $fileExt = strtolower($fileInfo['extension']);

    if (in_array($fileExt, $allowed)) {
        // Define the target path to store the image
        $targetDir = "../profile_picz/";
        $newFileName = $user_id . "_profile." . $fileExt;  // Unique file name based on user ID
        $targetFile = $targetDir . $newFileName;

        // Move uploaded file to the target directory
        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetFile)) {
            // Update the profile picture path in the database
            $query = "UPDATE site_users SET profile_pic = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $newFileName, $user_id);
            $stmt->execute();
            $stmt->close();

            // Redirect back to profile page
            header('Location: profile.php');
            exit();
        } else {
            echo "Failed to upload image.";
        }
    } else {
        echo "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
    }
} else {
    echo "Error uploading file.";
}
?>
