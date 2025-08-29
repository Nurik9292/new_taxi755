/**
 * Taxi755 Template JavaScript Application
 * Modern ES6+ code for Joomla 5.3.3
 * Integration with SEDI API and modern UX
 */

class TaxiApp {
    constructor() {
        this.sediLoaded = false;
        this.mobileBreakpoint = 768;
        this.init();
    }

    init() {
        this.initDOM();
        this.initEventListeners();
        this.initMobileMenu();
        this.initOrderButtons();
        this.initQuickOrderForm();
        this.initSmoothScroll();
        this.initAnimations();
        this.checkSediStatus();
        
        console.log('TaxiApp initialized successfully');
    }

    initDOM() {
        this.body = document.body;
        this.header = document.querySelector('.header');
        this.navigation = document.querySelector('.navigation');
        this.mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        this.navigationMenu = document.querySelector('.navigation ul');
        this.mobileSidebar = document.querySelector('.mobile-sidebar');
        this.sidebarOverlay = document.querySelector('.sidebar-overlay');
    }

    initEventListeners() {
        // Window resize
        window.addEventListener('resize', this.debounce(() => {
            this.handleResize();
        }, 250));

        // Scroll events
        window.addEventListener('scroll', this.throttle(() => {
            this.handleScroll();
        }, 16));

        // SEDI API ready check
        document.addEventListener('sedi-loaded', () => {
            this.sediLoaded = true;
            console.log('SEDI API loaded successfully');
        });
    }

    initMobileMenu() {
        if (!this.mobileMenuToggle || !this.navigationMenu) return;

        this.mobileMenuToggle.addEventListener('click', (e) => {
            e.preventDefault();
            this.toggleMobileMenu();
        });

        // Close menu on outside click
        document.addEventListener('click', (e) => {
            if (!this.navigation.contains(e.target) && this.navigationMenu.classList.contains('active')) {
                this.closeMobileMenu();
            }
        });

        // Close menu on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.navigationMenu.classList.contains('active')) {
                this.closeMobileMenu();
            }
        });
    }

    toggleMobileMenu() {
        this.navigationMenu.classList.toggle('active');
        this.mobileMenuToggle.classList.toggle('active');
        this.body.classList.toggle('menu-open');
    }

    closeMobileMenu() {
        this.navigationMenu.classList.remove('active');
        this.mobileMenuToggle.classList.remove('active');
        this.body.classList.remove('menu-open');
    }

    initOrderButtons() {
        const orderButtons = document.querySelectorAll('.order-btn');
        
        orderButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.handleTaxiOrder(button);
            });
        });
    }

    handleTaxiOrder(button) {
        const carType = button.dataset.carType || 'comfort';
        const destination = button.dataset.destination || '';
        const direction = button.dataset.direction || 'to';
        
        // Show loading state
        button.classList.add('loading');
        button.disabled = true;
        
        // Try SEDI API first
        if (this.sediLoaded && window.SeDi_) {
            this.orderViaSedi(carType, destination, direction, button);
        } else {
            // Fallback to phone call
            this.fallbackToPhone(button);
        }
    }

    orderViaSedi(carType, destination, direction, button) {
        try {
            // SEDI API integration
            if (window.SeDi_ && typeof window.SeDi_.order === 'function') {
                const orderData = {
                    car_type: carType,
                    destination: destination,
                    direction: direction,
                    phone: '',
                    address_from: '',
                    address_to: destination
                };

                window.SeDi_.order(orderData)
                    .then(response => {
                        this.handleOrderSuccess(response, button);
                    })
                    .catch(error => {
                        console.error('SEDI order error:', error);
                        this.fallbackToPhone(button);
                    });
            } else {
                this.fallbackToPhone(button);
            }
        } catch (error) {
            console.error('SEDI integration error:', error);
            this.fallbackToPhone(button);
        }
    }

    fallbackToPhone(button) {
        // Remove loading state
        button.classList.remove('loading');
        button.disabled = false;

        // Open phone dialer
        const phone = '+79266410896';
        window.location.href = `tel:${phone}`;

        // Show notification
        this.showNotification('Для заказа позвоните диспетчеру', 'info');
    }

    handleOrderSuccess(response, button) {
        // Remove loading state
        button.classList.remove('loading');
        button.disabled = false;

        // Show success message
        this.showNotification('Заказ принят! Ожидайте звонка водителя.', 'success');
        
        // Analytics tracking (if available)
        if (typeof gtag !== 'undefined') {
            gtag('event', 'taxi_order', {
                'car_type': button.dataset.carType,
                'destination': button.dataset.destination
            });
        }
    }

    initQuickOrderForm() {
        const quickOrderForm = document.querySelector('.quick-order-form');
        if (!quickOrderForm) return;

        quickOrderForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleQuickOrder(quickOrderForm);
        });

        // Input validation
        const inputs = quickOrderForm.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('blur', () => this.validateField(input));
        });
    }

    handleQuickOrder(form) {
        const formData = new FormData(form);
        const orderData = {
            from_address: formData.get('from_address'),
            to_address: formData.get('to_address'),
            car_class: formData.get('car_class'),
            phone: formData.get('phone')
        };

        // Validate form
        if (!this.validateQuickOrderForm(orderData)) {
            return;
        }

        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;

        // Try SEDI API or fallback
        if (this.sediLoaded && window.SeDi_) {
            this.submitOrderViaSedi(orderData, submitBtn);
        } else {
            this.submitOrderViaPhone(orderData, submitBtn);
        }
    }

    validateQuickOrderForm(data) {
        const errors = [];

        if (!data.from_address || data.from_address.length < 3) {
            errors.push('Укажите адрес отправления');
        }

        if (!data.to_address || data.to_address.length < 3) {
            errors.push('Укажите адрес назначения');
        }

        if (!data.car_class) {
            errors.push('Выберите класс автомобиля');
        }

        if (!data.phone || !this.validatePhone(data.phone)) {
            errors.push('Укажите корректный номер телефона');
        }

        if (errors.length > 0) {
            this.showNotification(errors.join('. '), 'error');
            return false;
        }

        return true;
    }

    validatePhone(phone) {
        const phoneRegex = /^[\+]?[7|8][-\s\(\)]?[0-9]{3}[-\s\(\)]?[0-9]{3}[-\s]?[0-9]{2}[-\s]?[0-9]{2}$/;
        return phoneRegex.test(phone.replace(/\s+/g, ''));
    }

    validateField(field) {
        field.classList.remove('error');
        
        if (field.hasAttribute('required') && !field.value.trim()) {
            field.classList.add('error');
            return false;
        }

        if (field.type === 'tel' && field.value && !this.validatePhone(field.value)) {
            field.classList.add('error');
            return false;
        }

        return true;
    }

    submitOrderViaSedi(data, button) {
        try {
            window.SeDi_.createOrder({
                from: data.from_address,
                to: data.to_address,
                car_type: data.car_class,
                phone: data.phone,
                comment: `Заказ через сайт taxi755.ru - класс ${data.car_class}`
            }).then(response => {
                this.handleQuickOrderSuccess(response, button);
            }).catch(error => {
                console.error('SEDI quick order error:', error);
                this.submitOrderViaPhone(data, button);
            });
        } catch (error) {
            console.error('SEDI quick order exception:', error);
            this.submitOrderViaPhone(data, button);
        }
    }

    submitOrderViaPhone(data, button) {
        // Remove loading state
        button.classList.remove('loading');
        button.disabled = false;

        // Generate SMS/WhatsApp message
        const message = `Заказ такси:
От: ${data.from_address}
До: ${data.to_address}
Класс: ${data.car_class}
Телефон: ${data.phone}`;

        // Try to send via available messengers
        const phone = '+79266410896';
        
        if (this.isMobile()) {
            // Mobile: try WhatsApp first, then SMS
            const whatsappUrl = `whatsapp://send?phone=${phone.replace(/\D/g, '')}&text=${encodeURIComponent(message)}`;
            const smsUrl = `sms:${phone}?body=${encodeURIComponent(message)}`;
            
            window.location.href = whatsappUrl;
            
            // Fallback to SMS after delay
            setTimeout(() => {
                window.location.href = smsUrl;
            }, 1000);
        } else {
            // Desktop: show phone number
            this.showOrderDialog(message, phone);
        }
    }

    showOrderDialog(message, phone) {
        const dialog = document.createElement('div');
        dialog.className = 'order-dialog';
        dialog.innerHTML = `
            <div class="dialog-content">
                <div class="dialog-header">
                    <h3>Ваш заказ готов</h3>
                    <button class="dialog-close">&times;</button>
                </div>
                <div class="dialog-body">
                    <p>Позвоните нам для подтверждения заказа:</p>
                    <div class="dialog-phone">
                        <a href="tel:${phone}" class="btn btn-primary btn-lg">${phone}</a>
                    </div>
                    <div class="order-details">
                        <pre>${message}</pre>
                    </div>
                    <p><small>Или скопируйте детали заказа для удобства</small></p>
                </div>
            </div>
            <div class="dialog-overlay"></div>
        `;

        document.body.appendChild(dialog);

        // Event listeners for dialog
        const closeBtn = dialog.querySelector('.dialog-close');
        const overlay = dialog.querySelector('.dialog-overlay');
        
        closeBtn.addEventListener('click', () => this.closeOrderDialog(dialog));
        overlay.addEventListener('click', () => this.closeOrderDialog(dialog));

        // Auto-close after 30 seconds
        setTimeout(() => {
            if (document.contains(dialog)) {
                this.closeOrderDialog(dialog);
            }
        }, 30000);
    }

    closeOrderDialog(dialog) {
        dialog.classList.add('closing');
        setTimeout(() => {
            if (document.contains(dialog)) {
                document.body.removeChild(dialog);
            }
        }, 300);
    }

    handleQuickOrderSuccess(response, button) {
        // Remove loading state
        button.classList.remove('loading');
        button.disabled = false;

        // Reset form
        const form = button.closest('form');
        if (form) {
            form.reset();
        }

        // Show success message
        this.showNotification('Заказ успешно оформлен! Ожидайте звонка водителя через 2-3 минуты.', 'success');

        // Analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'quick_order_success', {
                'car_type': button.dataset.carType || 'unknown'
            });
        }
    }

    initSmoothScroll() {
        const anchors = document.querySelectorAll('a[href^="#"]');
        
        anchors.forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                const targetId = anchor.getAttribute('href').substring(1);
                const target = document.getElementById(targetId);
                
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

    initAnimations() {
        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fadeInUp');
                }
            });
        }, observerOptions);

        // Observe elements for animation
        const elementsToAnimate = document.querySelectorAll(
            '.service-card, .feature-item, .direction-card, .tariff-block'
        );
        
        elementsToAnimate.forEach(el => observer.observe(el));
    }

    handleScroll() {
        const scrollY = window.scrollY;
        
        // Header background on scroll
        if (scrollY > 50) {
            this.header.classList.add('scrolled');
        } else {
            this.header.classList.remove('scrolled');
        }
    }

    handleResize() {
        const width = window.innerWidth;
        
        // Close mobile menu on desktop
        if (width > this.mobileBreakpoint && this.navigationMenu.classList.contains('active')) {
            this.closeMobileMenu();
        }
    }

    checkSediStatus() {
        // Check SEDI API availability
        let attempts = 0;
        const maxAttempts = 10;
        
        const checkSedi = () => {
            attempts++;
            
            if (window.SeDi_ && typeof window.SeDi_ === 'object') {
                this.sediLoaded = true;
                console.log('SEDI API detected');
                document.dispatchEvent(new CustomEvent('sedi-loaded'));
                return;
            }
            
            if (attempts < maxAttempts) {
                setTimeout(checkSedi, 500);
            } else {
                console.warn('SEDI API not loaded after 5 seconds - using fallback');
            }
        };
        
        // Start checking after initial page load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                setTimeout(checkSedi, 1000);
            });
        } else {
            setTimeout(checkSedi, 1000);
        }
    }

    showNotification(message, type = 'info') {
        // Remove existing notifications
        const existing = document.querySelector('.taxi-notification');
        if (existing) {
            existing.remove();
        }

        // Create notification
        const notification = document.createElement('div');
        notification.className = `taxi-notification alert alert-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-message">${message}</span>
                <button class="notification-close">&times;</button>
            </div>
        `;

        // Insert at top of page
        document.body.insertBefore(notification, document.body.firstChild);

        // Auto-hide after 5 seconds
        setTimeout(() => {
            notification.classList.add('hiding');
            setTimeout(() => {
                if (document.contains(notification)) {
                    notification.remove();
                }
            }, 300);
        }, 5000);

        // Close button
        const closeBtn = notification.querySelector('.notification-close');
        closeBtn.addEventListener('click', () => {
            notification.remove();
        });
    }

    // Utility functions
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    isMobile() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }

    // Price calculator
    calculatePrice(distance, carType, timeOfDay) {
        const basePrices = {
            economy: 25,
            comfort: 35,
            business: 50,
            minivan: 40
        };

        let price = basePrices[carType] * distance;

        // Night tariff (23:00 - 06:00)
        const hour = new Date().getHours();
        if (hour >= 23 || hour < 6) {
            price *= 1.2;
        }

        // Weekend tariff (Friday 18:00 - Sunday 23:59)
        const day = new Date().getDay();
        const isWeekend = day === 0 || day === 6 || (day === 5 && hour >= 18);
        if (isWeekend) {
            price *= 1.1;
        }

        return Math.round(price);
    }

    // Airport-specific pricing
    getAirportPrice(airport, carType) {
        const airportPrices = {
            domodedovo: {
                economy: 800,
                comfort: 1200,
                business: 1800,
                minivan: 1400
            },
            sheremetyevo: {
                economy: 900,
                comfort: 1300,
                business: 1900,
                minivan: 1500
            },
            vnukovo: {
                economy: 700,
                comfort: 1100,
                business: 1700,
                minivan: 1300
            },
            zhukovsky: {
                economy: 1200,
                comfort: 1600,
                business: 2200,
                minivan: 1800
            }
        };

        return airportPrices[airport]?.[carType] || 1000;
    }

    // Update prices on page
    updatePricesOnPage() {
        const priceElements = document.querySelectorAll('[data-price-airport][data-price-class]');
        
        priceElements.forEach(element => {
            const airport = element.dataset.priceAirport;
            const carClass = element.dataset.priceClass;
            const price = this.getAirportPrice(airport, carClass);
            
            element.textContent = `от ${price}₽`;
        });
    }

    // Initialize price updater
    initPriceUpdater() {
        // Update prices when SEDI is ready
        document.addEventListener('sedi-loaded', () => {
            this.updatePricesOnPage();
        });

        // Update prices every 5 minutes
        setInterval(() => {
            this.updatePricesOnPage();
        }, 300000);
    }
}

// Additional CSS for notifications and dialogs
const additionalStyles = `
    <style>
    .taxi-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1001;
        max-width: 400px;
        border-radius: var(--element-radius);
        box-shadow: var(--shadow-medium);
        animation: slideInRight 0.3s ease;
    }

    .taxi-notification.hiding {
        animation: slideOutRight 0.3s ease forwards;
    }

    .notification-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
    }

    .notification-close {
        background: none;
        border: none;
        font-size: 1.25rem;
        cursor: pointer;
        color: inherit;
        opacity: 0.7;
    }

    .notification-close:hover {
        opacity: 1;
    }

    .order-dialog {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1002;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease;
    }

    .order-dialog.closing {
        animation: fadeOut 0.3s ease forwards;
    }

    .dialog-content {
        background: var(--white);
        border-radius: var(--card-radius);
        box-shadow: var(--shadow-medium);
        max-width: 500px;
        width: 90vw;
        max-height: 80vh;
        overflow-y: auto;
        position: relative;
        z-index: 1003;
        animation: scaleIn 0.3s ease;
    }

    .dialog-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid var(--border-gray);
        background: var(--light-gray);
    }

    .dialog-body {
        padding: 1.5rem;
        text-align: center;
    }

    .dialog-phone {
        margin: 1.5rem 0;
    }

    .order-details {
        background: var(--light-gray);
        padding: 1rem;
        border-radius: var(--element-radius);
        margin: 1rem 0;
        text-align: left;
    }

    .order-details pre {
        margin: 0;
        font-family: var(--font-family);
        white-space: pre-wrap;
        font-size: 0.875rem;
    }

    .dialog-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 1002;
    }

    .dialog-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--dark-gray);
    }

    /* Form validation styles */
    input.error,
    select.error {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220,53,69,.25);
    }

    /* Header scroll effect */
    .header.scrolled {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        box-shadow: var(--shadow-light);
    }

    /* Animations */
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Mobile menu animation */
    .mobile-menu-toggle.active span:nth-child(1) {
        transform: rotate(-45deg) translate(-5px, 6px);
    }

    .mobile-menu-toggle.active span:nth-child(2) {
        opacity: 0;
    }

    .mobile-menu-toggle.active span:nth-child(3) {
        transform: rotate(45deg) translate(-5px, -6px);
    }

    /* Navigation mobile styles */
    @media (max-width: 768px) {
        .navigation ul {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--dark-gray);
            flex-direction: column;
            display: none;
            box-shadow: var(--shadow-medium);
        }

        .navigation ul.active {
            display: flex;
            animation: slideDown 0.3s ease;
        }

        .navigation li {
            border-bottom: 1px solid #555;
        }

        .navigation li:last-child {
            border-bottom: none;
        }

        .mobile-menu-toggle {
            display: flex;
        }
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    </style>
`;

// Insert additional styles
if (document.head) {
    document.head.insertAdjacentHTML('beforeend', additionalStyles);
}

// Initialize TaxiApp when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.taxiApp = new TaxiApp();
    });
} else {
    window.taxiApp = new TaxiApp();
}

// Global utility functions
window.TaxiUtils = {
    formatPhone: (phone) => {
        const cleaned = phone.replace(/\D/g, '');
        const match = cleaned.match(/^(\d{1})(\d{3})(\d{3})(\d{2})(\d{2})$/);
        if (match) {
            return `+${match[1]} (${match[2]}) ${match[3]}-${match[4]}-${match[5]}`;
        }
        return phone;
    },

    formatPrice: (price) => {
        return new Intl.NumberFormat('ru-RU', {
            style: 'currency',
            currency: 'RUB',
            minimumFractionDigits: 0
        }).format(price);
    },

    getDistanceToAirport: (airport) => {
        const distances = {
            domodedovo: 42,
            sheremetyevo: 35,
            vnukovo: 28,
            zhukovsky: 65
        };
        return distances[airport] || 40;
    },

    getTravelTime: (airport) => {
        const times = {
            domodedovo: 45,
            sheremetyevo: 40,
            vnukovo: 35,
            zhukovsky: 70
        };
        return times[airport] || 45;
    }
};

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = TaxiApp;
}