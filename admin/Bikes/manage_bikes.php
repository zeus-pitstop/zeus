<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
require_once "../database.php";

// Handle bike deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Fetch the current images before deleting
    $stmt = mysqli_prepare($conn, "SELECT cover_image, detail_image FROM bikes WHERE bike_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $delete_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $bike = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($bike) {
        $cover_image_path = "../../image/" . $bike['cover_image'];
        $detail_image_path = "../../image/" . $bike['detail_image'];

        // Delete bike record
        $stmt = mysqli_prepare($conn, "DELETE FROM bikes WHERE bike_id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $delete_id);
        if (mysqli_stmt_execute($stmt)) {
            // Remove images if they exist
            if (file_exists($cover_image_path)) {
                unlink($cover_image_path);
            }
            if (file_exists($detail_image_path)) {
                unlink($detail_image_path);
            }
            echo "<div class='alert alert-success'>Bike deleted successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error deleting bike: " . mysqli_stmt_error($stmt) . "</div>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<div class='alert alert-warning'>Bike not found.</div>";
    }
}

// Fetch all bikes
$stmt = mysqli_prepare($conn, "SELECT bike_id, name, cover_image, detail_image FROM bikes");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$bikes = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bikes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="container">
        <h1>Manage Bikes</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Cover Image</th>
                    <th>Detail Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($bikes): ?>
                    <?php foreach ($bikes as $bike): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($bike['name']); ?></td>
                            <td>
                                <img src="../../image/<?php echo htmlspecialchars($bike['cover_image']); ?>" alt="Cover Image" class="img-thumbnail" style="max-width: 150px;">
                            </td>
                            <td>
                                <img src="../../image/<?php echo htmlspecialchars($bike['detail_image']); ?>" alt="Detail Image" class="img-thumbnail" style="max-width: 150px;">
                            </td>
                            <td>
                                <a href="edit_bike.php?id=<?php echo $bike['bike_id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="manage_bikes.php?delete_id=<?php echo $bike['bike_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this bike?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No bikes found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
