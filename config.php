<?php
// Database Configuration
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'testing_voting_portal');

// Password Strength Validation
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
?>