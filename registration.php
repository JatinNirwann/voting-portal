<?php
require_once 'config.php'; // Include database configuration and password strength function

session_start(); // Start session for CSRF protection

// Generate CSRF token if it doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$conn = new mysqli($servername,$username,$password,$dbname); // Connect to database
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed");
    }

    // Retrieve form data
    $full_name = $_POST["full_name"];
    $username = $_POST["username"];
    $voter_id = $_POST["voter_id"];
    $password = $_POST["newpassword"];

    // Validate password strength
    if (!isPasswordStrong($password)) {
        echo "<script>alert('Password does not meet strength requirements');</script>";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if username is already taken
        $checkUsername = $conn->prepare("SELECT id FROM user_data WHERE username = ?");
        $checkUsername->bind_param("s", $username);
        $checkUsername->execute();
        $checkUsername->store_result();

        if ($checkUsername->num_rows > 0) {
            echo "<script>alert('Username is already taken');</script>";
        } else {
            // Insert new user
            $stmt = $conn->prepare("INSERT INTO user_data (full_name, username, password_hash, voter_id) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $full_name, $username, $hashed_password, $voter_id);

            if ($stmt->execute()) {
                // Successful registration
                $_SESSION['username'] = $username;
                echo "<script>
                        alert('Registration successful!');
                        window.location.href = 'vote.php';
                      </script>";
            } else {
                echo "<script>alert('Registration failed. Please try again.');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter Registration</title>
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

        <form action="" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

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