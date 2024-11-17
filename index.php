<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Correctly retrieving username and password from form fields
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'voting_portal');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT password FROM user_data WHERE username = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if username exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify password with hashed password in database
        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $username;
            header("Location: vote.php");
            exit();
        } else {
            echo "<script>
                alert('Incorrect password! Please try again.');
                window.location.href = 'index.php';
            </script>";
        }
    } else {
        echo "<script>
            alert('No user found with that username! Please register.');
            window.location.href = 'index.php';
        </script>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Voting Portal</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body class="login-page">
  <nav>
      <div class="logo">Voting Portal</div>
      <ul>
          <li><a href="index.php">Login</a></li>
          <li><a href="registration.php">Register</a></li>
      </ul>
  </nav>

  <div class="container">
      <div class="welcome-text">
          <h1>Welcome to the Voting Portal</h1>
          <p>"The vote is precious. It is the most powerful non-violent tool we have in a democratic society." - John Lewis</p>
      </div>

      <div class="login-form">
          <h2>Login</h2>
          <!-- Correcting form action and input name for username -->
          <form action="index.php" method="POST">
              <div class="input-container">
                  <label for="username">Username</label>
                  <input type="text" id="username" name="username" required>
              </div>
              
              <div class="input-container">
                  <label for="password">Password</label>
                  <input type="password" id="password" name="password" required>
              </div>

              <button type="submit">Login</button>
              
              <p>Don't have an account? <a href="registration.php">Sign up</a></p>
          </form>
      </div>
  </div>
</body>
</html>
