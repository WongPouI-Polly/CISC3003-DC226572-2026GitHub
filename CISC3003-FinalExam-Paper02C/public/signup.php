<?php
/**
 * CISC3003-FinalExam-Paper02C
 * Scenario C.01: Signup page
 * Student: Wong Pou I (DC226572)
 */
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paper02C - Sign Up</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Create an Account</h1>
        <p>Scenario C: User Registration & Authentication System</p>
    </header>

    <main>
        <?php if (isset($_SESSION['signup_error'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_SESSION['signup_error']); unset($_SESSION['signup_error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['signup_success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['signup_success']); unset($_SESSION['signup_success']); ?></div>
        <?php endif; ?>

        <!-- C.01: Signup form -->
        <!-- C.05: Client-side validation via JavaScript -->
        <form id="signupForm" action="php/signup-process.php" method="POST" novalidate>
            <fieldset>
                <legend>Registration Information</legend>

                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" placeholder="Choose a username" required maxlength="50">
                    <span class="validation-message" id="username-error"></span>
                </div>

                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <!-- C.06: AJAX email validation on blur -->
                    <input type="email" id="email" name="email" placeholder="your@email.com" required>
                    <span class="validation-message" id="email-error"></span>
                    <span id="email-ajax-status"></span>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="At least 8 characters" required minlength="8">
                    <span class="validation-message" id="password-error"></span>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter password" required>
                    <span class="validation-message" id="confirm_password-error"></span>
                </div>
            </fieldset>

            <div class="form-actions">
                <button type="submit" name="signup">Create Account</button>
            </div>
            <p style="text-align:center; margin-top:1rem;">
                Already have an account? <a href="login.php">Log in here</a>
            </p>
        </form>
    </main>

    <footer>
        <p>CISC3003 Web Programming: Wong Pou I  DC226572  2026</p>
    </footer>

    <!-- C.05: JavaScript validation -->
    <script src="js/signup-validation.js"></script>
    <!-- C.06: AJAX email check -->
    <script src="js/ajax-email-check.js"></script>
</body>
</html>