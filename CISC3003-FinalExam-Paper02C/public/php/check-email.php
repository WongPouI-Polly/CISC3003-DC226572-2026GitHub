<?php
/**
 * CISC3003-FinalExam-Paper02C
 * Scenario C.06: Validate email using an Ajax request
 * Student: Wong Pou I (DC226572)
 */

// Set JSON content type
header('Content-Type: application/json');

// Check if it's an AJAX request
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request.'
    ]);
    exit();
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
    exit();
}

// Get and validate email
$email = isset($_POST['email']) ? trim($_POST['email']) : '';

// Validate email format
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid email format.'
    ]);
    exit();
}

// Sanitize email
$email = filter_var($email, FILTER_SANITIZE_EMAIL);

// Database connection
require_once __DIR__ . '/config.php';
$conn = getDBConnection();

// C.06: Check if email already exists using prepared statement (prevent SQL injection)
$sql = "SELECT id FROM users WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Email already exists
    echo json_encode([
        'status' => 'unavailable',
        'message' => 'This email is already registered. Please use a different email or log in.'
    ]);
} else {
    // Email is available
    echo json_encode([
        'status' => 'available',
        'message' => 'Email is available!'
    ]);
}

$stmt->close();
$conn->close();