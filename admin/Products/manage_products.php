<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
require_once "../database.php";

// Handle product deletion
if (isset($_GET['delete'])) {
    $product_id = $_GET['delete'];

    // Fetch the product image paths
    $result = mysqli_query($conn, "SELECT image, additional_images FROM products WHERE product_id = '$product_id'");
    $product = mysqli_fetch_assoc($result);

    // Define paths to delete
    $image_path = "../../image/" . $product['image'];
    $additional_images = json_decode($product['additional_images'], true);

    // Ensure $additional_images is an array
    if (!is_array($additional_images)) {
        $additional_images = [];
    }

    foreach ($additional_images as $img) {
        $img_path = "../../image/" . $img;
        if (file_exists($img_path)) {
            unlink($img_path);
        }
    }

    // Delete product from the database
    $sql = "DELETE FROM products WHERE product_id = '$product_id'";
    if (mysqli_query($conn, $sql)) {
        // Delete main image from the server
        if (file_exists($image_path)) {
            unlink($image_path);
        }
        echo "<div class='alert alert-success'>Product deleted successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error deleting product.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_style.css">
    <script src="admin_scripts.js" defer></script>
    <script src="login-register/other_js_files.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Manage Products</h1>
        <?php
        // Fetch all categories
        $category_result = mysqli_query($conn, "SELECT * FROM categories");
        
        while ($category = mysqli_fetch_assoc($category_result)) {
            // Display category name
            echo "<h2>{$category['category_name']}</h2>";
            
            echo "<table class='table table-striped'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Main Image</th>
                            <th>Additional Images</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>";
            
            // Fetch products for this category
            $category_id = $category['category_id'];
            $product_result = mysqli_query($conn, "SELECT * FROM products WHERE category_id = '$category_id'");
            
            if (mysqli_num_rows($product_result) > 0) {
                while ($row = mysqli_fetch_assoc($product_result)) {
                    $main_image_src = '../../image/' . $row['image'];
                    $additional_images = json_decode($row['additional_images'], true);
                    
                    $additional_images_html = "";
                    if (is_array($additional_images)) {
                        foreach ($additional_images as $img) {
                            $additional_images_html .= "<img src='../../image/$img' width='100' style='margin-right: 5px;'>";
                        }
                    } else {
                        $additional_images_html = "No additional images";
                    }

                    echo "<tr>
                            <td>{$row['product_id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['description']}</td>
                            <td>{$row['price']}</td>
                            <td>{$row['stock']}</td>
                            <td><img src='{$main_image_src}' width='100'></td>
                            <td>{$additional_images_html}</td>
                            <td>
                                <a href='edit_product.php?id={$row['product_id']}' class='btn btn-warning'>Edit</a>
                                <a href='manage_products.php?delete={$row['product_id']}' class='btn btn-danger'>Delete</a>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No products found in this category.</td></tr>";
            }
            
            echo "</tbody></table>";
        }
        ?>
    </div>
</body>
</html>
