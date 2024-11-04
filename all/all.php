<?php
session_start();
require_once "../admin/database.php"; // Database connection

$wishlist_product_ids = [];
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT product_id FROM wishlist WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $wishlist_product_ids[] = $row['product_id'];
    }
    $stmt->close();
}

// Fetch products and their average ratings from the database
$query = "
    SELECT p.*, 
           IFNULL(AVG(f.rating), 0) AS average_rating, 
           COUNT(f.rating) AS total_reviews 
    FROM products p
    LEFT JOIN feedback f ON p.product_id = f.product_id
    GROUP BY p.product_id
";

$result = $conn->query($query);

// Log the page view
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Assuming you have a $user_id and are fetching user details from the database
$query = "SELECT profile_pic FROM site_users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($profile_pic);
$stmt->fetch();
$stmt->close();

// Set a default image if the profile picture is not available or is missing
if (empty($profile_pic) || !file_exists('../profile_picz/' . $profile_pic)) {
    $profile_pic = 'default-profile.png'; // Set this to your default profile image
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Products - Motorcycle Accessories Store</title>
    <link rel="stylesheet" href="all.css">
    <link rel="stylesheet" href="../home/main.css">
    <link rel="icon" href="../image/logo.png" type="image/x-icon">
    <script src="../home/search.js"></script>
    <script src="../wishlist/wishlist.js" defer></script>
    <script>
        var loggedInStatus = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;

        async function addToCart(event) {
            event.preventDefault(); // Prevent the default form submission

            const form = event.target; // Get the form element
            const formData = new FormData(form); // Create FormData object

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json(); // Parse the JSON response

                // Show the message in an alert box
                alert(result.message);

            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while adding the product to the cart.');
            }
        }
    </script>
</head>

<body>
    <?php include '../home/header.php'; ?>
    <main>
        <section class="product-list">
            <div class="container">
                <h1>All Products</h1>
                <div class="products">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $average_rating = isset($row['average_rating']) ? $row['average_rating'] : 0;
                    ?>
                            <div class="product-item" data-id="<?php echo htmlspecialchars($row['product_id']); ?>"
                                data-name="<?php echo htmlspecialchars($row['name']); ?>"
                                data-price="<?php echo htmlspecialchars($row['price']); ?>">
                                <div class="product-content">
                                    <a href="product_detail.php?id=<?php echo htmlspecialchars($row['product_id']); ?>">
                                        <div class="product-image-container">
                                            <img class="product-image" src="../image/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                                            <div class="wishlist-icon">
                                                <i class="fas fa-heart <?php echo in_array($row['product_id'], $wishlist_product_ids) ? 'filled' : ''; ?>"></i>
                                            </div>
                                        </div>
                                    </a>
                                    <h2><a href="product_detail.php?id=<?php echo htmlspecialchars($row['product_id']); ?>"><?php echo htmlspecialchars($row['name']); ?></a></h2>
                                    <p>₹<?php echo htmlspecialchars($row['price']); ?></p>
                                    <div class="product-rating">
                                        <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $average_rating) {
                                                echo '<i class="fas fa-star"></i>';
                                            } else {
                                                echo '<i class="far fa-star"></i>';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <form action="../all/add_to_cart.php" method="POST" onsubmit="addToCart(event)">
                                    <!-- Display the stock level before the Add to Cart button -->
                                    <p class="stock-level">Available Stock: <?php echo htmlspecialchars($row['stock']); ?></p>

                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['product_id']); ?>">
                                    <input type="number" name="quantity" value="1" min="1" step="1" max="<?php echo htmlspecialchars($row['stock']); ?>" required>

                                    <button type="submit" class="btn add-to-cart">Add to Cart</button>
                                </form>
                            </div>
                    <?php
                        }
                    } else {
                        echo "<p>No products found.</p>";
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <?php include '../home/footer.php'; ?>
    </footer>
</body>

</html>

<?php
$conn->close(); // Close the database connection
?>