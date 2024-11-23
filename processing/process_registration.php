<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'testing_voting_portal');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch input directly (no sanitization)
    $full_name = $_POST["full_name"];
    $username = $_POST["username"];
    $voter_id = $_POST["voter_id"];
    $password = $_POST["newpassword"];

    // Check if username or voter ID already exists
    $stmt = $conn->prepare("SELECT id FROM user_data WHERE username = ? OR voter_id = ?");
    $stmt->bind_param("ss", $username, $voter_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Redirect back with error
        header("Location: registration.php?error=Username or Voter ID is already registered!");
        exit();
    } else {
        // Hash password and insert new user data
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert_stmt = $conn->prepare("INSERT INTO user_data (full_name, username, password_hash, voter_id) VALUES (?, ?, ?, ?)");
        $insert_stmt->bind_param("ssss", $full_name, $username, $hashed_password, $voter_id);

        if ($insert_stmt->execute()) {
            // Redirect to login page after successful registration
            echo "<script>
                    alert('Registration successful! Please log in.');
                    window.location.href = 'index.php';
                  </script>";
            exit();
        } else {
            // Redirect back with error
            header("Location: registration.php?error=Registration failed. Please try again.");
            exit();
        }
        $insert_stmt->close();
    }

    $stmt->close();
}
$conn->close();
?>
