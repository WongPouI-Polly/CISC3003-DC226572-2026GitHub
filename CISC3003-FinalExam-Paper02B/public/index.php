<?php
/**
 * CISC3003-FinalExam-Paper02B
 * Scenario B: Contact Form with PHPMailer
 * Student: Wong Pou I (DC226572)
 */
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paper02B - Contact Form</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Contact Us</h1>
        <p>Scenario B: Email Sending with PHPMailer</p>
    </header>

    <main>
        <!-- Display session messages (B.05: Post/Redirect/Get pattern) -->
        <?php if (isset($_SESSION['contact_success'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_SESSION['contact_success']); ?>
            </div>
            <?php unset($_SESSION['contact_success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['contact_error'])): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($_SESSION['contact_error']); ?>
            </div>
            <?php unset($_SESSION['contact_error']); ?>
        <?php endif; ?>

        <!-- B.01: Contact form with client-side validation -->
        <form id="contactForm" action="php/process-contact.php" method="POST" novalidate>
            <fieldset>
                <legend>Send Us a Message</legend>

                <div class="form-group">
                    <label for="name">Your Name:</label>
                    <input type="text" id="name" name="name" 
                           placeholder="John Doe" required
                           maxlength="100" autocomplete="name">
                    <span class="validation-message" id="name-error"></span>
                </div>

                <div class="form-group">
                    <label for="email">Your Email:</label>
                    <input type="email" id="email" name="email" 
                           placeholder="john@example.com" required
                           autocomplete="email">
                    <span class="validation-message" id="email-error"></span>
                </div>

                <div class="form-group">
                    <label for="subject">Subject:</label>
                    <input type="text" id="subject" name="subject" 
                           placeholder="Message subject" required
                           maxlength="200">
                    <span class="validation-message" id="subject-error"></span>
                </div>

                <div class="form-group">
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" rows="6" 
                              placeholder="Write your message here..." required
                              maxlength="2000"></textarea>
                    <small>Maximum 2000 characters</small>
                    <span class="validation-message" id="message-error"></span>
                </div>
            </fieldset>

            <div class="form-actions">
                <button type="submit" name="send_message">Send Message</button>
                <button type="reset">Clear Form</button>
            </div>
        </form>
    </main>

    <footer>
        <p>CISC3003 Web Programming: Wong Pou I  DC226572  2026</p>
    </footer>

    <!-- B.01: Client-side validation JavaScript -->
    <script src="js/contact-validation.js"></script>
</body>
</html>