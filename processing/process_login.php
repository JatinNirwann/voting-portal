<?php
session_start();
require_once('config.php');

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch input directly (no sanitization)
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute query to fetch user credentials
    $stmt = $conn->prepare("SELECT id, username, voter_id, password_hash FROM user_data WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $voter_id, $hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Set session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['voter_id'] = $voter_id;
            $_SESSION['logged_in'] = true;

            // Redirect to vote.php after successful login
            header("Location: vote.php");
            exit();
        } else {
            // Redirect back with an error message
            header("Location: index.php?error=Incorrect password!");
            exit();
        }
    } else {
        // Redirect back with an error message
        header("Location: index.php?error=No user found with that username!");
        exit();
    }

    $stmt->close();
}
$conn->close();
?>