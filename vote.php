<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote - Voting Portal</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="voting-page">
    <nav>
        <div class="logo">Voting Portal</div>
        <ul>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="card-container">
        <?php
        // Include the PHP logic to fetch candidates
        include 'fetch_candidates.php';
        ?>
    </div>
</body>
</html>
