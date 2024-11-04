<?php
session_start();
require_once '../admin/database.php'; // Include your database connection file

// Log the page view
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Fetch bike details based on ID
$bike = null;
if (isset($_GET['id'])) {
    $bike_id = $_GET['id'];
    $stmt = mysqli_prepare($conn, "SELECT * FROM bikes WHERE bike_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $bike_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $bike = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$bike) {
        echo "<div class='alert alert-warning'>Bike not found.</div>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bike Detail</title>
    <link rel="stylesheet" href="../home/main.css">
    <link rel="stylesheet" href="bike_detail.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="../image/logo.png" type="image/x-icon">
</head>

<body>
    <?php include '../home/header.php'; ?>
    <main>
        <section class="bike-detail">
            <div class="container">
                <?php if ($bike): ?>
                    <h1><?php echo htmlspecialchars($bike['name']); ?></h1>
                    <div class="bike-media">
                        <?php if (!empty($bike['video'])): ?>
                            <div class="bike-video">
                                <video class="small-video" autoplay loop muted playsinline>
                                    <source src="../videos/<?php echo htmlspecialchars($bike['video']); ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        <?php endif; ?>
                        <div class="bike-image">
                            <img src="image/<?php echo htmlspecialchars($bike['detail_image']); ?>"
                                alt="<?php echo htmlspecialchars($bike['name']); ?>" class="bike-image-content">
                        </div>
                    </div>
                    <p><?php echo htmlspecialchars($bike['description']); ?></p>
                <?php else: ?>
                    <p>Bike details not available.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        <?php include '../home/footer.php'; ?>
    </footer>
</body>

</html>