/**
 * Taxi755 Template JavaScript
 * Modern interactions for Joomla 5.3.3 template
 */

document.addEventListener('DOMContentLoaded', function() {

    // Initialize all template functions
    initSmoothScrolling();
    initMobileMenu();
    initOrderFunctions();
    initAnimations();
    initContactForms();

});

/**
 * Smooth scrolling for anchor links
 */
function initSmoothScrolling() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

/**
 * Mobile menu functionality
 */
function initMobileMenu() {
    const nav = document.querySelector('.nav');
    const menu = document.querySelector('.nav ul.menu');

    if (nav && menu && window.innerWidth <= 768) {
        // Create mobile menu toggle
        const toggleBtn = document.createElement('button');
        toggleBtn.className = 'mobile-menu-toggle';
        toggleBtn.innerHTML = '☰';
        toggleBtn.style.cssText = `
            background: #FFD700;
            border: none;
            color: #333;
            font-size: 1.5rem;
            padding: 0.5rem;
            border-radius: 5px;
            cursor: pointer;
            margin: 0 auto;
            display: block;
        `;

        // Insert toggle before menu
        nav.querySelector('.container').insertBefore(toggleBtn, menu);

        // Hide menu initially on mobile
        menu.style.display = 'none';

        // Toggle functionality
        toggleBtn.addEventListener('click', function() {
            if (menu.style.display === 'none') {
                menu.style.display = 'flex';
                menu.style.flexDirection = 'column';
                toggleBtn.innerHTML = '✕';
            } else {
                menu.style.display = 'none';
                toggleBtn.innerHTML = '☰';
            }
        });
    }
}

/**
 * Order taxi functions
 */
function initOrderFunctions() {
    // Order buttons functionality
    document.querySelectorAll('.order-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const carType = this.getAttribute('data-car-type') || 'unknown';
            orderTaxi(carType);
        });
    });
}

/**
 * Order taxi function with SEDI integration
 */
function orderTaxi(type) {
    console.log('Ordering taxi type:', type);

    // Check if SEDI is available
    if (typeof window.SeDi !== 'undefined' && window.SeDi) {
        try {
            // Use SEDI API for ordering
            window.SeDi.openOrderForm({
                carClass: type,
                defaultFrom: '',
                defaultTo: ''
            });
        } catch (error) {
            console.error('SEDI integration error:', error);
            fallbackOrderMethod(type);
        }
    } else {
        // Fallback to phone or form
        fallbackOrderMethod(type);
    }
}

/**
 * Fallback order method if SEDI is not available
 */
function fallbackOrderMethod(type) {
    const phone = '+7 (926) 641-08-96';
    const message = `Здравствуйте! Хочу заказать такси класса "${type}".`;

    if (confirm(`Заказать такси класса "${type}"?\n\nМы перенаправим вас на звонок диспетчеру.`)) {
        window.location.href = `tel:${phone.replace(/[^0-9+]/g, '')}`;
    }
}

/**
 * Initialize scroll animations
 */
function initAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Animate service cards and feature items
    document.querySelectorAll('.service-card, .feature-item').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
}

/**
 * Initialize contact forms
 */
function initContactForms() {
    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Basic form validation
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.style.borderColor = '#dc3545';
                    isValid = false;
                } else {
                    field.style.borderColor = '#28a745';
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Пожалуйста, заполните все обязательные поля.');
            }
        });
    });
}

/**
 * Phone number formatting
 */
function formatPhoneNumber(input) {
    let value = input.value.replace(/\D/g, '');

    if (value.startsWith('7')) {
        value = '+7 (' + value.substring(1, 4) + ') ' +
            value.substring(4, 7) + '-' +
            value.substring(7, 9) + '-' +
            value.substring(9, 11);
    }

    input.value = value;
}

/**
 * Add phone formatting to phone inputs
 */
document.addEventListener('DOMContentLoaded', function() {
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function() {
            formatPhoneNumber(this);
        });
    });
});

/**
 * Back to top functionality
 */
function initBackToTop() {
    // Create back to top button
    const backToTop = document.createElement('button');
    backToTop.innerHTML = '↑';
    backToTop.className = 'back-to-top';
    backToTop.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #FFD700;
        color: #333;
        border: none;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        font-size: 1.5rem;
        cursor: pointer;
        display: none;
        z-index: 1000;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    `;

    document.body.appendChild(backToTop);

    // Show/hide based on scroll
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTop.style.display = 'block';
        } else {
            backToTop.style.display = 'none';
        }
    });

    // Scroll to top on click
    backToTop.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// Initialize back to top
document.addEventListener('DOMContentLoaded', initBackToTop);

/**
 * Form validation helpers
 */
const ValidationHelpers = {

    isValidEmail: function(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    },

    isValidPhone: function(phone) {
        const re = /^[\+]?[7-8][\s\-]?[\(]?[0-9]{3}[\)]?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/;
        return re.test(phone.replace(/\D/g, ''));
    },

    showError: function(field, message) {
        // Remove existing error
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }

        // Add error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error';
        errorDiv.style.cssText = 'color: #dc3545; font-size: 0.9rem; margin-top: 0.3rem;';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);

        // Style field
        field.style.borderColor = '#dc3545';
    },

    clearError: function(field) {
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }
        field.style.borderColor = '#28a745';
    }
};

/**
 * SEDI Integration helpers
 */
const SediHelpers = {

    checkSediAvailability: function() {
        return new Promise((resolve) => {
            let attempts = 0;
            const maxAttempts = 10;

            const checkInterval = setInterval(() => {
                attempts++;

                if (typeof window.SeDi !== 'undefined' && window.SeDi) {
                    clearInterval(checkInterval);
                    resolve(true);
                } else if (attempts >= maxAttempts) {
                    clearInterval(checkInterval);
                    console.warn('SEDI not loaded after 5 seconds');
                    resolve(false);
                }
            }, 500);
        });
    },

    initSedi: function() {
        this.checkSediAvailability().then(isAvailable => {
            if (isAvailable) {
                console.log('SEDI integration ready');
                // Additional SEDI setup if needed
            } else {
                console.warn('SEDI integration not available, using fallback methods');
            }
        });
    }
};

// Initialize SEDI when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Wait a bit for SEDI script to load
    setTimeout(() => {
        SediHelpers.initSedi();
    }, 1000);
});