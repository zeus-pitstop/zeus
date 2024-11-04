<?php
session_start();
require_once '../admin/database.php'; // Adjust the path if necessary

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../account/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Initialize variables
$username = '';
$email = '';
$profile_pic = '';

// Fetch existing profile data
$query = "SELECT username, email, profile_pic FROM site_users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $profile_pic);
$stmt->fetch();
$stmt->close();

// Remove profile picture logic
if (isset($_POST['remove_profile_pic'])) {
    if ($profile_pic && file_exists('../profile_picz/' . $profile_pic)) {
        unlink('../profile_picz/' . $profile_pic);
    }
    $profile_pic = ''; // Reset profile picture to empty
    $query_update = "UPDATE site_users SET profile_pic = '' WHERE id = ?";
    $stmt_update = $conn->prepare($query_update);
    $stmt_update->bind_param("i", $user_id);
    $stmt_update->execute();
    $stmt_update->close();
    header('Location: profile_edit.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['remove_profile_pic'])) {
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];

    // Handle pre-added image selection
    if (isset($_POST['pre_added_image'])) {
        $new_profile_pic = $_POST['pre_added_image'];
    } else {
        // Handle profile picture upload
        $new_profile_pic = $profile_pic; // Keep existing picture if no new picture is uploaded
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['profile_pic']['tmp_name'];
            $file_name = basename($_FILES['profile_pic']['name']);
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            // Validate file extension
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($file_ext, $allowed_ext)) {
                $new_profile_pic = uniqid() . '.' . $file_ext;
                $upload_path = '../profile_picz/' . $new_profile_pic;
                move_uploaded_file($file_tmp, $upload_path);

                // Remove old profile picture if it exists
                if ($profile_pic && file_exists('../profile_picz/' . $profile_pic)) {
                    unlink('../profile_picz/' . $profile_pic);
                }
            }
        }
    }

    // Update user data
    $query_update = "UPDATE site_users SET username = ?, email = ?, profile_pic = ? WHERE id = ?";
    $stmt_update = $conn->prepare($query_update);
    $stmt_update->bind_param("sssi", $new_username, $new_email, $new_profile_pic, $user_id);
    $stmt_update->execute();
    $stmt_update->close();

    // Redirect to profile page
    header('Location: profile.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="profile_edit.css">
    <link rel="stylesheet" href="profile_edit.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="profile-section">
        <div class="profile-container">
            <h2>Edit Profile</h2>
            <form id="profile-form" action="profile_edit.php" method="post" enctype="multipart/form-data">
                <div class="profile-picture">
                    <img id="profile-pic" src="<?php echo htmlspecialchars($profile_pic ? '../profile_picz/' . $profile_pic : '../image/default-profile.png'); ?>" alt="Profile Picture">
                    <input type="file" id="upload-pic" name="profile_pic" accept="image/*" style="display: none;">
                    <button type="button" id="add-pic-btn" class="btn add-btn">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button type="submit" name="remove_profile_pic" class="btn remove-btn">Remove Profile Picture</button>
                    <div class="pre-added-images">
                        <h3>Select from Pre-added Images:</h3>
                        <div class="image-grid">
                            <?php
                            $pre_added_images = ['p1.png', 'p2.png', 'p3.png']; // Add your pre-added images here
                            foreach ($pre_added_images as $image) {
                                echo '<div class="image-option">';
                                echo '<img src="../profile_picz/' . $image . '" alt="Pre-added Image" class="pre-added-img" onclick="selectPreAddedImage(\'' . $image . '\')">';
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="profile-details">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                <div class="profile-actions">
                    <button type="submit" class="btn">Save Changes</button>
                    <button type="button" id="change-password-btn" class="btn change-password-btn">Change Password</button>
                </div>
            </form>
            <div id="change-password-modal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" id="close-modal">&times;</span>
                    <h3>Change Password</h3>
                    <form id="change-password-form" action="../account/change_password.php" method="post">
                        <label for="current_password">Current Password:</label>
                        <input type="password" id="current_password" name="current_password" required>

                        <label for="new_password">New Password:</label>
                        <input type="password" id="new_password" name="new_password" required>

                        <label for="confirm_password">Confirm New Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>

                        <button type="submit" class="btn">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('add-pic-btn').addEventListener('click', function() {
            document.getElementById('upload-pic').click();
        });

        document.getElementById('upload-pic').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const formData = new FormData();
                formData.append('profile_pic', file);

                fetch('upload_profile_pic.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('profile-pic').src = 'profile_picz/' + data.file_name;
                        } else {
                            alert('Failed to upload image.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred.');
                    });
            }
        });

        function selectPreAddedImage(imageName) {
            // Set the selected image as the profile picture
            document.getElementById('profile-pic').src = '../profile_picz/' + imageName;

            // Set a hidden input field to send the selected pre-added image name
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'pre_added_image';
            hiddenInput.value = imageName;
            document.getElementById('profile-form').appendChild(hiddenInput);
        }

        document.getElementById('change-password-btn').addEventListener('click', function() {
            document.getElementById('change-password-modal').style.display = 'block';
        });

        document.getElementById('close-modal').addEventListener('click', function() {
            document.getElementById('change-password-modal').style.display = 'none';
        });

        window.onclick = function(event) {
            if (event.target == document.getElementById('change-password-modal')) {
                document.getElementById('change-password-modal').style.display = 'none';
            }
        };
    </script>
</body>

</html>