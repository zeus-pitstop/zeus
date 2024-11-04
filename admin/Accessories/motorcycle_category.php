<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

require_once "../database.php";

// Handle adding a new category
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);

    // Check if the category already exists
    $check_query = "SELECT * FROM motorcycle_categories WHERE category_name = '$category_name'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<div class='alert alert-warning'>Category already exists.</div>";
    } else {
        // Add new category
        $insert_query = "INSERT INTO motorcycle_categories (category_name) VALUES ('$category_name')";
        if (mysqli_query($conn, $insert_query)) {
            echo "<div class='alert alert-success'>Category added successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error adding category: " . mysqli_error($conn) . "</div>";
        }
    }
}

// Handle removing a category
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $category_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Remove the category
    $delete_query = "DELETE FROM motorcycle_categories WHERE id = '$category_id'";
    if (mysqli_query($conn, $delete_query)) {
        echo "<div class='alert alert-success'>Category removed successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error removing category: " . mysqli_error($conn) . "</div>";
    }
}

// Fetch existing categories
$categories_query = "SELECT * FROM motorcycle_categories";
$categories_result = mysqli_query($conn, $categories_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_style.css">
    <script src="admin_scripts.js" defer></script>
</head>
<body>
    <div class="container mt-4">
        <h1>Manage Categories</h1>

        <!-- Add Category Form -->
        <form action="motorcycle_category.php" method="post" class="mb-4">
            <div class="mb-3">
                <label for="category_name" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="category_name" name="category_name" required>
            </div>
            <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
        </form>

        <!-- List of Categories -->
        <h2>Existing Categories</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($categories_result)) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['id']) . "</td>
                            <td>" . htmlspecialchars($row['category_name']) . "</td>
                            <td><a href='motorcycle_category.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-danger btn-sm'>Remove</a></td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
