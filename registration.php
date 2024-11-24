<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Voting Portal</title>
    <link rel="stylesheet" href="stylesheet/styles.css">
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
        <?php if (isset($_GET['error'])): ?>
            <p class="error"><?= htmlspecialchars($_GET['error']) ?></p>
        <?php endif; ?>
        <form action="processing/process_registration.php" method="POST">
            <div class="input-container">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>
            <div class="input-container">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-container">
                <label for="voter_id">Voter ID</label>
                <input type="text" id="voter_id" name="voter_id" required>
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
