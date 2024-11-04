<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
require_once "../database.php";

// Initialize variables
$user_id = null;
$user_activities = [];
$user_full_name = ""; // Variable to hold the full name of the selected user

// Check if a user is selected
if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    // Fetch user activity for the selected user
    $sql = "SELECT a.*, u.full_name FROM user_activity a JOIN users u ON a.user_id = u.id WHERE a.user_id = ? ORDER BY a.action_date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_activities = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Fetch the full name of the selected user
    $name_sql = "SELECT full_name FROM users WHERE id = ?";
    $stmt_name = $conn->prepare($name_sql);
    $stmt_name->bind_param("i", $user_id);
    $stmt_name->execute();
    $stmt_name->bind_result($user_full_name);
    $stmt_name->fetch();
    $stmt_name->close();
} else {
    // If no user is selected, fetch all users for the dropdown
    $users_result = mysqli_query($conn, "SELECT id, full_name FROM users");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Activities</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="access_logs.css">
    <script src="admin_scripts.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>User Activities</h1>

        <!-- User selection form -->
        <form method="POST" class="mb-3">
            <div class="form-group">
                <label for="user_id">Select User:</label>
                <select name="user_id" id="user_id" class="form-control" required>
                    <option value="">-- Select a User --</option>
                    <?php
                    // Populate user dropdown if no user is selected
                    if (!isset($user_id)) {
                        while ($user_row = mysqli_fetch_assoc($users_result)) {
                            echo "<option value='{$user_row['id']}'>{$user_row['full_name']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mt-2">View User Activities</button>
        </form>

        <!-- Display user activities for the selected user -->
        <?php if (isset($user_id)): ?>
            <h2>User Activities for <?php echo htmlspecialchars($user_full_name); ?></h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Date/Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($user_activities as $activity) {
                        echo "<tr>
                                <td>{$activity['full_name']}</td>
                                <td>{$activity['action']}</td>
                                <td>{$activity['action_date']}</td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
