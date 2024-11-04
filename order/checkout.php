<?php
session_start();
require_once '../admin/database.php'; // Include your database connection file

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../account/login.php"); // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

// Initialize cart items array and total cost
$cartItems = [];
$total_cost = 0;

// Fetch cart items for logged-in users from the database
$query = "SELECT p.product_id, p.name, p.price, c.quantity 
          FROM cart c 
          JOIN products p ON c.product_id = p.product_id 
          WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $cartItems[$row['product_id']] = $row;
    $total_cost += $row['price'] * $row['quantity']; // Calculate total cost
}
$stmt->close();

// Apply discounts based on the total cost
$discount = 0;
if ($total_cost > 8000) {
    $discount = 0.10 * $total_cost; // 10% discount for orders over ₹8000
} elseif ($total_cost > 5000) {
    $discount = 0.05 * $total_cost; // 5% discount for orders over ₹5000
}
$final_cost = $total_cost - $discount; // Calculate final cost after discount

// Fetch user details for shipping
$query = "SELECT email, phone_number FROM site_users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($email, $phone_number);
$stmt->fetch();
$stmt->close();

// Handle order confirmation or online payment request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input from the form
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number']; // Capture phone number
    $payment_method = $_POST['payment_method']; // Capture payment method

    // Insert order into orders table with prepared statements
    $order_query = "INSERT INTO orders (user_id, total, order_date, shipping_address, phone_number, payment_method, status) VALUES (?, ?, NOW(), ?, ?, ?, 'processing')";
    $stmt = $conn->prepare($order_query);
    $stmt->bind_param("idsis", $user_id, $final_cost, $address, $phone_number, $payment_method);

    if ($stmt->execute()) {
        $order_id = $stmt->insert_id; // Get the inserted order ID
        $stmt->close();

        // Insert each cart item into order_items table
        $order_item_query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($order_item_query);
        foreach ($cartItems as $productId => $product) {
            $quantity = $product['quantity'];
            $price = $product['price'];
            $stmt->bind_param("iiid", $order_id, $productId, $quantity, $price);
            $stmt->execute();
        }
        $stmt->close();

        // Clear the cart for logged-in users only if payment is Cash on Delivery
        if ($payment_method == 'cod') {
            $clear_cart_query = "DELETE FROM cart WHERE user_id = ?";
            $stmt = $conn->prepare($clear_cart_query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();

            // Redirect to order confirmation if Cash on Delivery
            header("Location: order_confirmation.php?order_id=$order_id");
            exit();
        } else {
            // If Online Payment, return order ID as JSON response
            echo json_encode(['order_id' => $order_id]);
            exit();
        }
    } else {
        echo "<div class='alert alert-danger'>Error processing your order. Please try again later.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="../home/main.css">
    <link rel="stylesheet" href="checkout.css">
</head>

<body>
    <?php include '../home/header.php'; ?>
    <main>
        <h1>Checkout</h1>

        <div class="user-details">
            <h3>User Details</h3>
            <p>Email: <?php echo htmlspecialchars($email); ?></p>
            <p>Phone: <?php echo htmlspecialchars($phone_number); ?></p>
        </div>

        <div class="cart-summary">
            <h2>Your Cart</h2>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $productId => $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                            <td>₹<?php echo htmlspecialchars($product['price']); ?></td>
                            <td>₹<?php echo htmlspecialchars($product['price'] * $product['quantity']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p class="total-cost">Total Cost: ₹<?php echo number_format($total_cost, 2); ?></p>
            <?php if ($discount > 0): ?>
                <p>Discount: ₹<?php echo number_format($discount, 2); ?> (<?php echo ($total_cost > 8000) ? '10%' : '5%'; ?> off)</p>
            <?php endif; ?>
            <p><strong>Final Cost: ₹<?php echo number_format($final_cost, 2); ?></strong></p>
        </div>

        <h3>Shipping Details</h3>
        <form id="checkoutForm" action="checkout.php" method="POST" style="color: black;">
            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>" required><br>

            <label for="address">Address:</label>
            <textarea id="address" name="address" required></textarea><br>

            <input type="hidden" name="total_price" value="<?php echo $final_cost; ?>">
            <input type="hidden" name="order_id" value="<?php echo isset($order_id) ? $order_id : ''; ?>">

            <h3>Payment Method</h3>
            <input type="radio" id="cod" name="payment_method" value="cod" required> Cash on Delivery<br>
            <input type="radio" id="online" name="payment_method" value="online" required> Online Payment<br>

            <input type="submit" value="Confirm Order">
        </form>

        <div class="qr-popup" id="qrPopup" style="color: black; display: none;">
            <div class="qr-code">
                <button class="close-btn" id="closePopup">&times;</button> <!-- Close button -->
                <h3>Scan the QR Code to Pay</h3>
                <img src="../image/gpay.png" alt="QR Code">
                <br>
                <button id="paymentDoneBtn">Paid</button> <!-- Payment done button -->
            </div>
        </div>
    </main>

    <footer>
        <?php include '../home/footer.php'; ?>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#checkoutForm").submit(function(e) {
                e.preventDefault(); // Prevent default form submission
                var paymentMethod = $("input[name='payment_method']:checked").val();
                console.log("Payment Method:", paymentMethod); // Log payment method

                if (paymentMethod === 'online') {
                    $("#qrPopup").show(); // Show QR code popup for online payment
                    console.log("QR Popup Shown"); // Log when popup is shown
                } else {
                    this.submit(); // Submit the form directly for Cash on Delivery
                }
            });

            $("#paymentDoneBtn").click(function() {
                var formData = $("#checkoutForm").serialize(); // Serialize form data
                $.ajax({
                    type: "POST",
                    url: "checkout.php", // This URL will handle the order confirmation
                    data: formData, // Send form data
                    success: function(response) {
                        var order = JSON.parse(response);
                        console.log("Order ID:", order.order_id); // Log order ID
                        window.location.href = "order_confirmation.php?order_id=" + order.order_id; // Redirect to confirmation page
                    }
                });
            });

            $("#closePopup").click(function() {
                $("#qrPopup").hide(); // Hide QR code popup
            });
        });
    </script>
</body>
</html>
