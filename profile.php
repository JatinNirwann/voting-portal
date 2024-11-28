<?php
session_start();
require_once 'processing/config.php';

if (!isset($_SESSION['voter_id'])) {
    header('Location: index.php');
    exit();
}

$voter_id = $_SESSION['voter_id'];

$stmt = $conn->prepare("SELECT username, age,  district_code FROM voters WHERE voter_id = ?");
$stmt->bind_param("s", $voter_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();
$stmt->close();

$total_users_stmt = $conn->prepare("SELECT COUNT(*) AS total_users FROM voters");
$total_users_stmt->execute();
$total_users_result = $total_users_stmt->get_result();
$total_users = $total_users_result->fetch_assoc()['total_users'];
$total_users_stmt->close();

$voted_users_stmt = $conn->prepare("SELECT COUNT(*) AS voted_users FROM votes WHERE vote_cast = 1");
$voted_users_stmt->execute();
$voted_users_result = $voted_users_stmt->get_result();
$voted_users = $voted_users_result->fetch_assoc()['voted_users'];
$voted_users_stmt->close();

$analytics_available = ($voted_users / $total_users) > 0.5;

if ($analytics_available) {
    $analytics_stmt = $conn->prepare("
        SELECT party, COUNT(*) AS vote_count 
        FROM candidates 
        JOIN votes ON candidates.id = votes.candidate_id 
        GROUP BY party 
        ORDER BY vote_count DESC 
        LIMIT 1
    ");
    $analytics_stmt->execute();
    $analytics_result = $analytics_stmt->get_result();
    $majority_party = $analytics_result->fetch_assoc()['party'];
    $analytics_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Voting Portal</title>
    <link rel="stylesheet" href="stylesheet/styles.css">
</head>
<body class="profile-page">
    <nav>
        <div class="logo">Voting Portal</div>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="vote.php">Vote</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="processing/logout.php">Logout</a></li>
        </ul>
    </nav>

    <div >
        <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h1>
        <p><strong>Age:</strong> <?php echo htmlspecialchars($user['age']); ?></p>
        <p><strong>District Code:</strong> <?php echo htmlspecialchars($user['district_code']); ?></p>

        <hr>

        <h2>Analytics</h2>
        <?php if ($analytics_available): ?>
            <p>The majority party based on votes so far is: <strong><?php echo htmlspecialchars($majority_party); ?></strong></p>
        <?php else: ?>
            <p>Analytics will be available once more than 50% of users have cast their votes.</p>
        <?php endif; ?>
    </div>
</body>
</html>
