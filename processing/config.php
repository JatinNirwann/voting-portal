<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "testing_voting_portal";

if (!$conn = mysqli_connect($servername,$username,$password,$dbname)) {
    die("Failed to connect");
}

define('PASSWORD_MIN_LENGTH', 8);

function isPasswordStrong($password) {
    return (
        strlen($password) >= PASSWORD_MIN_LENGTH &&
        preg_match('/[A-Z]/', $password) &&
        preg_match('/[a-z]/', $password) &&
        preg_match('/[0-9]/', $password) &&
        preg_match('/[!@#$%^&*()]/', $password)
    );
}

function check_login($conn)
{
    if(isset($_SESSION['username']))
    {
        $username = $_SESSION['username'];
        $query = "select * from user_data where username = '$username' LIMIT 1"; // Fixed the quote and LIMIT syntax
        $result = mysqli_query($conn, $query); // Fixed mysqli function call
        if($result && mysqli_num_rows($result)>0)
        {
            $user_data = mysqli_fetch_assoc($result);
            return $user_data;
        }
    }
    header("Location: index.php"); // Fixed the space after Location:
    die;
}
?>