<?php
/**
 * CISC3003-FinalExam-Paper02C
 * Scenario C.07: Reset password form (after clicking email link)
 * Student: Wong Pou I (DC226572)
 */

session_start();
require_once __DIR__ . '/config.php';

$token = $_GET['token'] ?? '';
$token_valid = false;
$user_id = null;
$username = '';

if (!empty($token) && strlen($token) === 64) {
    $conn = getDBConnection();
    
    $sql = "SELECT id, username FROM users WHERE reset_token = ? AND reset_token_expiry > NOW() LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $token_valid = true;
        $user_id = $user['id'];
        $username = $user['username'];
    }
    
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paper02C - Set New Password</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>Set New Password</h1>
        <p>Scenario C.07: Create a new password</p>
    </header>
    <main>
        <?php if (!$token_valid): ?>
            <div class="alert alert-error">
                Invalid or expired reset link. Please request a new password reset.
            </div>
            <p style="text-align:center;">
                <a href="reset-password.php" class="btn btn-primary">Request New Reset Link</a>
            </p>
        <?php else: ?>
            <?php if (isset($_SESSION['reset_form_error'])): ?>
                <div class="alert alert-error"><?= htmlspecialchars($_SESSION['reset_form_error']); unset($_SESSION['reset_form_error']); ?></div>
            <?php endif; ?>
            
            <p>Setting new password for: <strong><?php echo htmlspecialchars($username); ?></strong></p>
            
            <form action="update-password.php" method="POST" novalidate>
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                
                <fieldset>
                    <legend>New Password</legend>
                    
                    <div class="form-group">
                        <label for="new_password">New Password:</label>
                        <input type="password" id="new_password" name="new_password" 
                               placeholder="At least 8 characters" required minlength="8">
                        <span id="new_password-error" class="validation-message"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" 
                               placeholder="Re-enter new password" required>
                        <span id="confirm_password-error" class="validation-message"></span>
                    </div>
                </fieldset>
                
                <div class="form-actions">
                    <button type="submit" name="update_password">Update Password</button>
                </div>
            </form>
        <?php endif; ?>
    </main>
    <footer>
        <p>CISC3003 Web Programming: Wong Pou I  DC226572  2026</p>
    </footer>

    <script>
        // Client-side validation for password reset form
        const form = document.querySelector('form[action="update-password.php"]');
        if (form) {
            form.addEventListener('submit', function(e) {
                const newPass = document.getElementById('new_password').value;
                const confirmPass = document.getElementById('confirm_password').value;
                let valid = true;
                
                if (!newPass || newPass.length < 8) {
                    document.getElementById('new_password-error').textContent = 'Password must be at least 8 characters.';
                    document.getElementById('new_password-error').className = 'validation-message error';
                    valid = false;
                } else if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])/.test(newPass)) {
                    document.getElementById('new_password-error').textContent = 'Password must include uppercase, lowercase, number, and special character.';
                    document.getElementById('new_password-error').className = 'validation-message error';
                    valid = false;
                } else {
                    document.getElementById('new_password-error').textContent = '';
                    document.getElementById('new_password-error').className = 'validation-message';
                }
                
                if (newPass !== confirmPass) {
                    document.getElementById('confirm_password-error').textContent = 'Passwords do not match.';
                    document.getElementById('confirm_password-error').className = 'validation-message error';
                    valid = false;
                } else {
                    document.getElementById('confirm_password-error').textContent = '';
                    document.getElementById('confirm_password-error').className = 'validation-message';
                }
                
                if (!valid) e.preventDefault();
            });
        }
    </script>
</body>
</html>