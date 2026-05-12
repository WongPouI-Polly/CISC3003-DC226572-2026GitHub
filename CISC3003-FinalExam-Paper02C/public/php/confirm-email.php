<?php
/**
 * CISC3003-FinalExam-Paper02C
 * Scenario C.08: Confirm email address before login
 * Student: Wong Pou I (DC226572)
 */

session_start();
require_once __DIR__ . '/config.php';

$token = $_GET['token'] ?? '';
$message = '';
$message_type = '';

if (empty($token) || strlen($token) !== 64) {
    $message = "Invalid activation token.";
    $message_type = 'error';
} else {
    $conn = getDBConnection();
    
    // Find user with this token, not yet activated, and token not expired
    $sql = "SELECT id, username, email, activation_token_expiry FROM users 
            WHERE activation_token = ? AND is_active = 0 
            AND activation_token_expiry > NOW() 
            LIMIT 1";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // C.08: Activate the user account
        $update_sql = "UPDATE users SET is_active = 1, activation_token = NULL, activation_token_expiry = NULL WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $user['id']);
        
        if ($update_stmt->execute()) {
            $message = "Congratulations, " . htmlspecialchars($user['username']) . "! Your email has been confirmed. You can now log in.";
            $message_type = 'success';
            
            // Log the successful activation
            error_log("User ID {$user['id']} ({$user['email']}) activated successfully.");
        } else {
            $message = "Error activating account. Please try again or contact support.";
            $message_type = 'error';
        }
        $update_stmt->close();
        
    } else {
        // Check if already activated
        $check_sql = "SELECT id, is_active FROM users WHERE activation_token = ? LIMIT 1";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $token);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows === 1) {
            $check_user = $check_result->fetch_assoc();
            if ($check_user['is_active'] == 1) {
                $message = "This account has already been activated. You can log in.";
                $message_type = 'info';
            } else {
                $message = "Activation token has expired. Please register again or request a new activation link.";
                $message_type = 'warning';
            }
        } else {
            $message = "Invalid or expired activation token. Please check your email or register again.";
            $message_type = 'error';
        }
        $check_stmt->close();
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
    <title>Paper02C - Email Confirmation</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>Email Confirmation</h1>
        <p>Scenario C.08: Account Activation</p>
    </header>
    <main>
        <div class="alert alert-<?php echo $message_type === 'success' ? 'success' : ($message_type === 'warning' ? 'warning' : ($message_type === 'info' ? 'info' : 'error')); ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
        <div style="text-align:center; margin-top:2rem;">
            <?php if ($message_type === 'success' || $message_type === 'info'): ?>
                <a href="../login.php" class="btn btn-primary">Go to Login</a>
            <?php else: ?>
                <a href="../signup.php" class="btn btn-primary">Go to Sign Up</a>
            <?php endif; ?>
        </div>
    </main>
    <footer>
        <p>CISC3003 Web Programming: Wong Pou I  DC226572  2026</p>
    </footer>
</body>
</html>