<?php
/**
 * CISC3003-FinalExam-Paper02C
 * Scenario C.04: Dashboard (requires login)
 * Student: Wong Pou I (DC226572)
 */

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paper02C - Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>User Dashboard</h1>
        <p>Scenario C.04: Protected Page (Login Required)</p>
    </header>

    <nav>
        <a href="dashboard.php">Dashboard</a>
        <div class="nav-right">
            <span class="user-info">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="php/logout.php" class="btn btn-outline" style="padding:0.4rem 1rem;">Logout</a>
        </div>
    </nav>

    <main>
        <div class="dashboard-card">
            <h3>Account Information</h3>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
            <p><strong>User ID:</strong> <?php echo htmlspecialchars($_SESSION['user_id']); ?></p>
        </div>

        <div class="dashboard-card">
            <h3>Quick Actions</h3>
            <p>
                <a href="php/reset-password.php" class="btn btn-outline">Change Password</a>
            </p>
        </div>

        <div class="dashboard-card">
            <h3>Scenario C Completed Tasks</h3>
            <ul>
                <li>✓ C.01: Signup page created</li>
                <li>✓ C.02: Server-side validation in PHP</li>
                <li>✓ C.03: Save signup data to MySQL</li>
                <li>✓ C.04: Login and logout pages</li>
                <li>✓ C.05: Browser validation with JavaScript</li>
                <li>✓ C.06: AJAX email validation</li>
                <li>✓ C.07: Secure password reset by email</li>
                <li>✓ C.08: Email confirmation before login</li>
            </ul>
        </div>
    </main>

    <footer>
        <p>CISC3003 Web Programming: Wong Pou I  DC226572  2026</p>
    </footer>
</body>
</html>