<?php
session_start();
require_once "../admin/database.php"; // Connect to the database

// Check if 'id' is present in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='alert alert-danger'>No product ID specified.</div>";
    exit();
}

$product_id = intval($_GET['id']); // Ensure product_id is an integer

// Retrieve product details
$result = mysqli_query($conn, "SELECT * FROM products WHERE product_id = '$product_id'");
if (mysqli_num_rows($result) == 0) {
    echo "<div class='alert alert-danger'>Product not found.</div>";
    exit();
}
$product = mysqli_fetch_assoc($result);

// Fetch reviews with user names
$review_query = "
    SELECT 
        f.feedback_id,
        f.user_id, 
        f.message, 
        f.rating, 
        f.feedback_date,
        u.username AS user_name
    FROM 
        feedback f
    JOIN 
        site_users u ON f.user_id = u.id
    WHERE 
        f.product_id = ?
    ORDER BY 
        f.feedback_date DESC
";
$review_stmt = $conn->prepare($review_query);
$review_stmt->bind_param("i", $product_id);
$review_stmt->execute();
$reviews = $review_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$review_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Product Details</title>
    <link rel="stylesheet" href="../home/main.css">
    <link rel="stylesheet" href="product_detail.css">
    <script src="product_detail.js" defer></script>
</head>

<body>
    <?php include '../home/header.php'; ?>
    <main>
        <section class="product-detail">
            <div class="container">
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                <div class="product-detail-container">
                    <div class="carousel-container">
                        <div class="carousel">
                            <?php if (!empty($product['image'])): ?>
                                <div class="carousel-item">
                                    <img class="product-image"
                                        src="../image/<?php echo htmlspecialchars($product['image']); ?>"
                                        alt="<?php echo htmlspecialchars($product['name']); ?>">
                                </div>
                            <?php endif; ?>

                            <?php
                            // Assume `additional_images` column stores image filenames separated by commas
                            $images = explode(',', $product['additional_images']);
                            if (empty($images[0])) {
                                // No additional images
                                echo '<div class="no-images-error">No additional images available.</div>';
                            } else {
                                foreach ($images as $image) {
                                    $image = trim($image);
                                    if (!empty($image)) {
                                        echo '<div class="carousel-item">';
                                        echo '<img class="product-image" src="../image/' . htmlspecialchars($image) . '" alt="' . htmlspecialchars($product['name']) . '">';
                                        echo '</div>';
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="product-info">
                        <p class="description"><?php echo htmlspecialchars($product['description']); ?></p>
                        <p class="price">Price: ₹<?php echo htmlspecialchars($product['price']); ?></p>
                        <p class="quantity">Stock Left: <?php echo htmlspecialchars($product['stock']); ?></p>

                        <!-- Buttons for Wishlist, Add to Cart, and Buy Now -->
                        <div class="product-actions">
                            <form action="../wishlist/wishlist.php" method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                <button type="submit" class="wishlist-button">Add to Wishlist</button>
                            </form>

                            <!-- Disable Add to Cart if stock is 0 -->
                            <form action="../all/add_to_cart.php" method="POST" onsubmit="checkLogin(event, this)" <?php if ($product['stock'] == 0) echo 'style="display:none;"'; ?>>
                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
                                <label for="quantity">Quantity:</label>
                                <input type="number" name="quantity" id="quantity" value="1" min="1" step="1" required>
                                <button type="submit" class="btn add-to-cart">Add to Cart</button>
                            </form>

                            <?php if ($product['stock'] == 0): ?>
                                <p class="out-of-stock-alert" style="color: red;">This product is out of stock.</p>
                            <?php endif; ?>
                        </div>

                        <!-- Feedback Section -->
                        <div class="feedback">
                            <h2>Leave Feedback</h2>
                            <form id="feedback-form">
                                <label for="rating">Rate this product:</label>
                                <input type="range" id="rating" name="rating" min="0" max="5" step="1" value="0">
                                <span id="rating-value">0</span>/5
                                <textarea id="feedback-message" name="comment" placeholder="Leave your feedback here..." required></textarea>
                                <button type="submit" class="btn submit-feedback" data-product-id="<?php echo $product_id; ?>">Submit Feedback</button>
                                <p class="feedback-success" style="display: none;">Feedback submitted successfully!</p>
                                <p class="feedback-error" style="display: none;"></p>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="review-container">
                    <h2 id="customer-reviews-heading">Customer Reviews</h2>
                    <div class="reviews">
                        <?php if (!empty($reviews)): ?>
                            <?php foreach ($reviews as $review): ?>
                                <div class="review-item">
                                    <p><strong><?php echo htmlspecialchars($review['user_name']); ?></strong></p>
                                    <p class="rating">Rating: <?php echo htmlspecialchars($review['rating']); ?> Stars</p>
                                    <p><?php echo htmlspecialchars($review['message']); ?></p>
                                    <p><em>Posted on <?php echo date('F d, Y', strtotime($review['feedback_date'])); ?></em></p>
                                    <?php if ($review['user_id'] == $_SESSION['user_id']): ?>
                                        <button class="delete-review"
                                            data-review-id="<?php echo htmlspecialchars($review['feedback_id']); ?>">Delete</button>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No reviews yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <?php include '../home/footer.php'; ?>
    </footer>
</body>

</html>