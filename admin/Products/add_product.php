<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
require_once "../database.php";

if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $stock = $_POST['stock'];
    $image = $_FILES['image']['name'];

    // Save the main image in the shared directory
    $target = "../../image/" . basename($image);

    // Prepare the SQL statement
    $stmt = mysqli_prepare($conn, "INSERT INTO products (name, description, price, category_id, stock, image) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo "<div class='alert alert-danger'>Prepare failed: " . mysqli_error($conn) . "</div>";
        exit();
    }

    // Bind parameters
    mysqli_stmt_bind_param($stmt, 'ssdiss', $name, $description, $price, $category_id, $stock, $image);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        // Move uploaded file to the shared images directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            echo "<div class='alert alert-success'>Product added successfully.</div>";

            // Handle additional images
            if (!empty($_FILES['additional_images']['name'][0])) {
                $additional_images = $_FILES['additional_images'];
                $total_images = count($additional_images['name']);
                $uploaded_images = [];

                for ($i = 0; $i < $total_images; $i++) {
                    $file_name = basename($additional_images['name'][$i]);
                    $file_tmp = $additional_images['tmp_name'][$i];
                    $file_target = "../../image/" . $file_name;

                    if (move_uploaded_file($file_tmp, $file_target)) {
                        $uploaded_images[] = $file_name;
                    } else {
                        echo "<div class='alert alert-warning'>Failed to upload additional image: $file_name</div>";
                    }
                }

                // Insert additional images into a separate table if you have one, or just save the filenames in a comma-separated format
                if (!empty($uploaded_images)) {
                    $uploaded_images_str = implode(',', $uploaded_images);
                    $update_stmt = mysqli_prepare($conn, "UPDATE products SET additional_images = ? WHERE image = ?");
                    if ($update_stmt) {
                        mysqli_stmt_bind_param($update_stmt, 'ss', $uploaded_images_str, $image);
                        mysqli_stmt_execute($update_stmt);
                        mysqli_stmt_close($update_stmt);
                    }
                }
            }
        } else {
            echo "<div class='alert alert-warning'>Product added, but failed to move image.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Error adding product: " . mysqli_stmt_error($stmt) . "</div>";
    }

    mysqli_stmt_close($stmt);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_style.css">
    <script src="admin_scripts.js" defer></script>
    <script src="login-register/other_js_files.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Add New Product</h1>
        <form action="add_product.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control" id="price" name="price" required>
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
                <label for="stock" class="form-label">Stock Quantity</label>
                <input type="number" class="form-control" id="stock" name="stock" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Product Image</label>
                <input type="file" class="form-control" id="image" name="image" required>
            </div>
            <div class="mb-3">
                <label for="additional_images" class="form-label">Additional Images</label>
                <input type="file" class="form-control" id="additional_images" name="additional_images[]" multiple>
                <small class="form-text text-muted">You Can Select Multiple Images.</small>
            </div>
            <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
        </form>
    </div>
</body>
</html>
