<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $conn = new mysqli('localhost', 'root', '', 'voting_portal');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT password FROM user_data WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

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
<body class="index-page">
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
          <form action="#" method="POST">
              <div class="input-container">
                  <label for="userid">User ID</label>
                  <input type="text" id="userid" name="userid" required>
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
