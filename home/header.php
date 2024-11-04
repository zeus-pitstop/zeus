<?php
// Fetch profile picture path from the database (use the session user ID)
$query = "SELECT profile_pic FROM site_users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($profile_pic);
$stmt->fetch();
$stmt->close();

// Set a default image if the user doesn't have a profile picture
$profile_pic_path = $profile_pic ? '../profile_picz/' . $profile_pic : '../profile_picz/default-profile.png';
?>
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
<header>
    <div class="header-container">
        <div class="logo-container">
            <a href="../home/index.php">
                <img src="../image/logo.png" alt="Logo" class="logo">
            </a>
        </div>
        <div class="zeus">
            <a href="../home/index.php">
                <img src="../image/zeus.png" alt="Zeus" class="alt-name">
            </a>
        </div>
        <div class="search-bar">
            <form action="../home/search.php" method="GET">
                <input type="text" name="query" placeholder="Search for products..." aria-label="Search" required>
                <button type="submit">Search</button>
            </form>
            <div class="search-results"></div>
        </div>
        <div class="wishlist-cart-icon">
            <a href="../wishlist/wishlist.php" class="wishlist" aria-label="Wishlist">
                <i class="fas fa-heart"></i>
            </a>
            <a href="../cart/cart.php" class="cart" aria-label="Cart">
                <i class="far fa-shopping-bag"></i>
            </a>
        </div>
        <div class="icons">
            <div class="profile-dropdown">
                <button class="profile-btn" aria-label="Profile Menu" onclick="goToProfile()">
                    <img src="<?php echo htmlspecialchars($profile_pic_path); ?>" alt="Profile Picture" class="profile-pic-dropdown">
                </button>
                <div class="profile-content">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="../admin/index.php">Admin Login</a>
                        <a href="../home/profile.php">Profile</a>
                        <a href="../account/logout.php" onclick="logout()">Logout</a>
                    <?php else: ?>
                        <a href="../admin/index.php">Admin Login</a>
                        <a href="../account/login.php">Login</a>
                        <a href="../account/create.php">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <nav>
        <ul>
            <li><a href="../all/all.php">All</a></li>
            <li><a href="../home/index.php">Home</a></li>
            <li><a href="../home/about.php">About Us</a></li>
            <li><a href="../home/contact.php">Contact Us</a></li>
        </ul>
    </nav>
</header>
<script>
    function goToProfile() {
        window.location.href = '../home/profile.php';
    }
</script>

<style>
    .profile-img {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 5px;
    }

    .profile-pic,
    .profile-pic-dropdown {
        width: 50px;
        /* Adjust as needed */
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }
</style>