<?php
/**
 * CISC3003-FinalExam-Paper02C
 * Scenario C.07: Secure password reset by email
 * Student: Wong Pou I (DC226572)
 */
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paper02C - Reset Password</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>Reset Your Password</h1>
        <p>Scenario C.07: Secure Password Reset by Email</p>
    </header>

    <main>
        <?php if (isset($_SESSION['reset_error'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_SESSION['reset_error']); unset($_SESSION['reset_error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['reset_success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['reset_success']); unset($_SESSION['reset_success']); ?></div>
        <?php endif; ?>

        <form action="send-reset-link.php" method="POST" novalidate>
            <fieldset>
                <legend>Request Password Reset</legend>
                <p>Enter your email address and we will send you a link to reset your password.</p>
                
                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" placeholder="your@email.com" required>
                </div>
            </fieldset>

            <div class="form-actions">
                <button type="submit" name="send_reset">Send Reset Link</button>
            </div>
            <p style="text-align:center; margin-top:1rem;">
                <a href="../login.php">Back to Login</a>
            </p>
        </form>
    </main>

    <footer>
        <p>CISC3003 Web Programming: Wong Pou I  DC226572  2026</p>
    </footer>
</body>
</html>