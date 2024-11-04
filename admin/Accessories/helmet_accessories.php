<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

require_once "../database.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_helmet'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    // Handle product image upload
    $product_image = '';
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == UPLOAD_ERR_OK) {
        $product_image = 'images/' . basename($_FILES['product_image']['name']);
        move_uploaded_file($_FILES['product_image']['tmp_name'], $product_image);
    }

    // Handle additional images
    $additional_images = '';
    if (isset($_FILES['additional_images']) && $_FILES['additional_images']['error'] == UPLOAD_ERR_OK) {
        $additional_images = 'images/' . basename($_FILES['additional_images']['name']);
        move_uploaded_file($_FILES['additional_images']['tmp_name'], $additional_images);
    }

    $sql = "INSERT INTO helmets (name, description, price, stock, category, product_image, additional_images)
            VALUES ('$name', '$description', '$price', '$stock', '$category', '$product_image', '$additional_images')";

    if (mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Helmet added successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error adding helmet: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Helmet Accessory</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Add Helmet Accessory</h1>
        <form action="helmet_accessories.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" required>
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" required>
            </div>
            <div class="mb-3">
                <label for="product_image" class="form-label">Product Image</label>
                <input type="file" class="form-control" id="product_image" name="product_image">
            </div>
            <div class="mb-3">
                <label for="additional_images" class="form-label">Additional Images</label>
                <input type="file" class="form-control" id="additional_images" name="additional_images">
            </div>
            <button type="submit" name="add_helmet" class="btn btn-primary">Add Helmet</button>
        </form>
    </div>
</body>
</html>
