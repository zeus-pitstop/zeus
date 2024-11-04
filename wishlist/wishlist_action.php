<?php
session_start();
require_once 'admin/database.php'; // Include your database connection file

// Prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>
        alert('Error: User not logged in.');
        window.history.back(); // Redirects the user back to the previous page
    </script>";
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Check if the product ID is set and valid
if (isset($_GET['product_id']) && is_numeric($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);

    // Check if the product is already in the wishlist
    $check_query = "SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($check_query);
    if ($stmt === false) {
        echo "<script>
            alert('Database error: " . htmlspecialchars($conn->error) . "');
            window.location.href = window.location.href; // Reload the current page
        </script>";
        exit();
    }
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If the product is already in the wishlist, remove it (Toggle behavior)
        $delete_query = "DELETE FROM wishlist WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($delete_query);
        if ($stmt === false) {
            echo "<script>
                alert('Database error: " . htmlspecialchars($conn->error) . "');
                window.location.href = window.location.href; // Reload the current page
            </script>";
            exit();
        }
        $stmt->bind_param("ii", $user_id, $product_id);
        if ($stmt->execute()) {
            echo "<script>alert('Product removed from wishlist.');</script>";
        } else {
            echo "<script>alert('Error removing product from wishlist.');</script>";
        }
    } else {
        // If not, add the product to the wishlist
        $insert_query = "INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_query);
        if ($stmt === false) {
            echo "<script>alert('Database error: " . htmlspecialchars($conn->error) . "');</script>";
            exit();
        }
        $stmt->bind_param("ii", $user_id, $product_id);
        if ($stmt->execute()) {
            echo "<script>alert('Product added to wishlist.');</script>";
        } else {
            echo "<script>alert('Error adding product to wishlist.');</script>";
        }
    }

    $stmt->close();
} else {
    echo "<script>alert('Invalid product ID.');</script>";
}

$conn->close();
?>
<script>
    document.querySelectorAll('.wishlist-link').forEach(icon => {
    icon.addEventListener('click', function(event) {
        event.preventDefault();
        const productId = this.dataset.productId;

        fetch(`wishlist_action.php?product_id=${productId}`, {
            method: 'GET'
        })
        .then(response => response.text())
        .then(result => {
            alert(result);
            // Update the UI dynamically, e.g., toggle the heart icon
            this.classList.toggle('wishlist-active');
            this.style.color = this.classList.contains('wishlist-active') ? '#ff0000' : '#fff';
        })
        .catch(error => console.error('Error:', error));
    });
});
</script>
