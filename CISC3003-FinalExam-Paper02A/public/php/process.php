<?php
/**
 * CISC3003-FinalExam-Paper02A
 * Scenario A: Form Processing with PHP
 * Student: Wong Pou I (DC226572)
 * 
 * A.05: Process submitted form data using PHP
 * A.06: Validate form data using filter functions
 * A.07: Avoid SQL injection attack
 * A.08: Use a prepared statement to insert a new record
 */

// Database configuration
$db_host = '127.0.0.1';
$db_user = 'root';
$db_pass = '';
$db_name = 'cisc3003_paper02a';

// Initialize variables
$errors = [];
$fullname = $email = $phone = $course = $academic_year = $remarks = '';
$learning_mode = [];

// A.05: Process the submitted form data using PHP
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // A.06: Validate the form data using filter functions
    // Validate Fullname (A.02 - simple text input)
    if (empty($_POST["fullname"])) {
        $errors["fullname"] = "Full name is required.";
    } else {
        $fullname = trim($_POST["fullname"]);
        // Sanitize and validate string
        $fullname = filter_var($fullname, FILTER_SANITIZE_STRING);
        if (strlen($fullname) < 2 || strlen($fullname) > 100) {
            $errors["fullname"] = "Full name must be between 2 and 100 characters.";
        }
    }

    // Validate Email
    if (empty($_POST["email"])) {
        $errors["email"] = "Email address is required.";
    } else {
        $email = trim($_POST["email"]);
        // A.06: Validate email using filter function
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "Please provide a valid email address.";
        }
    }

    // Validate Phone (optional but validated if provided)
    if (!empty($_POST["phone"])) {
        $phone = trim($_POST["phone"]);
        $phone = filter_var($phone, FILTER_SANITIZE_STRING);
        if (!preg_match('/^[\+\d\s\-]{8,20}$/', $phone)) {
            $errors["phone"] = "Phone number format is invalid.";
        }
    }

    // Validate Course (A.04 - select list)
    $allowed_courses = ["CISC3003", "CISC3004", "CISC3005", "CISC3006"];
    if (empty($_POST["course"])) {
        $errors["course"] = "Please select a course.";
    } else {
        $course = $_POST["course"];
        if (!in_array($course, $allowed_courses, true)) {
            $errors["course"] = "Invalid course selection.";
        }
    }

    // Validate Academic Year (A.04 - radio buttons)
    $allowed_years = ["1", "2", "3", "4"];
    if (empty($_POST["academic_year"])) {
        $errors["academic_year"] = "Please select an academic year.";
    } else {
        $academic_year = $_POST["academic_year"];
        if (!in_array($academic_year, $allowed_years, true)) {
            $errors["academic_year"] = "Invalid academic year selection.";
        }
    }

    // Validate Learning Mode (A.04 - checkboxes)
    $allowed_modes = ["onsite", "online", "hybrid", "self_paced"];
    if (isset($_POST["learning_mode"]) && is_array($_POST["learning_mode"])) {
        foreach ($_POST["learning_mode"] as $mode) {
            if (in_array($mode, $allowed_modes, true)) {
                $learning_mode[] = $mode;
            }
        }
    }
    $learning_mode_str = implode(", ", $learning_mode);

    // Validate Remarks (A.03 - textarea)
    if (!empty($_POST["remarks"])) {
        $remarks = trim($_POST["remarks"]);
        $remarks = filter_var($remarks, FILTER_SANITIZE_STRING);
        if (strlen($remarks) > 500) {
            $errors["remarks"] = "Remarks must not exceed 500 characters.";
        }
    }

    // If no errors, proceed to database insertion
    if (empty($errors)) {

        // A.07: Avoid SQL injection attack by using prepared statements
        // A.08: Use a prepared statement to insert a new record
        // Create database connection using MySQLi
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name, 3306);

        // Check connection
        if ($conn->connect_error) {
            die("<p style='color:red;'>Database connection failed: " . htmlspecialchars($conn->connect_error) . "</p>");
        }

        // A.08: Prepared statement to prevent SQL injection (A.07)
        $sql = "INSERT INTO registrations (fullname, email, phone, course, academic_year, learning_mode, remarks, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        
        // A.07: avoid an SQL injection attack
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind parameters: s = string, i = integer
            $stmt->bind_param("ssssiss", $fullname, $email, $phone, $course, $academic_year, $learning_mode_str, $remarks);

            // Execute the prepared statement
            if ($stmt->execute()) {
                $success_message = "Registration submitted successfully! Record ID: " . $stmt->insert_id;
            } else {
                $errors["database"] = "Error inserting record: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $errors["database"] = "Error preparing statement: " . $conn->error;
        }

        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paper02A - Processing Result</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .error { color: #dc3545; background: #ffe6e6; padding: 0.5rem; border-radius: 4px; margin: 0.3rem 0; }
        .success { color: #198754; background: #d4edda; padding: 1rem; border-radius: 4px; margin: 1rem 0; }
        .data-display { background: #f8f9fa; padding: 1rem; border-radius: 4px; border: 1px solid #dee2e6; }
        .back-link { display: inline-block; margin-top: 1rem; }
    </style>
</head>
<body>
    <header>
        <h1>Registration Result</h1>
    </header>
    <main>
        <?php if (!empty($errors)): ?>
            <h2 style="color: #dc3545;">Please correct the following errors:</h2>
            <ul>
                <?php foreach ($errors as $field => $error): ?>
                    <li class="error"><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
            <a href="../index.php" class="back-link">← Go back to the form</a>
        <?php elseif (isset($success_message)): ?>
            <div class="success">
                <strong><?php echo htmlspecialchars($success_message); ?></strong>
            </div>
            <div class="data-display">
                <h3>Submitted Data:</h3>
                <p><strong>Full Name:</strong> <?php echo htmlspecialchars($fullname); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($phone ?: 'N/A'); ?></p>
                <p><strong>Course:</strong> <?php echo htmlspecialchars($course); ?></p>
                <p><strong>Academic Year:</strong> <?php echo htmlspecialchars($academic_year); ?></p>
                <p><strong>Learning Mode:</strong> <?php echo htmlspecialchars($learning_mode_str ?: 'None selected'); ?></p>
                <p><strong>Remarks:</strong> <?php echo nl2br(htmlspecialchars($remarks ?: 'N/A')); ?></p>
            </div>
            <a href="../index.php" class="back-link">← Submit another registration</a>
        <?php endif; ?>
    </main>
    <footer>
        <p>CISC3003 Web Programming: Wong Pou I  DC226572  2026</p>
    </footer>
</body>
</html>