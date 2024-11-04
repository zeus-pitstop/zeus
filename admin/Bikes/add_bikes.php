<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
require_once "../database.php";


if (isset($_POST['add_bike'])) {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $image = $_FILES['image']['name'];
    $user_image = $_FILES['user_image']['name'];
    $video = !empty($_FILES['video']['name']) ? $_FILES['video']['name'] : null; // Handle optional video

    // Verify that the category_id exists
    $category_check_stmt = mysqli_prepare($conn, "SELECT 1 FROM categories WHERE category_id = ?");
    mysqli_stmt_bind_param($category_check_stmt, 'i', $category_id);
    mysqli_stmt_execute($category_check_stmt);
    mysqli_stmt_store_result($category_check_stmt);

    if (mysqli_stmt_num_rows($category_check_stmt) === 0) {
        echo "<div class='alert alert-danger'>Invalid category selected.</div>";
        mysqli_stmt_close($category_check_stmt);
        exit();
    }

    mysqli_stmt_close($category_check_stmt);

    // Handle file uploads
    $target = "../../image/" . basename($image);
    $user_target = "../../image/" . basename($user_image);

    if ($video) {
        $video_target = "../../videos" . basename($video);
    }

    $stmt = mysqli_prepare($conn, "INSERT INTO bikes (name, category_id, cover_image, detail_image, video) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo "<div class='alert alert-danger'>Prepare failed: " . mysqli_error($conn) . "</div>";
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'sisss', $name, $category_id, $image, $user_image, $video);

    if (mysqli_stmt_execute($stmt)) {
        $image_moved = move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $user_image_moved = move_uploaded_file($_FILES['user_image']['tmp_name'], $user_target);
        $video_moved = $video ? move_uploaded_file($_FILES['video']['tmp_name'], $video_target) : true;

        if ($image_moved && $user_image_moved && $video_moved) {
            echo "<div class='alert alert-success'>Bike added successfully.</div>";
        } else {
            echo "<div class='alert alert-warning'>Bike added, but failed to move files.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Error adding bike: " . mysqli_stmt_error($stmt) . "</div>";
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Bike</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="container">
        <h1>Add New Bike</h1>
        <form action="add_bikes.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Bike Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <?php
                    $result = mysqli_query($conn, "SELECT * FROM categories");
                    if (!$result) {
                        echo "<option>Error fetching categories</option>";
                    }
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['category_id'] . "'>" . $row['category_name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Cover Image</label>
                <input type="file" class="form-control" id="image" name="image" required>
            </div>
            <div class="mb-3">
                <label for="user_image" class="form-label">Detail Image</label>
                <input type="file" class="form-control" id="user_image" name="user_image">
            </div>
            <div class="mb-3">
                <label for="video" class="form-label">Bike Video</label>
                <input type="file" class="form-control" id="video" name="video" accept="video/*">
            </div>
            <button type="submit" name="add_bike" class="btn btn-primary">Add Bike</button>
        </form>
    </div>
</body>
</html>
