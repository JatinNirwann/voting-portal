<?php
$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbname = "voting_portal";

$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST["fullname"];
    $username = $_POST["username"];
    $password = $_POST["newpassword"];
    $aadhar_no = $_POST["aadhar_no"];

    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $hashed_aadhar_no = password_hash($aadhar_no, PASSWORD_DEFAULT);

        $checkUsername = $conn->prepare("SELECT id FROM user_data WHERE username = ?");
        $checkUsername->bind_param("s", $username);
        $checkUsername->execute();
        $checkUsername->store_result();

        if ($checkUsername->num_rows > 0) {
            echo "<script>alert('Username is already taken. Please choose a different one.');</script>";
        } else {
            $checkAadhar = $conn->prepare("SELECT id FROM user_data WHERE aadhar_no = ?");
            $checkAadhar->bind_param("s", $hashed_aadhar_no);
            $checkAadhar->execute();
            $checkAadhar->store_result();

            if ($checkAadhar->num_rows > 0) {
                echo "<script>alert('This Aadhar number is already registered.');</script>";
            } else {
                $stmt = $conn->prepare("INSERT INTO user_data (fullname, username, password, aadhar_no) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $fullname, $username, $hashed_password, $hashed_aadhar_no);

                if ($stmt->execute()) {
                    echo "<script>
                                alert('Registration successful!');
                                window.location.href = 'index.php';
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

    <div class="registration-form" style="width: 565.575px; height: 601.175px; transform: translate(-311.2px, 200px); position: relative; left: -14px; top: -167px; transition: none;" >        
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