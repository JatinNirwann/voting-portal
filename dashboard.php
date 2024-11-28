<?php
session_start();
require_once 'processing/config.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo "<script>alert('Please login first');
            window.location.href = 'index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting Portal - Dashboard</title>
    <link rel="stylesheet" href="stylesheet/styles.css">
</head>

<body class="dashboard-page" style="position: relative;">
    <!-- Navigation -->
    <nav>
        <div class="logo">Voting Portal</div>
        <ul>
            <li><a href="dashboard.html">Dashboard</a></li>
            <li><a href="vote.php">Vote</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="processing/logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="welcome-text">
            <h1 style="position: relative; left: 163px; top: -155px; font-size: 18px; width: 857.6px; color: rgb(28, 36, 40); transition: none;">"The vote is precious. It is the most powerful non-violent tool we have in a democratic society."
                <br>- John Lewis
            </h1>
        </div>
        <div class="login-form" style="position: relative; left: -400px; top: 151px; width: 387.2px; height: 187.8px; transition: none;">
            <div class="input-container" style="position: relative; left: -1px; top: -19px;">
                <button onclick="window.location.href='vote.php'">Vote Now</button>
                <button onclick="window.location.href='profile.php'" style="background: rgba(241, 241, 241, 0.1); margin-top: 15px;">
                    Profile
                </button>
            </div>
        </div>
    </div>
</body>

</html>