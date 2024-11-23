<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'testing_voting_portal');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch input directly (no sanitization)
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute query to fetch user credentials
    $stmt = $conn->prepare("SELECT password_hash FROM user_data WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
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
