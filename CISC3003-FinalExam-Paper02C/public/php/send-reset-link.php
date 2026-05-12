<?php
/**
 * CISC3003-FinalExam-Paper02C
 * Scenario C.07: Send password reset link via email
 * Student: Wong Pou I (DC226572)
 */

session_start();
require_once __DIR__ . '/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

// Only process POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: reset-password.php");
    exit();
}

$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['reset_error'] = "Please provide a valid email address.";
    header("Location: reset-password.php");
    exit();
}

$conn = getDBConnection();

// Check if email exists and user is active
$sql = "SELECT id, username, email, is_active FROM users WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Always show success message to prevent email enumeration
if ($result->num_rows !== 1) {
    // Email not found, but don't reveal this
    $_SESSION['reset_success'] = "If an account with that email exists, a password reset link has been sent.";
    $stmt->close();
    $conn->close();
    header("Location: reset-password.php");
    exit();
}

$user = $result->fetch_assoc();

// Check if account is active (C.08)
if ($user['is_active'] == 0) {
    $_SESSION['reset_error'] = "This account has not been activated yet. Please check your email for the activation link.";
    $stmt->close();
    $conn->close();
    header("Location: reset-password.php");
    exit();
}

// C.07: Generate secure reset token
$reset_token = bin2hex(random_bytes(32));
$reset_token_expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

// Update user with reset token
$update_sql = "UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE id = ?";
$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param("ssi", $reset_token, $reset_token_expiry, $user['id']);

if (!$update_stmt->execute()) {
    $_SESSION['reset_error'] = "Error processing request. Please try again.";
    $stmt->close();
    $update_stmt->close();
    $conn->close();
    header("Location: reset-password.php");
    exit();
}

$stmt->close();
$update_stmt->close();
$conn->close();

// Build reset link
$reset_link = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/reset-password-form.php?token=" . $reset_token;

// C.07: Send password reset email
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'polly1314beel@gmail.com';     // REPLACE
    $mail->Password   = 'qfly vzih xfuc mvmb';         // REPLACE
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8';
    
    $mail->setFrom('polly1314bee@gmail.com', 'CISC3003 Paper02C');
    $mail->addAddress($user['email'], $user['username']);
    
    $mail->isHTML(true);
    $mail->Subject = "Password Reset Request - CISC3003 Paper02C";
    $mail->Body    = "
        <h2>Password Reset Request</h2>
        <p>Hello, " . htmlspecialchars($user['username']) . "!</p>
        <p>We received a request to reset your password. Click the link below to set a new password:</p>
        <p><a href='" . $reset_link . "' style='display:inline-block;padding:10px 20px;background-color:#0d6efd;color:#fff;text-decoration:none;border-radius:4px;'>Reset My Password</a></p>
        <p>Or copy and paste this link into your browser:</p>
        <p>" . $reset_link . "</p>
        <p><strong>This link will expire in 1 hour.</strong></p>
        <p>If you did not request a password reset, please ignore this email.</p>
        <hr>
        <p><small>CISC3003 Web Programming: Wong Pou I  DC226572  2026</small></p>
    ";
    $mail->AltBody = "Hello {$user['username']}!\n\nClick the link to reset your password:\n$reset_link\n\nThis link expires in 1 hour.";
    
    $mail->send();
    $_SESSION['reset_success'] = "If an account with that email exists, a password reset link has been sent.";
    
} catch (Exception $e) {
    error_log("Reset Email Error: " . $mail->ErrorInfo);
    $_SESSION['reset_error'] = "Could not send reset email. Please try again later.";
}

header("Location: reset-password.php");
exit();