// script.js
// JavaScript for multi-page personal website functionality

document.addEventListener('DOMContentLoaded', function() {
    // Mobile navigation functionality
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileNavClose = document.getElementById('mobileNavClose');
    const mobileNav = document.getElementById('mobileNav');
    const mobileNavOverlay = document.getElementById('mobileNavOverlay');
    const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
    
    // Open mobile navigation
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileNav.classList.add('active');
            mobileNavOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }
    
    // Close mobile navigation
    function closeMobileNav() {
        mobileNav.classList.remove('active');
        mobileNavOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    if (mobileNavClose) {
        mobileNavClose.addEventListener('click', closeMobileNav);
    }
    
    if (mobileNavOverlay) {
        mobileNavOverlay.addEventListener('click', closeMobileNav);
    }
    
    // Close mobile navigation when a link is clicked
    mobileNavLinks.forEach(link => {
        link.addEventListener('click', closeMobileNav);
    });
    
    // Smooth scrolling for section links within pages
    const sectionLinks = document.querySelectorAll('a[href^="#"]');
    
    sectionLinks.forEach(link => {
        // Skip links that point to different pages
        if (link.getAttribute('href').includes('.html')) return;
        
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                const headerHeight = document.querySelector('header').offsetHeight;
                const targetPosition = targetElement.offsetTop - headerHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Form submission (demo functionality)
    const messageForm = document.getElementById('messageForm');
    
    if (messageForm) {
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form values
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const subject = document.getElementById('subject').value;
            const message = document.getElementById('message').value;
            
            // In a real implementation, you would send this data to a server
            // For this demo, we'll just show an alert
            alert(`Thank you for your message, ${name}! This is a demonstration form. In a live website, your message would be sent to my email.`);
            
            // Reset form
            messageForm.reset();
        });
    }
    
    // Add active class to navigation links based on current page
    function updateActiveNavLink() {
        const currentPage = window.location.pathname.split('/').pop() || 'cisc3003-SugEx04-home.html';
        const navLinks = document.querySelectorAll('.desktop-nav a, .mobile-nav a');
        
        navLinks.forEach(link => {
            const linkPage = link.getAttribute('href');
            
            // Remove active class from all links
            link.classList.remove('active');
            
            // Add active class to current page link
            if (linkPage === currentPage) {
                link.classList.add('active');
            }
            
            // Special case for cisc3003-SugEx04-home.html (home page)
            if (currentPage === '' || currentPage === 'cisc3003-SugEx04-home.html') {
                if (linkPage === 'cisc3003-SugEx04-home.html' || linkPage === '') {
                    link.classList.add('active');
                }
            }
        });
    }
    
    // Initialize active link on page load
    updateActiveNavLink();
    
    // Add fade-in animation to elements as they scroll into view
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
                // Stop observing after animation is applied
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observe elements with animation classes
    document.querySelectorAll('.about-card, .value-card, .featured-card, .goal-item, .achievement-item, .education-item, .service-item').forEach(element => {
        element.classList.add('fade-out');
        observer.observe(element);
    });
    
    // Initialize tooltips for social icons
    const socialIcons = document.querySelectorAll('.social-icons a');
    socialIcons.forEach(icon => {
        const title = icon.getAttribute('title');
        if (title) {
            icon.setAttribute('aria-label', title);
        }
    });
    
    // Handle download button clicks
    const downloadButtons = document.querySelectorAll('a[href$=".pdf"]');
    downloadButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const fileName = this.getAttribute('href').split('/').pop();
            console.log(`Downloading ${fileName}...`);
            // In a real implementation, you might want to track downloads
        });
    });
    
    // Progress bar animation
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0';
        
        setTimeout(() => {
            bar.style.width = width;
        }, 300);
    });
});