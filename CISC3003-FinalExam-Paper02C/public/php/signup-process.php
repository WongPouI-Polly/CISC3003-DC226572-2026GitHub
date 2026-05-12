<?php
/**
 * CISC3003-FinalExam-Paper02C
 * Scenario C.02: Validate signup data on the server in PHP
 * Scenario C.03: Save the signup data to a MySQL database using PHP
 * Scenario C.08: Develop functionality that requires email confirmation
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
    header("Location: ../signup.php");
    exit();
}

// ============ C.02: Server-side validation ============
$errors = [];

// Validate username
$username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
if (empty($username)) {
    $errors[] = "Username is required.";
} elseif (strlen($username) < 3 || strlen($username) > 50) {
    $errors[] = "Username must be between 3 and 50 characters.";
} elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    $errors[] = "Username can only contain letters, numbers, and underscores.";
}

// Validate email
$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
if (empty($email)) {
    $errors[] = "Email is required.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please provide a valid email address.";
} elseif (strlen($email) > 255) {
    $errors[] = "Email must not exceed 255 characters.";
}

// Validate password
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if (empty($password)) {
    $errors[] = "Password is required.";
} elseif (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters.";
} elseif (!preg_match('/[a-z]/', $password)) {
    $errors[] = "Password must contain at least one lowercase letter.";
} elseif (!preg_match('/[A-Z]/', $password)) {
    $errors[] = "Password must contain at least one uppercase letter.";
} elseif (!preg_match('/\d/', $password)) {
    $errors[] = "Password must contain at least one number.";
} elseif (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password)) {
    $errors[] = "Password must contain at least one special character.";
}

// Validate confirm password
if (empty($confirm_password)) {
    $errors[] = "Please confirm your password.";
} elseif ($password !== $confirm_password) {
    $errors[] = "Passwords do not match.";
}

// If validation fails, redirect back with errors
if (!empty($errors)) {
    $_SESSION['signup_error'] = implode("<br>", $errors);
    header("Location: ../signup.php");
    exit();
}

// ============ C.03: Save to MySQL database ============
$conn = getDBConnection();

// Check if username already exists
$check_sql = "SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ss", $username, $email);
$check_stmt->execute();
$check_stmt->store_result();

if ($check_stmt->num_rows > 0) {
    $check_stmt->close();
    $conn->close();
    $_SESSION['signup_error'] = "Username or email already exists. Please choose a different one.";
    header("Location: ../signup.php");
    exit();
}
$check_stmt->close();

// Hash the password securely
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// C.08: Generate activation token for email confirmation
$activation_token = bin2hex(random_bytes(32));
$activation_token_expiry = date('Y-m-d H:i:s', strtotime('+24 hours'));

// Insert new user (is_active = 0 until email confirmed)
$sql = "INSERT INTO users (username, email, password_hash, is_active, activation_token, activation_token_expiry, created_at) 
        VALUES (?, ?, ?, 0, ?, ?, NOW())";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    $_SESSION['signup_error'] = "Database error: " . $conn->error;
    header("Location: ../signup.php");
    exit();
}

$stmt->bind_param("sssss", $username, $email, $password_hash, $activation_token, $activation_token_expiry);

if ($stmt->execute()) {
    $user_id = $stmt->insert_id;
    
    // C.08: Send activation email
    $activation_link = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/../php/confirm-email.php?token=" . $activation_token;
    
    // Send email using PHPMailer (similar to Paper02B)
    $mail_sent = sendActivationEmail($email, $username, $activation_link);
    
    if ($mail_sent) {
        $_SESSION['signup_success'] = "Account created successfully! Please check your email to activate your account before logging in.";
    } else {
        $_SESSION['signup_success'] = "Account created! However, we could not send the activation email. Please contact support. Your activation link: " . $activation_link;
        // In production, you would not expose the link; this is for debugging
        error_log("Activation email failed for user ID: $user_id. Token: $activation_token");
    }
    
} else {
    $_SESSION['signup_error'] = "Error creating account: " . $stmt->error;
}

$stmt->close();
$conn->close();

// Redirect back to signup page
header("Location: ../signup.php");
exit();

// ============ Helper function to send activation email ============
function sendActivationEmail($email, $username, $activation_link) {
    
    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'polly1314bee@gmail.com';     // REPLACE with your email
        $mail->Password   = 'qfly vzih xfuc mvmb';         // REPLACE with app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';
        
        $mail->setFrom('polly1314bee@gmail.com', 'CISC3003 Paper02C');
        $mail->addAddress($email, $username);
        
        $mail->isHTML(true);
        $mail->Subject = "Activate Your Account - CISC3003 Paper02C";
        $mail->Body    = "
            <h2>Welcome, " . htmlspecialchars($username) . "!</h2>
            <p>Thank you for creating an account. Please click the link below to activate your account:</p>
            <p><a href='" . $activation_link . "' style='display:inline-block;padding:10px 20px;background-color:#0d6efd;color:#fff;text-decoration:none;border-radius:4px;'>Activate My Account</a></p>
            <p>Or copy and paste this link into your browser:</p>
            <p>" . $activation_link . "</p>
            <p>This link will expire in 24 hours.</p>
            <hr>
            <p><small>CISC3003 Web Programming: Wong Pou I  DC226572  2026</small></p>
        ";
        $mail->AltBody = "Welcome, $username!\n\nPlease click the link to activate your account:\n$activation_link\n\nThis link will expire in 24 hours.";
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("PHPMailer Error (Activation): " . $mail->ErrorInfo);
        return false;
    }
}