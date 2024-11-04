<?php
session_start();
require_once '../admin/database.php'; // Include your database connection file

// Log the page view
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Motorcycle Accessories Store</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="about.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="../image/logo.png" type="image/x-icon">
    <script src="../account/toggleform.js" defer></script>
    <script src="search.js"></script>
</head>

<body>
    <?php include 'header.php'; ?>
    <main>
        <section id="page-header" class="about-header">
            <h2>#Customize #Conquer #Ride On</h2>
            <p>Providing Premium Modification Accessories</p>
        </section>
        <marquee style="background-color: red;" loop="-1" scrollamount="5">Transform Your Bike, Transform Your Journey</marquee>
        <section id="about-us">
            <h2>Welcome to Zeus Pitstop!</h2>
            <p>At Zeus Pitstop, we are dedicated to enhancing your motorcycle journey with premium modification
                accessories. Established with a passion for motorcycling and a commitment to quality, we offer a wide
                range of products designed to elevate your bike’s performance and style.</p>

            <p>Our selection includes high-performance parts, stylish accessories, and essential upgrades that cater to
                the needs of every motorcycle enthusiast. Whether you're looking to boost your bike's power, improve its
                handling, or simply add a touch of personal flair, Zeus Pitstop has you covered.</p>

            <h2>Our Mission</h2>
            <p>Our mission is to provide motorcycle riders with top-notch products that combine performance, durability,
                and aesthetics. We aim to exceed your expectations by delivering exceptional value through our curated
                collection of premium motorcycle accessories.</p>

            <p>We believe in the importance of quality and reliability, which is why we carefully select our products
                from trusted brands and manufacturers. Our goal is to ensure that each accessory we offer enhances your
                riding experience and stands the test of time.</p>

            <h2>Why Choose Us?</h2>
            <ul>
                <li><strong>Premium Quality:</strong> We source and curate only the best products from renowned brands,
                    ensuring that each item meets our high standards for quality and performance.</li>
                <li><strong>Expert Knowledge:</strong> Our team of motorcycle enthusiasts and experts is dedicated to
                    providing you with knowledgeable advice and support, helping you make informed decisions about your
                    modifications.</li>
                <li><strong>Customer Satisfaction:</strong> We are committed to delivering exceptional customer service.
                    From browsing our website to receiving your order, we strive to make your shopping experience smooth
                    and enjoyable.</li>
                <li><strong>Innovative Solutions:</strong> We continuously update our product offerings to include the
                    latest innovations and trends in motorcycle modification, keeping you ahead of the curve.</li>
                <li><strong>Community Focused:</strong> At Zeus Pitstop, we value the connection with our community of
                    riders. We engage with our customers through social media, events, and promotions to foster a
                    vibrant and supportive motorcycle culture.</li>
            </ul>

            <h2>Join the Zeus Pitstop Community</h2>
            <p>Discover our wide range of motorcycle accessories and experience the difference that premium quality
                makes. Follow us on social media to stay updated on new arrivals, special offers, and riding tips. We
                invite you to be a part of the Zeus Pitstop community and share your journey with us.</p>

            <p>Thank you for choosing Zeus Pitstop. We look forward to serving you and enhancing your riding experience
                with our exceptional products. Ride with confidence, ride with style!</p>
        </section>
    </main>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>

</html>