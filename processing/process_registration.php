<?php
session_start();
require_once('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST["full_name"];
    $username = $_POST["username"];
    $voter_id = $_POST["voter_id"];
    $password = $_POST["newpassword"];

    $stmt = $conn->prepare("SELECT id FROM user_data WHERE username = ? OR voter_id = ?");
    $stmt->bind_param("ss", $username, $voter_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        header("Location: registration.php?error=Username or Voter ID is already registered!");
        exit();
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert_stmt = $conn->prepare("INSERT INTO user_data (full_name, username, password_hash, voter_id) VALUES (?, ?, ?, ?)");
        $insert_stmt->bind_param("ssss", $full_name, $username, $hashed_password, $voter_id);

        if ($insert_stmt->execute()) {
            $_SESSION['username'] = $username;
            $_SESSION['voter_id'] = $voter_id;
            $_SESSION['logged_in'] = true;
            
            echo "<script>
                    alert('Registration successful! Please log in.');
                    window.location.href = '../index.php';
                  </script>";
            exit();
        } else {
            header("Location: registration.php?error=Registration failed. Please try again.");
            exit();
        }
        $insert_stmt->close();
    }

    $stmt->close();
}
$conn->close();
?>