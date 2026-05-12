<?php
/**
 * CISC3003-FinalExam-Paper02C
 * Scenario C.04: Login page
 * Student: Wong Pou I (DC226572)
 */
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paper02C - Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Log In</h1>
        <p>Scenario C.04: Login with Email & Password</p>
    </header>

    <main>
        <?php if (isset($_SESSION['login_error'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_SESSION['login_error']); unset($_SESSION['login_error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['login_success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['login_success']); unset($_SESSION['login_success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['reset_success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['reset_success']); unset($_SESSION['reset_success']); ?></div>
        <?php endif; ?>

        <form id="loginForm" action="php/login-process.php" method="POST" novalidate>
            <fieldset>
                <legend>Sign In</legend>

                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" placeholder="your@email.com" required autocomplete="email">
                    <span class="validation-message" id="email-error"></span>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <span class="validation-message" id="password-error"></span>
                </div>
            </fieldset>

            <div class="form-actions">
                <button type="submit" name="login">Log In</button>
            </div>
            <div style="text-align:center; margin-top:1rem;">
                <p>
                    <a href="php/reset-password.php">Forgot your password?</a>
                </p>
                <p>
                    Don't have an account? <a href="signup.php">Sign up here</a>
                </p>
            </div>
        </form>
    </main>

    <footer>
        <p>CISC3003 Web Programming: Wong Pou I  DC226572  2026</p>
    </footer>

    <script>
        // Simple client-side validation for login
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            let valid = true;

            if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                document.getElementById('email-error').textContent = 'Please enter a valid email.';
                valid = false;
            } else {
                document.getElementById('email-error').textContent = '';
            }

            if (!password) {
                document.getElementById('password-error').textContent = 'Password is required.';
                valid = false;
            } else {
                document.getElementById('password-error').textContent = '';
            }

            if (!valid) e.preventDefault();
        });
    </script>
</body>
</html>