<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'voting_portal');

// Application Configuration
define('APP_NAME', 'Secure Voting Portal');
define('APP_VERSION', '1.0.0');

// Security Settings
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_DURATION', 15 * 60); // 15 minutes in seconds
define('PASSWORD_HASH_ALGO', PASSWORD_ARGON2ID);

// Logging Configuration
define('LOG_FILE', '/path/to/voting_app.log');
define('LOG_LEVEL', 'ERROR');

// Allowed file upload settings
define('MAX_UPLOAD_SIZE', 2 * 1024 * 1024); // 2MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif']);