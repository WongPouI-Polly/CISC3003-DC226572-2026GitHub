<?php
/**
 * CISC3003-FinalExam-Paper02C
 * Scenario C.07: Update password after reset
 * Student: Wong Pou I (DC226572)
 */

session_start();
require_once __DIR__ . '/config.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: reset-password.php");
    exit();
}

$token = $_POST['token'] ?? '';
$user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validate
$errors = [];

if (empty($token) || strlen($token) !== 64) {
    $errors[] = "Invalid reset token.";
}

if (!$user_id) {
    $errors[] = "Invalid user.";
}

if (strlen($new_password) < 8) {
    $errors[] = "Password must be at least 8 characters.";
} elseif (!preg_match('/[a-z]/', $new_password) || !preg_match('/[A-Z]/', $new_password) || 
          !preg_match('/\d/', $new_password) || !preg_match('/[!@#$%^&*]/', $new_password)) {
    $errors[] = "Password must contain uppercase, lowercase, number, and special character.";
}

if ($new_password !== $confirm_password) {
    $errors[] = "Passwords do not match.";
}

if (!empty($errors)) {
    $_SESSION['reset_form_error'] = implode("<br>", $errors);
    header("Location: reset-password-form.php?token=" . urlencode($token));
    exit();
}

// Verify token is still valid
$conn = getDBConnection();

$check_sql = "SELECT id FROM users WHERE id = ? AND reset_token = ? AND reset_token_expiry > NOW() LIMIT 1";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("is", $user_id, $token);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows !== 1) {
    $_SESSION['reset_form_error'] = "Invalid or expired reset link.";
    $check_stmt->close();
    $conn->close();
    header("Location: reset-password.php");
    exit();
}
$check_stmt->close();

// Hash new password and update
$password_hash = password_hash($new_password, PASSWORD_DEFAULT);

$update_sql = "UPDATE users SET password_hash = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?";
$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param("si", $password_hash, $user_id);

if ($update_stmt->execute()) {
    $_SESSION['login_success'] = "Your password has been reset successfully. Please log in with your new password.";
    $update_stmt->close();
    $conn->close();
    header("Location: ../login.php");
    exit();
} else {
    $_SESSION['reset_form_error'] = "Error updating password. Please try again.";
    $update_stmt->close();
    $conn->close();
    header("Location: reset-password-form.php?token=" . urlencode($token));
    exit();
}