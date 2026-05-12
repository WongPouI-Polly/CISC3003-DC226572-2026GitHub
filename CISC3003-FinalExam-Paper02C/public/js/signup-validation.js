/**
 * CISC3003-FinalExam-Paper02C
 * Scenario C.05: Client-side JavaScript validation for signup
 * Student: Wong Pou I (DC226572)
 */

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('signupForm');
    if (!form) return;

    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');

    // ============ C.05: Browser Validation Functions ============

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

    // Validate Username (C.05)
    function validateUsername() {
        const value = usernameInput.value.trim();
        if (value === '') {
            showError(usernameInput, 'Username is required.');
            return false;
        } else if (value.length < 3) {
            showError(usernameInput, 'Username must be at least 3 characters.');
            return false;
        } else if (value.length > 50) {
            showError(usernameInput, 'Username must not exceed 50 characters.');
            return false;
        } else if (!/^[a-zA-Z0-9_]+$/.test(value)) {
            showError(usernameInput, 'Username can only contain letters, numbers, and underscores.');
            return false;
        }
        showSuccess(usernameInput);
        return true;
    }

    // Validate Email (C.05)
    function validateEmail() {
        const value = emailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (value === '') {
            showError(emailInput, 'Email is required.');
            return false;
        } else if (!emailRegex.test(value)) {
            showError(emailInput, 'Please enter a valid email address.');
            return false;
        } else if (value.length > 255) {
            showError(emailInput, 'Email must not exceed 255 characters.');
            return false;
        }
        showSuccess(emailInput);
        return true;
    }

    // Validate Password (C.05)
    function validatePassword() {
        const value = passwordInput.value;
        if (value === '') {
            showError(passwordInput, 'Password is required.');
            return false;
        } else if (value.length < 8) {
            showError(passwordInput, 'Password must be at least 8 characters.');
            return false;
        } else if (!/(?=.*[a-z])/.test(value)) {
            showError(passwordInput, 'Password must contain at least one lowercase letter.');
            return false;
        } else if (!/(?=.*[A-Z])/.test(value)) {
            showError(passwordInput, 'Password must contain at least one uppercase letter.');
            return false;
        } else if (!/(?=.*\d)/.test(value)) {
            showError(passwordInput, 'Password must contain at least one number.');
            return false;
        } else if (!/(?=.*[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?])/.test(value)) {
            showError(passwordInput, 'Password must contain at least one special character.');
            return false;
        }
        showSuccess(passwordInput);
        // Re-validate confirm password if it has been touched
        if (confirmPasswordInput.value !== '') {
            validateConfirmPassword();
        }
        return true;
    }

    // Validate Confirm Password (C.05)
    function validateConfirmPassword() {
        const value = confirmPasswordInput.value;
        if (value === '') {
            showError(confirmPasswordInput, 'Please confirm your password.');
            return false;
        } else if (value !== passwordInput.value) {
            showError(confirmPasswordInput, 'Passwords do not match.');
            return false;
        }
        showSuccess(confirmPasswordInput);
        return true;
    }

    // ============ Event Listeners ============

    usernameInput.addEventListener('blur', validateUsername);
    usernameInput.addEventListener('input', function() {
        if (usernameInput.classList.contains('invalid') || usernameInput.classList.contains('valid')) {
            validateUsername();
        }
    });

    emailInput.addEventListener('blur', validateEmail);
    emailInput.addEventListener('input', function() {
        if (emailInput.classList.contains('invalid') || emailInput.classList.contains('valid')) {
            validateEmail();
        }
        // Reset AJAX status when email changes
        const ajaxStatus = document.getElementById('email-ajax-status');
        if (ajaxStatus) {
            ajaxStatus.textContent = '';
            ajaxStatus.className = '';
        }
    });

    passwordInput.addEventListener('blur', validatePassword);
    passwordInput.addEventListener('input', function() {
        if (passwordInput.classList.contains('invalid') || passwordInput.classList.contains('valid')) {
            validatePassword();
        }
    });

    confirmPasswordInput.addEventListener('blur', validateConfirmPassword);
    confirmPasswordInput.addEventListener('input', function() {
        if (confirmPasswordInput.classList.contains('invalid') || confirmPasswordInput.classList.contains('valid')) {
            validateConfirmPassword();
        }
    });

    // Form submission
    form.addEventListener('submit', function(event) {
        const isUsernameValid = validateUsername();
        const isEmailValid = validateEmail();
        const isPasswordValid = validatePassword();
        const isConfirmValid = validateConfirmPassword();

        if (!isUsernameValid || !isEmailValid || !isPasswordValid || !isConfirmValid) {
            event.preventDefault();
            const firstInvalid = form.querySelector('.invalid');
            if (firstInvalid) {
                firstInvalid.focus();
            }
        }
    });

    // Password strength indicator (optional enhancement)
    const passwordStrengthIndicator = document.createElement('div');
    passwordStrengthIndicator.id = 'password-strength';
    passwordStrengthIndicator.style.cssText = 'margin-top: 0.3rem; font-size: 0.85rem;';
    passwordInput.parentNode.appendChild(passwordStrengthIndicator);

    passwordInput.addEventListener('input', function() {
        const value = passwordInput.value;
        let strength = 0;
        let message = '';
        let color = '';

        if (value.length >= 8) strength++;
        if (/[a-z]/.test(value)) strength++;
        if (/[A-Z]/.test(value)) strength++;
        if (/\d/.test(value)) strength++;
        if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(value)) strength++;

        switch (strength) {
            case 0:
            case 1:
                message = 'Weak password';
                color = '#dc3545';
                break;
            case 2:
                message = 'Fair password';
                color = '#ffc107';
                break;
            case 3:
                message = 'Good password';
                color = '#fd7e14';
                break;
            case 4:
                message = 'Strong password';
                color = '#198754';
                break;
            case 5:
                message = 'Very strong password';
                color = '#0d6efd';
                break;
        }

        if (value === '') {
            message = '';
        }

        passwordStrengthIndicator.textContent = message;
        passwordStrengthIndicator.style.color = color;
    });
});