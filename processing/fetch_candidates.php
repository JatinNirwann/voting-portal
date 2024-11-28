<?php
require_once('config.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo "<script>alert('Please log in to view candidates')</script>";
    exit();
}

$voter_id = $_SESSION['voter_id'];

if (empty($voter_id)) {
    echo "<p class='message'>Invalid voter ID. Please try again.</p>";
    exit();
}
$stmt = $conn->prepare("SELECT vote_cast FROM votes WHERE voter_id = ?");
$stmt->bind_param("s", $voter_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($vote_cast);
$stmt->fetch();

if ($stmt->num_rows > 0 && $vote_cast != 0) { //non-zero means vote casted 
    echo "<p class='message'>You have already voted and cannot vote again.</p>";
    $stmt->close();
    $conn->close();
    exit();
}
$stmt->close();

$stmt = $conn->prepare("SELECT district_code FROM voters WHERE voter_id = ?");
$stmt->bind_param("s", $voter_id);
$stmt->execute();
$stmt->bind_result($district_code);
$stmt->fetch();
$stmt->close();

if (!$district_code) {
    echo "<p class='message'>Unable to fetch your district information. Please contact support.</p>";
    $conn->close();
    exit();
}

$query = $conn->prepare("SELECT id, name, age, party, photo FROM candidates WHERE district_code = ?");
$query->bind_param("s", $district_code);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    while ($candidate = $result->fetch_assoc()) {
        echo "<div class='card' data-id='" . htmlspecialchars($candidate['id']) . "'>";
        echo "<img src='" . htmlspecialchars($candidate['photo']) . "' alt='" . htmlspecialchars($candidate['name']) . "' class='candidate-photo'>";
        echo "<p class='candidate-name'>" . htmlspecialchars($candidate['name']) . "</p>";
        echo "<div class='details'>";
        echo "<p>Age: " . htmlspecialchars($candidate['age']) . "</p>";
        echo "<p>Party: " . htmlspecialchars($candidate['party']) . "</p>";
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "<p>No candidates available for your district.</p>";
}

$query->close();
$conn->close();
?>
