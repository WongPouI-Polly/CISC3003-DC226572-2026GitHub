/**
 * CISC3003-FinalExam-Paper02B
 * Scenario B.01: Client-side form validation
 * Student: Wong Pou I (DC226572)
 */

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const subjectInput = document.getElementById('subject');
    const messageInput = document.getElementById('message');

    // Validation helper functions
    function showError(input, message) {
        const errorSpan = document.getElementById(input.id + '-error');
        if (errorSpan) {
            errorSpan.textContent = message;
            errorSpan.className = 'validation-message error';
        }
        input.classList.add('invalid');
        input.classList.remove('valid');
    }

    function showSuccess(input) {
        const errorSpan = document.getElementById(input.id + '-error');
        if (errorSpan) {
            errorSpan.textContent = '';
            errorSpan.className = 'validation-message success';
        }
        input.classList.remove('invalid');
        input.classList.add('valid');
    }

    // Name validation (B.01: client-side validation)
    function validateName() {
        const value = nameInput.value.trim();
        if (value === '') {
            showError(nameInput, 'Name is required.');
            return false;
        } else if (value.length < 2) {
            showError(nameInput, 'Name must be at least 2 characters.');
            return false;
        } else if (value.length > 100) {
            showError(nameInput, 'Name must not exceed 100 characters.');
            return false;
        } else if (!/^[a-zA-Z\s\-'.]+$/.test(value)) {
            showError(nameInput, 'Name contains invalid characters.');
            return false;
        }
        showSuccess(nameInput);
        return true;
    }

    // Email validation
    function validateEmail() {
        const value = emailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (value === '') {
            showError(emailInput, 'Email is required.');
            return false;
        } else if (!emailRegex.test(value)) {
            showError(emailInput, 'Please enter a valid email address.');
            return false;
        }
        showSuccess(emailInput);
        return true;
    }

    // Subject validation
    function validateSubject() {
        const value = subjectInput.value.trim();
        if (value === '') {
            showError(subjectInput, 'Subject is required.');
            return false;
        } else if (value.length < 3) {
            showError(subjectInput, 'Subject must be at least 3 characters.');
            return false;
        } else if (value.length > 200) {
            showError(subjectInput, 'Subject must not exceed 200 characters.');
            return false;
        }
        showSuccess(subjectInput);
        return true;
    }

    // Message validation
    function validateMessage() {
        const value = messageInput.value.trim();
        if (value === '') {
            showError(messageInput, 'Message is required.');
            return false;
        } else if (value.length < 10) {
            showError(messageInput, 'Message must be at least 10 characters.');
            return false;
        } else if (value.length > 2000) {
            showError(messageInput, 'Message must not exceed 2000 characters.');
            return false;
        }
        showSuccess(messageInput);
        return true;
    }

    // Real-time validation on input/blur
    nameInput.addEventListener('blur', validateName);
    nameInput.addEventListener('input', function() {
        if (nameInput.classList.contains('invalid') || nameInput.classList.contains('valid')) {
            validateName();
        }
    });

    emailInput.addEventListener('blur', validateEmail);
    emailInput.addEventListener('input', function() {
        if (emailInput.classList.contains('invalid') || emailInput.classList.contains('valid')) {
            validateEmail();
        }
    });

    subjectInput.addEventListener('blur', validateSubject);
    subjectInput.addEventListener('input', function() {
        if (subjectInput.classList.contains('invalid') || subjectInput.classList.contains('valid')) {
            validateSubject();
        }
    });

    messageInput.addEventListener('blur', validateMessage);
    messageInput.addEventListener('input', function() {
        if (messageInput.classList.contains('invalid') || messageInput.classList.contains('valid')) {
            validateMessage();
        }
    });

    // Form submission
    form.addEventListener('submit', function(event) {
        const isNameValid = validateName();
        const isEmailValid = validateEmail();
        const isSubjectValid = validateSubject();
        const isMessageValid = validateMessage();

        if (!isNameValid || !isEmailValid || !isSubjectValid || !isMessageValid) {
            event.preventDefault();
            // Scroll to first error
            const firstInvalid = form.querySelector('.invalid');
            if (firstInvalid) {
                firstInvalid.focus();
            }
        }
    });
});