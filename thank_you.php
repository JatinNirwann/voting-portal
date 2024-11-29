<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - Voting Portal</title>
    <link rel="stylesheet" href="stylesheet/styles.css">
</head>
<body class="thank-you-page">
    <nav>
        <div class="logo">Voting Portal</div>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="processing/logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="thank-you-container">
        
        <h1 class="thank-you-title">Thank You for Voting!</h1>
        <p class="thank-you-message">
            Your vote has been successfully recorded. Your participation helps strengthen our democratic process.
            You can view the election analytics in your profile section.
        </p>
        
        <a href="profile.php" class="return-button">View Analytics</a>
    </div>
</body>
</html>