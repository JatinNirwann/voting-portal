<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Voting Portal</title>
    <link rel="stylesheet" href="stylesheet/styles.css">
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
        <div class="login-form" style="position: relative; left: 833px; top: -5px;">
            <h2>Login</h2>
            <?php if (isset($_GET['error'])): ?>
                <p class="error"><?= htmlspecialchars($_GET['error']) ?></p>
            <?php endif; ?>
            <form action="processing/process_login.php" method="POST">
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
