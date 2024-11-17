<?php
$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbname = "voting_portal";

// Create a database connection
$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

// Check for a connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST["fullname"];
    $username = $_POST["username"];
    $password = $_POST["newpassword"];
    $aadhar_no = $_POST["aadhar_no"];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the username already exists
    $checkUsername = $conn->prepare("SELECT id FROM user_data WHERE username = ?");
    $checkUsername->bind_param("s", $username);
    $checkUsername->execute();
    $checkUsername->store_result();

    if ($checkUsername->num_rows > 0) {
        echo "<script>alert('Username is already taken. Please choose a different one.');</script>";
    } else {
        // Retrieve all Aadhar hashes to verify uniqueness
        $checkAadhar = $conn->prepare("SELECT aadhar_no FROM user_data");
        $checkAadhar->execute();
        $checkAadhar->bind_result($stored_aadhar_hash);

        $isAadharDuplicate = false;
        while ($checkAadhar->fetch()) {
            if (password_verify($aadhar_no, $stored_aadhar_hash)) {
                $isAadharDuplicate = true;
                break;
            }
        }

        if ($isAadharDuplicate) {
            echo "<script>alert('This Aadhar number is already registered.');</script>";
        } else {
            // Insert the new user into the database
            $stmt = $conn->prepare("INSERT INTO user_data (fullname, username, password, aadhar_no) VALUES (?, ?, ?, ?)");
            $hashed_aadhar_no = password_hash($aadhar_no, PASSWORD_DEFAULT); // Hash Aadhar for storage
            $stmt->bind_param("ssss", $fullname, $username, $hashed_password, $hashed_aadhar_no);

            if ($stmt->execute()) {
                // Start a session and redirect to vote.php
                session_start();
                $_SESSION['username'] = $username;
                echo "<script>
                        alert('Registration successful!');
                        window.location.href = 'vote.php';
                      </script>";
            } else {
                echo "<script>alert('Error: Could not register user. Please try again.');</script>";
            }
            $stmt->close();
        }
        $checkAadhar->close();
    }
    $checkUsername->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
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
            <div class="input-container">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" required>
            </div>

            <div class="input-container">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="input-container">
                <label for="aadhar_no">Aadhar Number</label>
                <input type="text" id="aadhar_no" name="aadhar_no" required>
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