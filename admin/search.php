<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
require_once "database.php"; // Adjust the path as necessary

// Initialize variables
$search_result = [];
$error_message = '';
$search_type = '';

// Check if a search is submitted
if (isset($_POST['search_id'])) {
    $search_id = $_POST['search_id'];
    $search_type = $_POST['search_type'];

    // Validate the input
    if (!empty($search_id)) {
        // Prepare and execute the query based on selected search type
        switch ($search_type) {
            case 'product':
                $sql = "SELECT p.product_id, p.name, c.category_name, p.description, p.stock, p.price 
                        FROM products p 
                        JOIN categories c ON p.category_id = c.category_id 
                        WHERE p.product_id = ?";
                break;
            case 'feedback':
            case 'feedback':
                $sql = "SELECT f.feedback_id, f.message, f.feedback_date, f.rating, p.name AS product_name, u.username 
                            FROM feedback f
                            JOIN site_users u ON f.user_id = u.id
                            JOIN products p ON f.product_id = p.product_id
                            WHERE f.feedback_id = ?";
                break;
            case 'order':
                $sql = "SELECT o.order_id, o.order_date, o.total, o.status, u.username 
                            FROM orders o
                            JOIN site_users u ON o.user_id = u.id
                            WHERE o.order_id = ?";
                break;
            case 'user':
                $sql = "SELECT * FROM site_users WHERE id = ?";
                break;
            default:
                $error_message = "Invalid search type.";
                break;
        }

        if (empty($error_message)) {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $search_id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Fetch the results
            if ($result->num_rows > 0) {
                $search_result = $result->fetch_assoc();
            } else {
                $error_message = "No record found with ID: $search_id";
            }
            $stmt->close();
        }
    } else {
        $error_message = "Please enter a valid ID.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Records</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_style.css">
    <link rel="stylesheet" href="search.css">
</head>

<body>
    <div class="container">
        <h1>Search Records by ID</h1>

        <!-- Search Form -->
        <form method="post" class="mb-4">
            <div class="mb-3">
                <label for="search_type" class="form-label">Select Type</label>
                <select class="form-select" id="search_type" name="search_type" required>
                    <option value="" disabled selected>Select an option</option>
                    <option value="product">Product</option>
                    <option value="feedback">Feedback</option>
                    <option value="order">Order</option>
                    <option value="user">User</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="search_id" class="form-label">ID</label>
                <input type="number" class="form-control" id="search_id" name="search_id" required>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <!-- Display error message -->
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <!-- Display search results -->
        <?php if (!empty($search_result)): ?>
            <h2>Record Details</h2>

            <!-- Product Search Result -->
            <?php if ($search_type == 'product'): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Name</th>
                            <th>Category Name</th>
                            <th>Description</th>
                            <th>Quantity Available</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo htmlspecialchars($search_result['product_id']); ?></td>
                            <td><?php echo htmlspecialchars($search_result['name']); ?></td>
                            <td><?php echo htmlspecialchars($search_result['category_name']); ?></td>
                            <td><?php echo htmlspecialchars($search_result['description']); ?></td>
                            <td><?php echo htmlspecialchars($search_result['stock']); ?></td>
                            <td><?php echo htmlspecialchars($search_result['price']); ?></td>
                        </tr>
                    </tbody>
                </table>

                <!-- Feedback Search Result -->
            <?php elseif ($search_type == 'feedback'): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Feedback ID</th>
                            <th>Username</th>
                            <th>Product Name</th>
                            <th>Message</th>
                            <th>Feedback Date</th>
                            <th>Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo htmlspecialchars($search_result['feedback_id']); ?></td>
                            <td><?php echo htmlspecialchars($search_result['username']); ?></td>
                            <td><?php echo htmlspecialchars($search_result['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($search_result['message']); ?></td>
                            <td><?php echo htmlspecialchars($search_result['feedback_date']); ?></td>
                            <td><?php echo htmlspecialchars($search_result['rating']); ?></td>
                        </tr>
                    </tbody>
                </table>


                <!-- Order Search Result -->
            <?php elseif ($search_type == 'order'): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Username</th>
                            <th>Order Date</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo htmlspecialchars($search_result['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($search_result['username']); ?></td>
                            <td><?php echo htmlspecialchars($search_result['order_date']); ?></td>
                            <td><?php echo htmlspecialchars($search_result['total']); ?></td>
                            <td><?php echo htmlspecialchars($search_result['status']); ?></td>
                        </tr>
                    </tbody>
                </table>

                <!-- User Search Result -->
            <?php elseif ($search_type == 'user'): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo htmlspecialchars($search_result['id']); ?></td>
                            <td><?php echo htmlspecialchars($search_result['username']); ?></td>
                            <td><?php echo htmlspecialchars($search_result['email']); ?></td>
                        </tr>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>

</html>