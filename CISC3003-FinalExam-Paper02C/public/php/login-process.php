<?php
/**
 * CISC3003-FinalExam-Paper02C
 * Scenario C.04: Login processing & authentication
 * Student: Wong Pou I (DC226572)
 */

session_start();
require_once __DIR__ . '/config.php';

// Only process POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../login.php");
    exit();
}

// Validate inputs
$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
$password = $_POST['password'] ?? '';

$errors = [];

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please provide a valid email address.";
}

if (empty($password)) {
    $errors[] = "Password is required.";
}

if (!empty($errors)) {
    $_SESSION['login_error'] = implode("<br>", $errors);
    header("Location: ../login.php");
    exit();
}

// Authenticate user
$conn = getDBConnection();

$sql = "SELECT id, username, email, password_hash, is_active FROM users WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    // Verify password
    if (password_verify($password, $user['password_hash'])) {
        
        // C.08: Check if email is confirmed
        if ($user['is_active'] == 0) {
            $_SESSION['login_error'] = "Please confirm your email address before logging in. Check your inbox for the activation link.";
            $stmt->close();
            $conn->close();
            header("Location: ../login.php");
            exit();
        }
        
        // Login successful - set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        
        // Regenerate session ID for security
        session_regenerate_id(true);
        
        $stmt->close();
        $conn->close();
        
        header("Location: ../dashboard.php");
        exit();
        
    } else {
        $_SESSION['login_error'] = "Invalid email or password.";
    }
} else {
    $_SESSION['login_error'] = "Invalid email or password.";
}

$stmt->close();
$conn->close();

header("Location: ../login.php");
exit();