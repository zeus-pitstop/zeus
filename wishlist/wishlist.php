<?php
session_start();
require_once '../admin/database.php'; // Include your database connection file

// Log the page view
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Handle wishlist actions
if (isset($_SESSION['user_id'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
        $user_id = $_SESSION['user_id'];
        $product_id = intval($_POST['product_id']);

        // Check if product is already in wishlist
        $check_query = "SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("ii", $user_id, $product_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Product already in wishlist'); window.location.href = 'wishlist.php';</script>";
        } else {
            // Insert the product into the wishlist
            $query = "INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $user_id, $product_id);

            if ($stmt->execute()) {
                echo "<script>alert('Product added to wishlist'); window.location.href = 'wishlist.php';</script>";
            } else {
                echo "<script>alert('Failed to add product'); window.location.href = 'wishlist.php';</script>";
            }

            $stmt->close();
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo "<script>alert('Missing product ID'); window.location.href = 'wishlist.php';</script>";
    }
} else {
    echo "<script>alert('You must be logged in to add products to your wishlist'); window.location.href = '../account/login.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist - Motorcycle Accessories Store</title>
    <link rel="stylesheet" href="../home/main.css">
    <link rel="stylesheet" href="wishlist.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="../image/logo.png" type="image/x-icon">
    <script src="../home/search.js"></script>
    <script src="wishlist.js"></script>
</head>

<body>
    <?php include '../home/header.php'; ?>

    <main>
        <section class="wishlist">
            <div class="container">
                <h1 class="wishlist-title">Your Wishlist</h1>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php
                    $user_id = $_SESSION['user_id'];
                    $query = "SELECT p.*, w.quantity FROM products p JOIN wishlist w ON p.product_id = w.product_id WHERE w.user_id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    ?>

                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($product = $result->fetch_assoc()):
                            $product_id = $product['product_id'];
                            $quantity = $product['quantity'];
                            $total_price = $product['price'] * $quantity;
                        ?>
                            <div class="product">
                                <div class="product-image-container">
                                    <a href="../all/product_detail.php?id=<?= urlencode($product_id) ?>">
                                        <img src="../image/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                    </a>
                                </div>
                                <div class="product-details">
                                    <a href="../all/product_detail.php?id=<?= urlencode($product_id) ?>">
                                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                                    </a>
                                    <p class="product-description"><?= htmlspecialchars($product['description']) ?></p>
                                    <p class="product-price">Price: ₹<?= htmlspecialchars($product['price']) ?> X <?= htmlspecialchars($quantity) ?></p>
                                    <p class="product-total">Total: ₹<?= htmlspecialchars($total_price) ?></p>
                                    <p class="stock-status <?= $product['stock'] > 0 ? 'in-stock' : 'out-of-stock' ?>">
                                        <?= $product['stock'] > 0 ? 'In Stock' : 'Out of Stock' ?>
                                    </p>

                                    <div class="quantity-update-container">
                                        <form action="wishlist_update.php" method="POST" class="quantity-form">
                                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id) ?>">
                                            <input type="number" name="quantity" value="<?= htmlspecialchars($quantity) ?>" min="1" class="quantity-input">
                                            <button type="submit" class="btn btn-update">Update</button>
                                        </form>

                                        <form action="../all/add_to_cart.php" method="POST" class="add-to-cart-form">
                                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id) ?>">
                                            <input type="hidden" name="quantity" value="<?= htmlspecialchars($quantity) ?>"> <!-- Pass the quantity from the update -->
                                            <button type="submit" class="btn btn-add-to-cart">Add to Cart</button>
                                        </form>

                                        <form action="wishlist_remove.php" method="POST" class="remove-form">
                                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id) ?>">
                                            <button type="submit" class="btn btn-remove">Remove</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="wishlist-empty">Your wishlist is currently empty. Start browsing our products and add your favorites here for later.</p>
                        <div class="btn-container">
                            <a href="../all/all.php" class="btn">Continue Shopping</a>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="wishlist-login">You must be logged in to view your wishlist.</p>
                    <div class="btn-container">
                        <a href="../account/login.php" class="btn">Login</a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        <?php include '../home/footer.php'; ?>
    </footer>
</body>

</html>