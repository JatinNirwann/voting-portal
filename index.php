<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from form fields
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'testing_voting_portal');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT password_hash FROM user_data WHERE username = ?");
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        // Check if username exists in the database
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashed_password);
            $stmt->fetch();

            // Verify the input password with the hashed password from the database
            if (password_verify($password, $hashed_password)) {
                // Retrieve voter_id from the voters table
                $voter_stmt = $conn->prepare("SELECT voter_id FROM voters WHERE username = ?");
                if ($voter_stmt) {
                    $voter_stmt->bind_param("s", $username);
                    $voter_stmt->execute();
                    $voter_stmt->bind_result($voter_id);

                    // If voter_id exists, store session variables
                    if ($voter_stmt->fetch()) {
                        $_SESSION['loggedin'] = true;       // Session flag for logged-in users
                        $_SESSION['username'] = $username; // Store username
                        $_SESSION['voter_id'] = $voter_id; // Store voter ID
                    
                        // Redirect to the voting page after successful login
                        header("Location: vote.php");
                        exit();
                    } else {
                        echo "<script>
                            alert('Voter ID not found for this user. Please contact support.');
                            window.location.href = 'index.php';
                        </script>";
                    }
                    $voter_stmt->close();
                }
            } else {
                // Password mismatch
                echo "<script>
                    alert('Incorrect password! Please try again.');
                    window.location.href = 'index.php';
                </script>";
            }
        } else {
            // Username not found
            echo "<script>
                alert('No user found with that username! Please register.');
                window.location.href = 'index.php';
            </script>";
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo "Error preparing the statement: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}

// Check if the user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: vote.php"); // Redirect logged-in users directly to the voting page
    exit();
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
  <!-- Navigation Bar -->
  <nav>
      <div class="logo">Voting Portal</div>
      <ul>
          <li><a href="index.php">Login</a></li>
          <li><a href="registration.php">Register</a></li>
      </ul>
  </nav>

  <!-- Login Form -->
  <div class="container">
      <div class="login-form" style="position: relative; left: 814px; top: -13px;">
          <h2>Login</h2>
          <form action="index.php" method="POST">
              <!-- Username Input -->
              <div class="input-container">
                  <label for="username">Username</label>
                  <input type="text" id="username" name="username" required>
              </div>
              
              <!-- Password Input -->
              <div class="input-container">
                  <label for="password">Password</label>
                  <input type="password" id="password" name="password" required>
              </div>

              <!-- Submit Button -->
              <button type="submit">Login</button>
              
              <p>Don't have an account? <a href="registration.php">Sign up</a></p>
          </form>
      </div>
  </div>
</body>
</html>
