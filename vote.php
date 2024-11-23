<?php

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'testing_voting_portal');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$voter_id = $_SESSION['voter_id'];

// Check if the user has already voted
$stmt = $conn->prepare("SELECT id FROM votes WHERE voter_id = ?");
$stmt->bind_param("s", $voter_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "<p class='message'>You have already voted and cannot vote again.</p>";
    $stmt->close();
    $conn->close();
    exit();
}

// Fetch candidates for the voter's district
$stmt = $conn->prepare("SELECT district_code FROM voters WHERE voter_id = ?");
$stmt->bind_param("s", $voter_id);
$stmt->execute();
$stmt->bind_result($district_code);
$stmt->fetch();
$stmt->close();

$candidates = $conn->query("SELECT id, name, age, party FROM candidates WHERE district_code = '$district_code'");

$conn->close();
?>

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
        <?php if ($candidates->num_rows > 0): ?>
            <?php while ($candidate = $candidates->fetch_assoc()): ?>
                <div class="card" data-id="<?= htmlspecialchars($candidate['id']) ?>">
                    <p>Name: <?= htmlspecialchars($candidate['name']) ?></p>
                    <p>Age: <?= htmlspecialchars($candidate['age']) ?></p>
                    <p>Party: <?= htmlspecialchars($candidate['party']) ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No candidates available for your district.</p>
        <?php endif; ?>
    </div>
</body>
</html>
