<?php
/**
 * CISC3003-FinalExam-Paper02C
 * Scenario C.04: Logout page
 * Student: Wong Pou I (DC226572)
 */

session_start();

// Clear all session variables
$_SESSION = [];

// Destroy the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Redirect to login page with success message
session_start();
$_SESSION['login_success'] = "You have been logged out successfully.";
header("Location: ../login.php");
exit();