<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

require_once "../database.php";

// Fetch the helmet accessory to edit if an ID is provided
$accessory = null;
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT * FROM helmet_accessories WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $accessory = mysqli_fetch_assoc($result);
    } else {
        echo "<div class='alert alert-danger'>Accessory not found.</div>";
        exit();
    }
}

// Handle updating the accessory
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_accessory'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category']);
    
    // Handle product image upload
    $product_image = $accessory['product_image'];
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == UPLOAD_ERR_OK) {
        $product_image = 'images/' . basename($_FILES['product_image']['name']);
        move_uploaded_file($_FILES['product_image']['tmp_name'], $product_image);
    }

    // Handle additional images
    $additional_images = $accessory['additional_images'];
    if (isset($_FILES['additional_images']) && $_FILES['additional_images']['error'] == UPLOAD_ERR_OK) {
        $additional_images = 'images/' . basename($_FILES['additional_images']['name']);
        move_uploaded_file($_FILES['additional_images']['tmp_name'], $additional_images);
    }

    $sql = "UPDATE helmet_accessories SET 
            name = '$name',
            description = '$description',
            price = '$price',
            stock = '$stock',
            category_id = '$category_id',
            product_image = '$product_image',
            additional_images = '$additional_images'
            WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Accessory updated successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating accessory: " . mysqli_error($conn) . "</div>";
    }
}

// Handle deleting the accessory
if (isset($_POST['delete_accessory'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    
    $sql = "DELETE FROM helmet_accessories WHERE id = '$id'";
    
    if (mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Accessory deleted successfully.</div>";
        header("Location: manage_helmet_accessories.php"); // Redirect to the management page
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error deleting accessory: " . mysqli_error($conn) . "</div>";
    }
}

// Fetch categories for the dropdown
$categories_query = "SELECT * FROM helmets";
$categories_result = mysqli_query($conn, $categories_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Helmet Accessory</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Edit Helmet Accessory</h1>
        <?php if ($accessory): ?>
        <form action="edit_helmet_accessories.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($accessory['id']); ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($accessory['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($accessory['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($accessory['price']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" value="<?php echo htmlspecialchars($accessory['stock']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="product_image" class="form-label">Product Image</label>
                <input type="file" class="form-control" id="product_image" name="product_image">
                <?php if ($accessory['product_image']): ?>
                <img src="<?php echo htmlspecialchars($accessory['product_image']); ?>" alt="Product Image" style="max-width: 200px;">
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="additional_images" class="form-label">Additional Images</label>
                <input type="file" class="form-control" id="additional_images" name="additional_images">
                <?php if ($accessory['additional_images']): ?>
                <img src="<?php echo htmlspecialchars($accessory['additional_images']); ?>" alt="Additional Images" style="max-width: 200px;">
                <?php endif; ?>
            </div>
            <button type="submit" name="update_accessory" class="btn btn-primary">Update Accessory</button>
            <button type="submit" name="delete_accessory" class="btn btn-danger">Delete Accessory</button>
        </form>
        <?php else: ?>
        <div class='alert alert-danger'>No accessory to edit.</div>
        <?php endif; ?>
    </div>
</body>
</html>
