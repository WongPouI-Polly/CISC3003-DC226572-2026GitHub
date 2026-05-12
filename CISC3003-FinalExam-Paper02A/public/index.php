<?php
/**
 * CISC3003-FinalExam-Paper02A
 * Scenario A: HTML Form with multiple controls
 * Student: Wong Pou I (DC226572)
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paper02A - Student Registration Form</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Student Registration Form</h1>
        <p>Scenario A: Dynamic Web Programming with PHP & MySQL</p>
    </header>

    <main>
        <!-- A.01: Form created using HTML best practices -->
        <!-- A.02: Simple text input controls -->
        <!-- A.03: Multi-line text input with textarea -->
        <!-- A.04: Select lists, radio buttons, and checkboxes -->
        <form action="php/process.php" method="POST" novalidate>
            <fieldset>
                <legend>Personal Information</legend>

                <!-- A.02: Simple text inputs -->
                <div class="form-group">
                    <label for="fullname">Full Name:</label>
                    <input type="text" id="fullname" name="fullname" 
                           placeholder="Enter your full name" required
                           maxlength="100" autocomplete="name">
                </div>

                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" 
                           placeholder="example@domain.com" required
                           autocomplete="email">
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="tel" id="phone" name="phone" 
                           placeholder="+853 1234 5678" pattern="[\+\d\s\-]{8,20}">
                </div>

                <!-- A.04: Select list -->
                <div class="form-group">
                    <label for="course">Enrollment Course:</label>
                    <select id="course" name="course" required>
                        <option value="">-- Please Select --</option>
                        <option value="CISC3003">CISC3003 - Web Programming</option>
                        <option value="CISC3004">CISC3004 - Database Systems</option>
                        <option value="CISC3005">CISC3005 - Software Engineering</option>
                        <option value="CISC3006">CISC3006 - Network Security</option>
                    </select>
                </div>

                <!-- A.04: Radio buttons -->
                <div class="form-group">
                    <fieldset>
                        <legend>Academic Year:</legend>
                        <div class="radio-group">
                            <label>
                                <input type="radio" name="academic_year" value="1" required>
                                Year 1
                            </label>
                            <label>
                                <input type="radio" name="academic_year" value="2">
                                Year 2
                            </label>
                            <label>
                                <input type="radio" name="academic_year" value="3">
                                Year 3
                            </label>
                            <label>
                                <input type="radio" name="academic_year" value="4">
                                Year 4
                            </label>
                        </div>
                    </fieldset>
                </div>

                <!-- A.04: Checkboxes -->
                <div class="form-group">
                    <fieldset>
                        <legend>Preferred Learning Mode:</legend>
                        <div class="checkbox-group">
                            <label>
                                <input type="checkbox" name="learning_mode[]" value="onsite">
                                On-site
                            </label>
                            <label>
                                <input type="checkbox" name="learning_mode[]" value="online">
                                Online
                            </label>
                            <label>
                                <input type="checkbox" name="learning_mode[]" value="hybrid">
                                Hybrid
                            </label>
                            <label>
                                <input type="checkbox" name="learning_mode[]" value="self_paced">
                                Self-paced
                            </label>
                        </div>
                    </fieldset>
                </div>

                <!-- A.03: Multi-line text input with textarea -->
                <div class="form-group">
                    <label for="remarks">Additional Remarks:</label>
                    <textarea id="remarks" name="remarks" rows="5" cols="40" 
                              placeholder="Enter any additional information or questions here..."
                              maxlength="500"></textarea>
                    <small>Maximum 500 characters</small>
                </div>
            </fieldset>

            <div class="form-actions">
                <button type="submit" name="submit">Submit Registration</button>
                <button type="reset">Clear Form</button>
            </div>
        </form>
    </main>

    <footer>
        <p>CISC3003 Web Programming: Wong Pou I  DC226572  2026</p>
    </footer>
</body>
</html>