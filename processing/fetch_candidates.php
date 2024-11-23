<?php
// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'testing_voting_portal');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve voter ID securely (e.g., from query parameters or another secure source)
if (!isset($_GET['voter_id']) || empty($_GET['voter_id'])) {
    echo "<p class='message'>Invalid voter ID. Please try again.</p>";
    exit();
}

$voter_id = $_GET['voter_id'];

// Check if the voter has already voted
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
$stmt->close();

// Fetch the voter's district code
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

// Fetch candidates for the voter's district
$query = $conn->prepare("SELECT id, name, age, party FROM candidates WHERE district_code = ?");
$query->bind_param("s", $district_code);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    while ($candidate = $result->fetch_assoc()) {
        echo "<div class='card' data-id='" . htmlspecialchars($candidate['id']) . "'>";
        echo "<p>Name: " . htmlspecialchars($candidate['name']) . "</p>";
        echo "<p>Age: " . htmlspecialchars($candidate['age']) . "</p>";
        echo "<p>Party: " . htmlspecialchars($candidate['party']) . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>No candidates available for your district.</p>";
}

$query->close();
$conn->close();
?>
