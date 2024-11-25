<?php
session_start();
require_once 'config.php';

function hashCandidateSelection($candidateId) {
    $salt = 'vB9x$K2#mQ7zL4^pF3*wJ6';  // Randomly generated salt
    return hash('sha256', $candidateId . $salt);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $candidateId = $_POST['candidate_id'] ?? null;
    $voterId = $_POST['voter_id'] ?? null;
    $password = $_POST['password'] ?? null;

    if (!$candidateId || !$voterId || !$password) {
        http_response_code(400);
        die("Missing required information.");
    }

    try {
        $conn->begin_transaction();

        $stmt = $conn->prepare("SELECT password_hash FROM user_data WHERE voter_id = ?");
        $stmt->bind_param("s", $voterId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            http_response_code(401);
            die("Invalid voter ID.");
        }

        $user = $result->fetch_assoc();

        if (!password_verify($password, $user['password_hash'])) {
            http_response_code(401);
            die("Incorrect password.");
        }

        $stmt = $conn->prepare("SELECT vote_cast FROM voters WHERE voter_id = ?");
        $stmt->bind_param("s", $voterId);
        $stmt->execute();
        $result = $stmt->get_result();
        $voter = $result->fetch_assoc();

        if ($voter['vote_cast']) {
            http_response_code(403);
            die("You have already cast your vote.");
        }

        $stmt = $conn->prepare("SELECT id FROM candidates WHERE id = ?");
        $stmt->bind_param("i", $candidateId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            http_response_code(400);
            die("Invalid candidate selection.");
        }

        $hashedCandidate = hashCandidateSelection($candidateId);

        $stmt = $conn->prepare("INSERT INTO votes (voter_id, candidate_id, vote_given_to) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $voterId, $candidateId, $hashedCandidate);
        $stmt->execute();

        $stmt = $conn->prepare("UPDATE voters SET vote_cast = TRUE WHERE voter_id = ?");
        $stmt->bind_param("s", $voterId);
        $stmt->execute();

        $conn->commit();

        http_response_code(200);
        echo "Vote submitted successfully.";

    } catch (Exception $e) {
        $conn->rollback();

        http_response_code(500);
        echo "An internal error occurred. Please try again.";
    }
} else {
    http_response_code(405);
    echo "Invalid request method.";
}

$conn->close();
?>