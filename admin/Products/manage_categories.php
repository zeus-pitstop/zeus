<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
require_once "../database.php";

if (isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];
    $sql = "INSERT INTO categories (category_name) VALUES ('$category_name')";
    if (mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Category added successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error adding category.</div>";
    }
}

if (isset($_GET['delete'])) {
    $category_id = $_GET['delete'];
    $sql = "DELETE FROM categories WHERE category_id = '$category_id'";
    if (mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Category deleted successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error deleting category.</div>";
    }
}
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
    <script src="login-register/other_js_files.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Manage Categories</h1>
        <form action="manage_categories.php" method="post">
            <div class="mb-3">
                <label for="category_name" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="category_name" name="category_name" required>
            </div>
            <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
        <br><br>
        </form>
        <h2>Existing Categories</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($conn, "SELECT * FROM categories");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['category_id']}</td>
                            <td>{$row['category_name']}</td>
                            <td>
                                <a href='manage_categories.php?delete={$row['category_id']}' class='btn btn-danger'>Delete</a>
                            </td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
