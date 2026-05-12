<?php
/**
 * CISC3003-FinalExam-Paper02B
 * Scenario B: Process Contact Form with PHPMailer
 * Student: Wong Pou I (DC226572)
 * 
 * B.02: Install and configure PHPMailer package
 * B.03: Send email using PHPMailer
 * B.04: Debug problems when sending the email
 * B.05: Use Post / Redirect / Get pattern
 */

session_start();

// B.02: Require PHPMailer (installed via Composer)
// Run: composer require phpmailer/phpmailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

// B.05: Post / Redirect / Get pattern
// Only process POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.php");
    exit();
}

// Server-side validation
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

$errors = [];

// Validate name
if (empty(trim($name)) || strlen(trim($name)) < 2 || strlen(trim($name)) > 100) {
    $errors[] = "Name is required and must be between 2-100 characters.";
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "A valid email address is required.";
}

// Validate subject
if (empty(trim($subject)) || strlen(trim($subject)) < 3 || strlen(trim($subject)) > 200) {
    $errors[] = "Subject is required and must be between 3-200 characters.";
}

// Validate message
if (empty(trim($message)) || strlen(trim($message)) < 10 || strlen(trim($message)) > 2000) {
    $errors[] = "Message is required and must be between 10-2000 characters.";
}

// If there are validation errors, redirect back with error (B.05: PRG)
if (!empty($errors)) {
    $_SESSION['contact_error'] = implode("<br>", $errors);
    header("Location: ../index.php");
    exit();
}

// B.03: Send email using PHPMailer
$mail = new PHPMailer(true);

try {
    // B.04: Debug problems when sending email
    // SMTP debugging (set to 2 for detailed debug output, 0 for production)
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->SMTPDebug = SMTP::DEBUG_OFF;
    
    // B.02: Configure PHPMailer with SMTP settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';       // Gmail SMTP server
    $mail->SMTPAuth   = true;
    $mail->Username   = 'polly1314bee@gmail.com';  // Replace with your Gmail
    $mail->Password   = 'qfly vzih xfuc mvmb';     // Replace with Gmail App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;
    $mail->CharSet    = 'UTF-8';

    // Email headers
    $mail->setFrom('polly1314bee@gmail.com', 'Contact Form');
    $mail->addAddress('polly1314bee@gmail.com', 'Admin');  // Recipient
    $mail->addReplyTo($email, $name);  // Reply to the sender

    // Email content
    $mail->isHTML(true);
    $mail->Subject = "Contact Form: " . $subject;
    $mail->Body    = "
        <h2>New Contact Form Message</h2>
        <p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>
        <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
        <p><strong>Subject:</strong> " . htmlspecialchars($subject) . "</p>
        <hr>
        <h3>Message:</h3>
        <p>" . nl2br(htmlspecialchars($message)) . "</p>
        <hr>
        <p><small>Sent from CISC3003 Paper02B Contact Form</small></p>
    ";
    $mail->AltBody = "Name: $name\nEmail: $email\nSubject: $subject\n\nMessage:\n$message";

    // Send the email
    $mail->send();
    
    // B.05: PRG pattern - Redirect with success message
    $_SESSION['contact_success'] = "Thank you, " . htmlspecialchars($name) . "! Your message has been sent successfully. We will get back to you soon.";
    
} catch (Exception $e) {
    // B.04: Debug problems - Log the error and show user-friendly message
    error_log("PHPMailer Error: " . $mail->ErrorInfo);
    
    // B.05: PRG pattern - Redirect with error message
    $_SESSION['contact_error'] = "Sorry, we could not send your message at this time. Please try again later. Error: " . $mail->ErrorInfo;
}

// B.05: Post / Redirect / Get pattern - Always redirect after POST
header("Location: ../index.php");
exit();