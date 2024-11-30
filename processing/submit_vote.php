<?php
session_start();
require_once 'config.php';

function hashCandidateSelection($candidateId) {
    $salt = 'vB9x$K2#mQ7zL4^pF3*wJ6';
    return hash('sha256', $candidateId . $salt);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $candidateId = $_POST['candidate_id'] ?? null;
    $voterId = $_POST['voter_id'] ?? null;
    $password = $_POST['password'] ?? null;

    if (!$candidateId || !$voterId || !$password) {
        die("Missing required information.");
    }

    try {
        $conn->begin_transaction();

        $stmt = $conn->prepare("SELECT password_hash FROM user_data WHERE voter_id = ?");
        $stmt->bind_param("s", $voterId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            die("Invalid voter ID.");
        }

        $user = $result->fetch_assoc();

        if (!password_verify($password, $user['password_hash'])) {
            die("Incorrect password.");
        }

        $stmt = $conn->prepare("SELECT id FROM candidates WHERE id = ?");
        $stmt->bind_param("i", $candidateId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            die("Invalid candidate selection.");
        }

        $hashedCandidate = hashCandidateSelection($candidateId);

        $stmt = $conn->prepare("INSERT INTO votes (voter_id, candidate_id) VALUES (?, ?)");
        $stmt->bind_param("ss", $voterId, $hashedCandidate);
        $stmt->execute();

        $stmt = $conn->prepare("UPDATE votes SET vote_cast = 1 WHERE voter_id = ?");
        $stmt->bind_param("s", $voterId);
        $stmt->execute();

        $conn->commit();

        // Output a success message and redirect using JavaScript
        echo "<script>
            console.log('Vote submitted successfully.');
            alert('Vote submitted successfully.');
            window.location.href = 'thank_you.php';
        </script>";

    } catch (Exception $e) {
        error_log($e->getMessage(), 3, 'error_log.txt');
        echo "<script>
            console.error('Internal Error: " . $e->getMessage() . "');
            alert('Internal Error');
            window.location.href = 'vote.php';
        </script>";
    }

} else {
    echo "<script>
        console.error('Invalid request method.');
        alert('Invalid request method.');
        window.location.href = 'vote.php';
    </script>";
}

$conn->close();
?>
