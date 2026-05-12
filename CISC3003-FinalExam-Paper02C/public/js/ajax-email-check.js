/**
 * CISC3003-FinalExam-Paper02C
 * Scenario C.06: AJAX Email Validation
 * Student: Wong Pou I (DC226572)
 * 
 * Sends an asynchronous request to check if email already exists
 */

document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('email');
    const ajaxStatus = document.getElementById('email-ajax-status');
    const submitButton = document.querySelector('button[type="submit"]');

    if (!emailInput || !ajaxStatus) return;

    let debounceTimer;

    emailInput.addEventListener('blur', function() {
        checkEmailAvailability(emailInput.value.trim());
    });

    // Optional: check on input with debounce
    emailInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const email = emailInput.value.trim();
        
        // Basic email format check before AJAX
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            ajaxStatus.textContent = '';
            ajaxStatus.className = '';
            return;
        }

        debounceTimer = setTimeout(function() {
            checkEmailAvailability(email);
        }, 800); // Debounce 800ms
    });

    /**
     * C.06: AJAX request to validate email
     */
    function checkEmailAvailability(email) {
        // Skip if email is empty or invalid format
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email || !emailRegex.test(email)) {
            return;
        }

        // Show checking status
        ajaxStatus.textContent = 'Checking email availability...';
        ajaxStatus.className = 'checking';

        // Create AJAX request
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'php/check-email.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        
                        if (response.status === 'available') {
                            ajaxStatus.textContent = '✓ ' + response.message;
                            ajaxStatus.className = 'available';
                            emailInput.classList.add('valid');
                            emailInput.classList.remove('invalid');
                            if (submitButton) submitButton.disabled = false;
                        } else if (response.status === 'unavailable') {
                            ajaxStatus.textContent = '✗ ' + response.message;
                            ajaxStatus.className = 'unavailable';
                            emailInput.classList.add('invalid');
                            emailInput.classList.remove('valid');
                            if (submitButton) submitButton.disabled = true;
                        } else {
                            ajaxStatus.textContent = response.message || 'Error checking email.';
                            ajaxStatus.className = '';
                        }
                    } catch (e) {
                        console.error('AJAX response parse error:', e);
                        ajaxStatus.textContent = 'Error checking email availability.';
                        ajaxStatus.className = '';
                    }
                } else {
                    console.error('AJAX request failed with status:', xhr.status);
                    ajaxStatus.textContent = 'Server error. Please try again.';
                    ajaxStatus.className = '';
                }
            }
        };

        xhr.onerror = function() {
            console.error('AJAX network error');
            ajaxStatus.textContent = 'Network error. Please check your connection.';
            ajaxStatus.className = '';
        };

        // Send the request with the email parameter
        xhr.send('email=' + encodeURIComponent(email));
    }
});