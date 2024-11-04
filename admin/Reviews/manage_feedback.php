<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
require_once "../database.php";

// Handle delete feedback
if (isset($_POST['delete'])) {
    $feedback_id = $_POST['feedback_id'];
    $delete_query = "DELETE FROM feedback WHERE feedback_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $feedback_id);
    if ($stmt->execute()) {
        echo "<script>alert('Feedback deleted successfully');</script>";
    } else {
        echo "<script>alert('Error deleting feedback');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Feedback</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="manage_feedback.css">
    <script src="admin_scripts.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Manage Feedback</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Feedback ID</th>
                    <th>User Name</th>
                    <th>Product Name</th> <!-- New Column for Product Name -->
                    <th>Message</th>
                    <th>Feedback Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($conn, "
                    SELECT f.feedback_id, u.full_name AS user_name, p.name AS product_name, f.message, f.feedback_date 
                    FROM feedback f 
                    JOIN users u ON f.user_id = u.id 
                    JOIN products p ON f.product_id = p.product_id"); // Ensure this joins with your products table correctly

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['feedback_id']}</td>
                            <td>{$row['user_name']}</td>
                            <td>{$row['product_name']}</td> <!-- Display Product Name -->
                            <td>{$row['message']}</td>
                            <td>{$row['feedback_date']}</td>
                            <td>
                                <form method='POST' style='display:inline-block;'>
                                    <input type='hidden' name='feedback_id' value='{$row['feedback_id']}'>
                                    <button type='submit' name='delete' class='btn btn-danger btn-sm'>Delete</button>
                                </form>
                            </td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
