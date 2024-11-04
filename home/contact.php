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
    <title>Contact Us - Motorcycle Accessories Store</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="contact.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="icon" href="../image/logo.png" type="image/x-icon">
    <script src="../account/toggleform.js" defer></script>
    <script src="search.js"></script>
</head>

<body>
    <?php include 'header.php'; ?>
    <main>
        <section class="contact">
            <div class="container">
                <h1>
                    Get In Touch
                </h1>
                <div class="contact-details">
                    <p>
                        <i class="fal fa-map"></i>
                        Mons. Mathew Kothakathu Rd, Chullickal, Kochi, Kerala 682005
                    </p>
                    <p>
                        <i class="fal fa-phone-alt"></i>
                        Phone: <a href="tel:73060 24781">+91 73060 24781</a>
                    </p>
                    <p>
                        <i class="fal fa-clock"></i>
                        Hours: 9am-5pm
                    </p>
                    <p>
                        <i class="fal fa-envelope"></i>
                        Email: <a href="https://mail.google.com/mail/?view=cm&fs=1&to=zeuspitstop24@gmail.com" target="_blank">zeuspitstop24@gmail.com</a>
                    </p>

                    <!-- Map embedded directly within the contact details -->
                    <div class="map">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d17434.125500697164!2d76.255822!3d9.945035!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3b0636b5682e1f77%3A0x5b486059b7a88de3!2s9.945035,76.255822!5e0!3m2!1sen!2s!4v1679911544138!5m2!1sen!2s"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy">
                        </iframe>
                    </div> <!-- End of map container -->

                    <h2>Follow Us</h2>
                    <div class="social-media">
                        <a href="https://www.facebook.com/profile.php?id=61565580682114" target="_blank" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://x.com/ZeusPitstop" target="_blank" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://instagram.com/zeuspitstop" target="_blank" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>

                <form action="contact_submit.php" method="post">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" placeholder="Enter Your Name..." required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Enter Your Email Address..." required>

                    <label for="message">Message:</label>
                    <textarea id="message" name="message" placeholder="Share your feedback or ask us anything..." required></textarea>

                    <button type="submit" class="btn">Send Message</button>
                </form>
            </div>
        </section>
    </main>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>

</html>