<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
require_once "../database.php";

// Initialize variables
$bike = null;

// Fetch bike details based on ID
if (isset($_GET['id'])) {
    $bike_id = $_GET['id'];
    $stmt = mysqli_prepare($conn, "SELECT * FROM bikes WHERE bike_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $bike_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $bike = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    // Check if bike was found
    if (!$bike) {
        echo "<div class='alert alert-warning'>Bike not found.</div>";
        exit();
    }
}

// Update bike details
if (isset($_POST['edit_bike'])) {
    if ($bike === null) {
        echo "<div class='alert alert-warning'>No bike to update.</div>";
        exit();
    }

    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $cover_image = $_FILES['cover_image']['name'];
    $detail_image = $_FILES['detail_image']['name'];
    $video = $_FILES['video']['name'];

    // Handle cover image removal
    if (isset($_POST['remove_cover_image']) && $_POST['remove_cover_image'] === '1') {
        if (file_exists("../../image/" . $bike['cover_image'])) {
            unlink("../../image/" . $bike['cover_image']);
        }
        $cover_image = null; // Set to null to update in the database
    } else if (!$cover_image) {
        $cover_image = $bike['cover_image']; // Use existing cover image if no new one is uploaded
    } else {
        $cover_target = "../../image/" . basename($cover_image);
        if (!move_uploaded_file($_FILES['cover_image']['tmp_name'], $cover_target)) {
            echo "<div class='alert alert-warning'>Failed to move cover image.</div>";
        }
    }

    // Handle detail image removal
    if (isset($_POST['remove_detail_image']) && $_POST['remove_detail_image'] === '1') {
        if (file_exists("../../image/" . $bike['detail_image'])) {
            unlink("../../image/" . $bike['detail_image']);
        }
        $detail_image = null; // Set to null to update in the database
    } else if (!$detail_image) {
        $detail_image = $bike['detail_image']; // Use existing detail image if no new one is uploaded
    } else {
        $detail_target = "../../image/" . basename($detail_image);
        if (!move_uploaded_file($_FILES['detail_image']['tmp_name'], $detail_target)) {
            echo "<div class='alert alert-warning'>Failed to move detail image.</div>";
        }
    }

    // Handle video removal and upload
    $video_target_dir = "../../videos/";
    if (isset($_POST['remove_video']) && $_POST['remove_video'] === '1') {
        if (file_exists($video_target_dir . $bike['video'])) {
            unlink($video_target_dir . $bike['video']);
        }
        $video = null; // Set to null to update in the database
    } else if (!$video) {
        $video = $bike['video']; // Use existing video if no new one is uploaded
    } else {
        $video_target_file = $video_target_dir . basename($video);
        if (!move_uploaded_file($_FILES['video']['tmp_name'], $video_target_file)) {
            echo "<div class='alert alert-warning'>Failed to move video.</div>";
        }
    }

    // Prepare and execute the update SQL statement
    $stmt = mysqli_prepare($conn, "UPDATE bikes SET name = ?, category_id = ?, cover_image = ?, detail_image = ?, video = ? WHERE bike_id = ?");
    if (!$stmt) {
        echo "<div class='alert alert-danger'>Prepare failed: " . mysqli_error($conn) . "</div>";
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'sisssi', $name, $category_id, $cover_image, $detail_image, $video, $bike_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<div class='alert alert-success'>Bike updated successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating bike: " . mysqli_stmt_error($stmt) . "</div>";
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bike</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Bike</h1>
        <?php if ($bike): ?>
        <form action="edit_bike.php?id=<?php echo htmlspecialchars($bike_id); ?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Bike Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($bike['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <?php
                    $result = mysqli_query($conn, "SELECT * FROM categories");
                    while ($row = mysqli_fetch_assoc($result)) {
                        $selected = $row['category_id'] == $bike['category_id'] ? 'selected' : '';
                        echo "<option value='" . $row['category_id'] . "' $selected>" . $row['category_name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="cover_image" class="form-label">Cover Image</label>
                <input type="file" class="form-control" id="cover_image" name="cover_image">
                <?php if ($bike['cover_image']): ?>
                    <img src="../../image/<?php echo htmlspecialchars($bike['cover_image']); ?>" alt="Current Cover Image" class="img-thumbnail mt-2" style="max-width: 150px;">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remove_cover_image" value="1" id="removeCoverImage">
                        <label class="form-check-label" for="removeCoverImage">
                            Remove existing cover image
                        </label>
                    </div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="detail_image" class="form-label">Detail Image</label>
                <input type="file" class="form-control" id="detail_image" name="detail_image">
                <?php if ($bike['detail_image']): ?>
                    <img src="../../image/<?php echo htmlspecialchars($bike['detail_image']); ?>" alt="Current Detail Image" class="img-thumbnail mt-2" style="max-width: 150px;">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remove_detail_image" value="1" id="removeDetailImage">
                        <label class="form-check-label" for="removeDetailImage">
                            Remove existing detail image
                        </label>
                    </div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="video" class="form-label">Video</label>
                <input type="file" class="form-control" id="video" name="video">
                <?php if ($bike['video']): ?>
                    <video controls class="mt-2" style="max-width: 150px;">
                        <source src="../../video/<?php echo htmlspecialchars($bike['video']); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remove_video" value="1" id="removeVideo">
                        <label class="form-check-label" for="removeVideo">
                            Remove existing video
                        </label>
                    </div>
                <?php endif; ?>
            </div>
            <button type="submit" name="edit_bike" class="btn btn-primary">Update Bike</button>
        </form>
        <?php else: ?>
        <div class='alert alert-warning'>No bike details available.</div>
        <?php endif; ?>
    </div>
</body>
</html>
