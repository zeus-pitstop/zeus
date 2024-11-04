<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

require_once "../database.php";

// Fetch the riding gear to edit if an ID is provided
$gear = null;
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT * FROM riding_gears WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $gear = mysqli_fetch_assoc($result);
    } else {
        echo "<div class='alert alert-danger'>Gear not found.</div>";
        exit();
    }
}

// Handle updating the gear
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_gear'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category']);
    
    // Handle product image upload
    $product_image = $gear['product_image'];
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == UPLOAD_ERR_OK) {
        $product_image = 'images/' . basename($_FILES['product_image']['name']);
        move_uploaded_file($_FILES['product_image']['tmp_name'], $product_image);
    }

    // Handle additional images
    $additional_images = $gear['additional_images'];
    if (isset($_FILES['additional_images']) && $_FILES['additional_images']['error'] == UPLOAD_ERR_OK) {
        $additional_images = 'images/' . basename($_FILES['additional_images']['name']);
        move_uploaded_file($_FILES['additional_images']['tmp_name'], $additional_images);
    }

    $sql = "UPDATE riding_gears SET 
            name = '$name',
            description = '$description',
            price = '$price',
            stock = '$stock',
            category_id = '$category_id',
            product_image = '$product_image',
            additional_images = '$additional_images'
            WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Gear updated successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating gear: " . mysqli_error($conn) . "</div>";
    }
}

// Handle deleting the gear
if (isset($_POST['delete_gear'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    
    $sql = "DELETE FROM riding_gears WHERE id = '$id'";
    
    if (mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Gear deleted successfully.</div>";
        header("Location: manage_riding_gears.php"); // Redirect to the management page
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error deleting gear: " . mysqli_error($conn) . "</div>";
    }
}

// Fetch categories for the dropdown
$categories_query = "SELECT * FROM riding_gears";
$categories_result = mysqli_query($conn, $categories_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Riding Gear</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Edit Riding Gear</h1>
        <?php if ($gear): ?>
        <form action="edit_riding_gears.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($gear['id']); ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($gear['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($gear['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($gear['price']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" value="<?php echo htmlspecialchars($gear['stock']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="product_image" class="form-label">Product Image</label>
                <input type="file" class="form-control" id="product_image" name="product_image">
                <?php if ($gear['product_image']): ?>
                <img src="<?php echo htmlspecialchars($gear['product_image']); ?>" alt="Product Image" style="max-width: 200px;">
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="additional_images" class="form-label">Additional Images</label>
                <input type="file" class="form-control" id="additional_images" name="additional_images">
                <?php if ($gear['additional_images']): ?>
                <img src="<?php echo htmlspecialchars($gear['additional_images']); ?>" alt="Additional Images" style="max-width: 200px;">
                <?php endif; ?>
            </div>
            <button type="submit" name="update_gear" class="btn btn-primary">Update Gear</button>
            <button type="submit" name="delete_gear" class="btn btn-danger">Delete Gear</button>
        </form>
        <?php else: ?>
        <div class='alert alert-danger'>No gear to edit.</div>
        <?php endif; ?>
    </div>
</body>
</html>
