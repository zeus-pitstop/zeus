<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
require_once "../database.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reviews</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="manage_reviews.css">
    <script src="admin_scripts.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Manage Reviews</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Review ID</th>
                    <th>Product Name</th>
                    <th>Rating</th>
                    <th>Review Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Adjust query based on actual column names
                $result = mysqli_query($conn, "SELECT r.review_id, p.name AS product_name, r.rating, r.review_date 
                                               FROM reviews r 
                                               JOIN products p ON r.product_id = p.product_id");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['review_id']}</td>
                            <td>{$row['product_name']}</td>
                            <td>{$row['rating']}</td>
                            <td>{$row['review_date']}</td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
