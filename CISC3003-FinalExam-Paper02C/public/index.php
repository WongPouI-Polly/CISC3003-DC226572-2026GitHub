<?php
/**
 * CISC3003-FinalExam-Paper02C
 * Home page
 * Student: Wong Pou I (DC226572)
 */
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paper02C - Home</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Paper02C: User Authentication System</h1>
        <p>Scenario C: Signup, Login, Email Confirmation & Password Reset</p>
    </header>

    <?php if (isset($_SESSION['user_id'])): ?>
    <nav>
        <a href="index.php">Home</a>
        <a href="dashboard.php">Dashboard</a>
        <div class="nav-right">
            <span class="user-info">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="php/logout.php" class="btn btn-outline" style="padding:0.4rem 1rem;">Logout</a>
        </div>
    </nav>
    <?php endif; ?>

    <main>
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="alert alert-success">
                You are logged in as <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>.
            </div>
            <p><a href="dashboard.php" class="btn btn-primary">Go to Dashboard</a></p>
        <?php else: ?>
            <div class="dashboard-card">
                <h3>Welcome!</h3>
                <p>This project demonstrates a complete user authentication system:</p>
                <ul>
                    <li>User Registration with server-side validation</li>
                    <li>Client-side JavaScript validation</li>
                    <li>AJAX email availability check</li>
                    <li>Email confirmation before login</li>
                    <li>Secure login and logout</li>
                    <li>Password reset via email</li>
                </ul>
                <div style="display:flex; gap:1rem; margin-top:1rem;">
                    <a href="signup.php" class="btn btn-primary">Sign Up</a>
                    <a href="login.php" class="btn btn-outline">Log In</a>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <p>CISC3003 Web Programming: Wong Pou I  DC226572  2026</p>
    </footer>
</body>
</html>