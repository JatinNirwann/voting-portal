<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = htmlspecialchars(trim($_POST["full_name"]));
    $username = htmlspecialchars(trim($_POST["username"]));
    $voter_id = htmlspecialchars(trim($_POST["voter_id"]));
    $password = htmlspecialchars(trim($_POST["newpassword"]));

    $conn = new mysqli('localhost', 'root', '', 'testing_voting_portal');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Validate username and voter ID uniqueness
    $stmt = $conn->prepare("SELECT id FROM user_data WHERE username = ? OR voter_id = ?");
    $stmt->bind_param("ss", $username, $voter_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error_message = "Username or Voter ID is already registered!";
    } else {
        // Hash password and insert user data
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert_stmt = $conn->prepare("INSERT INTO user_data (full_name, username, password_hash, voter_id) VALUES (?, ?, ?, ?)");
        $insert_stmt->bind_param("ssss", $full_name, $username, $hashed_password, $voter_id);

        if ($insert_stmt->execute()) {
            echo "<script>
                    alert('Registration successful! Please log in.');
                    window.location.href = 'index.php';
                  </script>";
            exit();
        } else {
            $error_message = "Registration failed. Please try again.";
        }
        $insert_stmt->close();
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Voting Portal</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="registration-page">
    <nav>
        <div class="logo">Voting Portal</div>
        <ul>
            <li><a href="index.php">Login</a></li>
            <li><a href="registration.php">Register</a></li>
        </ul>
    </nav>

    <div class="registration-form">
        <h2>Register</h2>
        <?php if (isset($error_message)): ?>
            <p class="error"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
        <form action="registration.php" method="POST">
            <div class="input-container">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>
            <div class="input-container">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-container">
                <label for="voter_id">Voter ID</label>
                <input type="text" id="voter_id" name="voter_id" required>
            </div>
            <div class="input-container">
                <label for="newpassword">Password</label>
                <input type="password" id="newpassword" name="newpassword" required>
            </div>
            <button type="submit">Register</button>
            <p>Already have an account? <a href="index.php">Login</a></p>
        </form>
    </div>
</body>
</html>
