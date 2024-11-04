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
    <title>Motorcycle Accessories Store - Premium</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="../image/logo.png" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="search.js" defer></script>
</head>

<body>
    <style>
        .hero {
            background-image: url('../image/background pic.jpg');
            /* Path to your background image */
            background-size: cover;
            /* Ensures the image covers the entire section */
            background-position: center;
            /* Centers the background image */
            min-height: 80vh;
            /* Adjust this value to set the height of the hero section */
            display: flex;
            /* Enables flexbox for centering content */
            align-items: center;
            /* Vertically centers content */
            justify-content: center;
            /* Horizontally centers content */
            color: #fff;
            /* Optional: Change text color for better contrast */
            text-align: center;
            /* Center-align the text */
            padding: 20px;
            /* Optional: Add padding */
        }

        .cta-button {
            background-color: #f5a623;
            /* Button color */
            color: #333;
            /* Text color for button */
            padding: 10px 20px;
            /* Button padding */
            border-radius: 5px;
            /* Rounded corners for button */
            text-decoration: none;
            /* Remove underline */
            transition: background 0.3s ease;
            /* Smooth transition for button background */
        }

        .cta-button:hover {
            background-color: #d59421;
            /* Darker shade on hover */
        }

        .scrolling-text {
            background-color: rgba(0, 0, 0, 0.7);
            /* Semi-transparent black background */
            color: #fff;
            /* White text color */
            font-size: 1.5rem;
            /* Adjust font size */
            padding: 10px;
            /* Add padding */
            white-space: nowrap;
            /* Prevent line breaks */
            text-align: center;
            /* Center-align the text */
            position: relative;
            /* Relative positioning */
            z-index: 10;
            /* Ensure it's above other elements */
        }

        marquee {
            display: block;
            /* Make it a block element */
        }

        /* Import a Google Font */
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

        .about-us {
            padding: 40px;
            /* Increased padding for better spacing */
            text-align: center;
            background-color: #808080;
            /* Background color */
            font-family: 'Roboto', sans-serif;
            /* Font for the section */
            color: #333;
            /* Text color */
            border-radius: 10px;
            /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Subtle shadow for depth */
            margin: 20px;
            /* Margin to separate from other sections */
        }

        .about-us p {
            font-size: 1.1rem;
            /* Font size for paragraphs */
            line-height: 1.6;
            /* Line height for readability */
            margin: 15px 0;
            /* Margin between paragraphs */
        }

        .about-us h2 {
            font-size: 2rem;
            /* Heading font size */
            margin-bottom: 20px;
            /* Space below the heading */
            font-weight: 700;
            /* Bold font weight */
        }


        .featured-products h3 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            text-align: center;
            margin: 10px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .product-grid {
            display: flex;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }

        .product-item {
            width: 200px;
            text-align: center;
            border-radius: 16px;
            /* Rounded corners for the box */
            overflow: hidden;
            /* Ensures rounded corners apply to the content inside */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            /* Smooth transition for transform and box-shadow */
        }

        .product-item:hover {
            transform: scale(1.05);
            /* Scale the product box on hover */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            /* Optional shadow effect on hover */
        }

        .product-item img {
            width: 100%;
            border-radius: 16px;
            /* Rounded corners for the image */
            transition: transform 0.3s ease;
            /* Smooth transition for transform */
        }

        .product-item:hover img {
            transform: scale(1.05);
            /* Scale the image on hover */
        }
    </style>

    <?php include 'header.php'; ?>

    <section class="hero">
        <div class="hero-content">
            <h1>Discover Premium Motorcycle Accessories</h1>
            <p>Elevate your ride with top-notch gear designed for performance and style.</p>
            <a href="../all/all.php" class="cta-button">Shop Now</a>
        </div>
    </section>

    <marquee behavior="scroll" direction="left" scrollamount="10" scrolldelay="50" class="scrolling-text">
        #Customize #Conquer #Ride On
    </marquee>


    <section class="about-us">
        <p>Welcome to Zeus Pitstop, your ultimate destination for premium motorcycle accessories. We are dedicated to providing bikers with the highest quality gear to enhance their riding experience. From stylish helmets to durable riding jackets, our curated selection ensures that you ride safely and in style.</p>
        <p>At Zeus Pitstop, we believe that every motorcycle enthusiast deserves the best. Our team consists of passionate riders who understand the needs of the community. We carefully select products that combine quality, functionality, and aesthetic appeal, ensuring you get only the best.</p>
        <p>Join our community of riders and discover the difference. Whether you're a seasoned rider or just starting out, Zeus Pitstop has something for everyone. Experience the freedom of the open road with confidence and style.</p>
    </section>

    <section class="video-section">
        <div class="video-container">
            <video class="custom-video" autoplay loop muted>
                <source src="../videos/video1.mp4" type="video/mp4">
            </video>
        </div>
    </section>

    <section class="featured-products">
        <h2>Featured Products</h2>
        <div class="product-grid">
            <div class="product-item">
                <a href="http://localhost/zeus/all/product_detail.php?id=40">
                    <img src="../image/200 Hazard.png" alt="Premium Helmet">
                    <h3>Hazard Module</h3>
                </a>
            </div>
            <div class="product-item">
                <a href="http://localhost/zeus/all/product_detail.php?id=31">
                    <img src="../image/200 Metal Lever Guard.png" alt="Riding Jacket">
                    <h3>Carbon Fiber Metal Lever Guard</h3>
                </a>
            </div>
            <div class="product-item">
                <a href="http://localhost/zeus/all/product_detail.php?id=33">
                    <img src="../image/250 Chain Sprocket.png" alt="Motorcycle Gear">
                    <h3>Sprocket</h3>
                </a>
            </div>
        </div>
    </section>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>
    <script src="../account/logout.js" defer></script>
    <script src="../account/loginValidation.js" defer></script>
    <script src="../account/createAccountValidation.js" defer></script>
    <script src="../account/toggleForm.js" defer></script>
</body>

</html>