/* add code here  */

// Wait for the DOM to be fully loaded before setting up event handlers
document.addEventListener('DOMContentLoaded', function() {
    
    // Get all elements with the 'hilightable' class
    var hilightableElements = document.querySelectorAll('.hilightable');
    
    // Add focus and blur event handlers to each hilightable element
    for (var i = 0; i < hilightableElements.length; i++) {
        var element = hilightableElements[i];
        
        // Add focus event handler
        element.addEventListener('focus', function() {
            this.classList.add('highlight');
        });
        
        // Add blur event handler
        element.addEventListener('blur', function() {
            this.classList.remove('highlight');
        });
    }
    
    // Get the form element
    var form = document.getElementById('mainForm');
    
    // Add submit event handler
    form.addEventListener('submit', function(event) {
        
        // Get all required elements
        var requiredElements = document.querySelectorAll('.required');
        var hasError = false;
        
        // Check each required element
        for (var i = 0; i < requiredElements.length; i++) {
            var element = requiredElements[i];
            
            // Check if the element is empty (value is null or empty string)
            if (!element.value || element.value.trim() === '') {
                // Add error class
                element.classList.add('error');
                hasError = true;
                
                // Add input event handler to remove error class when user types
                element.addEventListener('input', function removeError() {
                    if (this.value && this.value.trim() !== '') {
                        this.classList.remove('error');
                        // Remove this event listener after it's been used
                        this.removeEventListener('input', removeError);
                    }
                });
            } else {
                // Remove error class if element has content
                element.classList.remove('error');
            }
        }
        
        // If there are errors, prevent form submission
        if (hasError) {
            event.preventDefault();
        }
    });
});