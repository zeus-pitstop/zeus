<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
require_once "../database.php";

// Initialize message variable
$message = "";

// Check if 'id' is present in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='alert alert-danger'>No product ID specified.</div>";
    exit();
}

$product_id = $_GET['id'];

// Handle form submission for product update
if (isset($_POST['update_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $stock = $_POST['stock'];

    // Prepare SQL for updating product details
    $sql = "UPDATE products SET name = ?, description = ?, price = ?, category_id = ?, stock = ? WHERE product_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssdiss', $name, $description, $price, $category_id, $stock, $product_id);

    if (mysqli_stmt_execute($stmt)) {
        // Handle main image upload
        if (!empty($_FILES['image']['name'])) {
            $image = $_FILES['image']['name'];
            $target = "../../image/" . basename($image);
            
            // Update the image in the database
            $update_image_sql = "UPDATE products SET image = ? WHERE product_id = ?";
            $image_stmt = mysqli_prepare($conn, $update_image_sql);
            mysqli_stmt_bind_param($image_stmt, 'si', $image, $product_id);
            mysqli_stmt_execute($image_stmt);

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $message = "Product updated successfully.";
            } else {
                $message = "Product updated, but failed to move image.";
            }
        }

        // Handle additional images
        if (!empty($_FILES['additional_images']['name'][0])) {
            // Retrieve existing additional images
            $product_result = mysqli_query($conn, "SELECT additional_images FROM products WHERE product_id = '$product_id'");
            $product = mysqli_fetch_assoc($product_result);
            $existing_images = json_decode($product['additional_images'], true) ?: [];
            
            $new_images = [];
            for ($i = 0; $i < count($_FILES['additional_images']['name']); $i++) {
                if ($_FILES['additional_images']['error'][$i] === UPLOAD_ERR_OK) {
                    $image_name = basename($_FILES['additional_images']['name'][$i]);
                    $target = "../../image/" . $image_name;
                    if (move_uploaded_file($_FILES['additional_images']['tmp_name'][$i], $target)) {
                        $new_images[] = $image_name;
                    }
                }
            }

            // Merge existing images with new ones
            $all_images = array_merge($existing_images, $new_images);
            $images_json = json_encode($all_images);

            // Update additional images in the database
            $update_additional_images_sql = "UPDATE products SET additional_images = ? WHERE product_id = ?";
            $additional_images_stmt = mysqli_prepare($conn, $update_additional_images_sql);
            mysqli_stmt_bind_param($additional_images_stmt, 'si', $images_json, $product_id);
            mysqli_stmt_execute($additional_images_stmt);
        }

        $message = "Product updated successfully."; // Set success message
    } else {
        $message = "Error updating product."; // Set error message
    }

    mysqli_stmt_close($stmt);
}

// Handle image removal
if (isset($_GET['remove_image'])) {
    $image_to_remove = $_GET['remove_image'];
    $product_result = mysqli_query($conn, "SELECT additional_images FROM products WHERE product_id = '$product_id'");
    $product = mysqli_fetch_assoc($product_result);
    $existing_images = json_decode($product['additional_images'], true) ?: [];
    
    // Remove the specified image
    $remaining_images = array_filter($existing_images, function($img) use ($image_to_remove) {
        return $img !== $image_to_remove;
    });

    // Update the database
    $images_json = json_encode($remaining_images);
    $update_additional_images_sql = "UPDATE products SET additional_images = ? WHERE product_id = ?";
    $additional_images_stmt = mysqli_prepare($conn, $update_additional_images_sql);
    mysqli_stmt_bind_param($additional_images_stmt, 'si', $images_json, $product_id);
    mysqli_stmt_execute($additional_images_stmt);

    // Remove the image file
    $image_path = "../../image/" . $image_to_remove;
    if (file_exists($image_path)) {
        unlink($image_path);
    }

    header("Location: edit_product.php?id=$product_id");
    exit();
}

// Retrieve product details
$result = mysqli_query($conn, "SELECT * FROM products WHERE product_id = '$product_id'");
if (mysqli_num_rows($result) == 0) {
    echo "<div class='alert alert-danger'>Product not found.</div>";
    exit();
}
$product = mysqli_fetch_assoc($result);

// Retrieve categories
$categories_result = mysqli_query($conn, "SELECT * FROM categories");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_style.css">
    <link rel="stylesheet" href="edit_product.css">
    <script src="admin_scripts.js" defer></script>
    <script src="login-register/other_js_files.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Edit Product</h1>

        <?php if (!empty($message)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form action="edit_product.php?id=<?php echo htmlspecialchars($product_id); ?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <?php
                    while ($category = mysqli_fetch_assoc($categories_result)) {
                        $selected = ($category['category_id'] == $product['category_id']) ? 'selected' : '';
                        echo "<option value='{$category['category_id']}' $selected>{$category['category_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Product Image</label>
                <input type="file" class="form-control" id="image" name="image">
                <?php if ($product['image']) : ?>
                    <img src="../../image/<?php echo htmlspecialchars($product['image']); ?>" alt="Current Product Image" width="100">
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="additional_images" class="form-label">Additional Images (You Can Select Multiple Images.)</label>
                <input type="file" class="form-control" id="additional_images" name="additional_images[]" multiple>
                <?php
                $additional_images = json_decode($product['additional_images'], true);
                if ($additional_images) {
                    echo "<div class='mt-2'>";
                    foreach ($additional_images as $img) {
                        echo "<div class='d-flex align-items-center'>";
                        echo "<img src='../../image/$img' alt='$img' width='50' class='me-2'>";
                        echo "<a href='edit_product.php?id=$product_id&remove_image=$img' class='btn btn-danger btn-sm ms-2'>Remove</a>";
                        echo "</div>";
                    }
                    echo "</div>";
                }
                ?>
            </div>
            <button type="submit" class="btn btn-primary" name="update_product">Update Product</button>
        </form>
    </div>
</body>
</html>
