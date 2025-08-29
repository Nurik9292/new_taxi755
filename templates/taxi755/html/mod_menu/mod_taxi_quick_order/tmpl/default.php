<?php
/**
 * @package     mod_taxi_quick_order
 * @subpackage  Templates.taxi755
 * @copyright   (C) 2025 Taxi755. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Uri\Uri;

?>

<div class="taxi-quick-order-module <?php echo $moduleclass_sfx; ?> <?php echo $compactMode ? 'compact-mode' : ''; ?>">

    <?php if ($showTitle) : ?>
        <div class="module-header">
            <h3 class="module-title"><?php echo $customTitle; ?></h3>
            <p class="module-subtitle">Быстрое оформление заказа</p>
        </div>
    <?php endif; ?>

    <form class="quick-order-form" id="quick-order-<?php echo $module->id; ?>" data-module-id="<?php echo $module->id; ?>">
        
        <!-- Address Fields -->
        <div class="form-group address-group">
            <label for="qo-from-<?php echo $module->id; ?>" class="form-label">
                Откуда:
            </label>
            <input type="text" 
                   id="qo-from-<?php echo $module->id; ?>" 
                   name="from_address" 
                   class="form-control address-input"
                   placeholder="Адрес подачи" 
                   required>
            <div class="address-suggestions" id="from-suggestions-<?php echo $module->id; ?>"></div>
        </div>

        <div class="form-group address-group">
            <label for="qo-to-<?php echo $module->id; ?>" class="form-label">
                Куда:
            </label>
            <input type="text" 
                   id="qo-to-<?php echo $module->id; ?>" 
                   name="to_address" 
                   class="form-control address-input"
                   placeholder="<?php echo $defaultDestination ? 'Аэропорт ' . ucfirst($defaultDestination) : 'Адрес назначения'; ?>"
                   value="<?php echo $defaultDestination ? 'Аэропорт ' . ucfirst($defaultDestination) : ''; ?>" 
                   required>
            <div class="address-suggestions" id="to-suggestions-<?php echo $module->id; ?>"></div>
        </div>

        <!-- Car Class Selection -->
        <div class="form-group car-class-group">
            <label class="form-label">Класс автомобиля:</label>
            
            <?php if ($compactMode) : ?>
                <!-- Compact mode: dropdown -->
                <select name="car_class" class="form-control car-class-select" required>
                    <option value="">Выберите класс</option>
                    <option value="economy" <?php echo $defaultCarType === 'economy' ? 'selected' : ''; ?>>
                        Эконом (от 800₽)
                    </option>
                    <option value="comfort" <?php echo $defaultCarType === 'comfort' ? 'selected' : ''; ?>>
                        Комфорт (от 1000₽)
                    </option>
                    <option value="business" <?php echo $defaultCarType === 'business' ? 'selected' : ''; ?>>
                        Бизнес (от 1500₽)
                    </option>
                    <option value="minivan" <?php echo $defaultCarType === 'minivan' ? 'selected' : ''; ?>>
                        Минивэн (от 1200₽)
                    </option>
                </select>
            <?php else : ?>
                <!-- Full mode: visual cards -->
                <div class="car-class-cards">
                    <?php
                    $carClasses = [
                        'economy' => ['name' => 'Эконом', 'price' => 800, 'icon' => '🚗'],
                        'comfort' => ['name' => 'Комфорт', 'price' => 1000, 'icon' => '🚖'],
                        'business' => ['name' => 'Бизнес', 'price' => 1500, 'icon' => '🚘'],
                        'minivan' => ['name' => 'Минивэн', 'price' => 1200, 'icon' => '🚐']
                    ];
                    ?>
                    
                    <?php foreach ($carClasses as $classKey => $classData) : ?>
                        <label class="car-class-option <?php echo $classKey; ?> <?php echo $defaultCarType === $classKey ? 'selected' : ''; ?>">
                            <input type="radio" 
                                   name="car_class" 
                                   value="<?php echo $classKey; ?>" 
                                   <?php echo $defaultCarType === $classKey ? 'checked' : ''; ?> 
                                   required>
                            <div class="class-card">
                                <div class="class-icon"><?php echo $classData['icon']; ?></div>
                                <div class="class-info">
                                    <span class="class-name"><?php echo $classData['name']; ?></span>
                                    <span class="class-price">от <?php echo $classData['price']; ?>₽</span>
                                </div>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Contact Information -->
        <div class="form-group contact-group">
            <label for="qo-phone-<?php echo $module->id; ?>" class="form-label">
                Ваш телефон:
            </label>
            <input type="tel" 
                   id="qo-phone-<?php echo $module->id; ?>" 
                   name="phone" 
                   class="form-control phone-input"
                   placeholder="+7 (___) ___-__-__" 
                   required>
            <small class="form-help">Для связи с водителем</small>
        </div>

        <!-- Additional Options -->
        <?php if (!$compactMode) : ?>
            <div class="form-group options-group">
                <label class="form-label">Дополнительно:</label>
                
                <div class="additional-options">
                    <label class="option-checkbox">
                        <input type="checkbox" name="child_seat" value="1">
                        <span class="option-label">Детское кресло</span>
                        <span class="option-note">бесплатно</span>
                    </label>
                    
                    <label class="option-checkbox">
                        <input type="checkbox" name="animal_transport" value="1">
                        <span class="option-label">Перевозка животных</span>
                        <span class="option-note">+200₽</span>
                    </label>
                    
                    <label class="option-checkbox">
                        <input type="checkbox" name="meet_with_sign" value="1">
                        <span class="option-label">Встреча с табличкой</span>
                        <span class="option-note">+300₽</span>
                    </label>
                </div>
            </div>
        <?php endif; ?>

        <!-- Price Estimation -->
        <?php if ($showPriceEstimate) : ?>
            <div class="price-estimation" id="price-estimate-<?php echo $module->id; ?>" style="display: none;">
                <div class="estimate-content">
                    <div class="estimate-price">
                        <span class="price-label">Примерная стоимость:</span>
                        <span class="price-value" id="estimated-price-<?php echo $module->id; ?>">-</span>₽
                    </div>
                    <div class="estimate-details">
                        <span id="estimated-time-<?php echo $module->id; ?>">-</span> мин, 
                        <span id="estimated-distance-<?php echo $module->id; ?>">-</span> км
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Submit Button -->
        <div class="form-group submit-group">
            <?php if ($enableSedi) : ?>
                <button type="submit" class="btn btn-primary btn-block order-submit">
                    <span class="btn-icon">🚖</span>
                    <span class="btn-text">Заказать такси</span>
                </button>
            <?php else : ?>
                <a href="tel:+79266410896" class="btn btn-primary btn-block">
                    <span class="btn-icon">📞</span>
                    <span class="btn-text">Позвонить диспетчеру</span>
                </a>
            <?php endif; ?>
        </div>

        <!-- Alternative Contact -->
        <div class="alternative-contact">
            <p class="alt-text">Или позвоните диспетчеру:</p>
            <a href="tel:+79266410896" class="phone-link">+7 (926) 641-08-96</a>
            <p class="work-hours">Работаем круглосуточно</p>
        </div>

        <!-- Hidden fields for context -->
        <input type="hidden" name="default_car_type" value="<?php echo $defaultCarType; ?>">
        <input type="hidden" name="default_destination" value="<?php echo $defaultDestination; ?>">
        <input type="hidden" name="module_id" value="<?php echo $module->id; ?>">
        
    </form>

    <!-- Success Message Template -->
    <div class="order-success" id="order-success-<?php echo $module->id; ?>" style="display: none;">
        <div class="success-icon">✅</div>
        <h4>Заказ принят!</h4>
        <p>Водитель свяжется с вами в течение 2-3 минут</p>
        <button class="btn btn-outline btn-sm new-order-btn">Новый заказ</button>
    </div>

</div>

<script>
// Quick Order Module JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const moduleId = '<?php echo $module->id; ?>';
    const form = document.getElementById('quick-order-' + moduleId);
    const priceEstimate = document.getElementById('price-estimate-' + moduleId);
    
    if (!form) return;

    // Initialize phone mask
    const phoneInput = form.querySelector('.phone-input');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            let formattedValue = '';
            
            if (value.length > 0) {
                if (value[0] === '8') {
                    value = '7' + value.slice(1);
                }
                if (value[0] !== '7') {
                    value = '7' + value;
                }
            }
            
            if (value.length >= 1) formattedValue += '+7';
            if (value.length >= 2) formattedValue += ' (' + value.slice(1, 4);
            if (value.length >= 4) formattedValue += ') ' + value.slice(4, 7);
            if (value.length >= 7) formattedValue += '-' + value.slice(7, 9);
            if (value.length >= 9) formattedValue += '-' + value.slice(9, 11);
            
            e.target.value = formattedValue;
        });
    }

    // Car class selection handling
    const classOptions = form.querySelectorAll('input[name="car_class"]');
    classOptions.forEach(option => {
        option.addEventListener('change', function() {
            // Remove selected class from all options
            form.querySelectorAll('.car-class-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            
            // Add selected class to current option
            if (this.checked) {
                this.closest('.car-class-option').classList.add('selected');
                updatePriceEstimate();
            }
        });
    });

    // Address input handling with estimation
    const addressInputs = form.querySelectorAll('.address-input');
    let estimateTimeout;
    
    addressInputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(estimateTimeout);
            estimateTimeout = setTimeout(updatePriceEstimate, 1000);
        });
    });

    // Price estimation function
    function updatePriceEstimate() {
        <?php if ($showPriceEstimate) : ?>
        const fromAddress = form.querySelector('[name="from_address"]').value;
        const toAddress = form.querySelector('[name="to_address"]').value;
        const carClass = form.querySelector('[name="car_class"]:checked')?.value;

        if (fromAddress.length < 3 || toAddress.length < 3 || !carClass) {
            if (priceEstimate) priceEstimate.style.display = 'none';
            return;
        }

        // Simple distance estimation (in real app, use Maps API)
        const estimatedDistance = Math.floor(Math.random() * 25) + 15; // 15-40 km
        const basePrices = {
            economy: 25,
            comfort: 35,
            business: 50,
            minivan: 40
        };
        
        const estimatedPrice = Math.round(basePrices[carClass] * estimatedDistance);
        const estimatedTime = Math.round(estimatedDistance * 2.2); // ~2.2 min per km

        // Update display
        document.getElementById('estimated-price-' + moduleId).textContent = estimatedPrice;
        document.getElementById('estimated-time-' + moduleId).textContent = estimatedTime;
        document.getElementById('estimated-distance-' + moduleId).textContent = estimatedDistance;
        
        if (priceEstimate) {
            priceEstimate.style.display = 'block';
            priceEstimate.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
        <?php endif; ?>
    }

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        submitQuickOrder();
    });

    function submitQuickOrder() {
        const submitBtn = form.querySelector('.order-submit');
        const successDiv = document.getElementById('order-success-' + moduleId);

        if (!validateForm()) {
            return;
        }

        // Show loading state
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;

        const formData = new FormData(form);
        const orderData = {
            from_address: formData.get('from_address'),
            to_address: formData.get('to_address'),
            car_class: formData.get('car_class'),
            phone: formData.get('phone'),
            child_seat: formData.get('child_seat') || 0,
            animal_transport: formData.get('animal_transport') || 0,
            meet_with_sign: formData.get('meet_with_sign') || 0,
            module_source: 'quick_order_' + moduleId
        };

        <?php if ($enableSedi) : ?>
        // Try SEDI API first
        if (window.taxiApp && window.taxiApp.sediLoaded) {
            window.taxiApp.submitOrderViaSedi(orderData, submitBtn)
                .then(response => {
                    showOrderSuccess();
                })
                .catch(error => {
                    console.error('Quick order error:', error);
                    fallbackToPhone();
                });
        } else {
            fallbackToPhone();
        }
        <?php else : ?>
        // Direct phone fallback
        fallbackToPhone();
        <?php endif; ?>

        function showOrderSuccess() {
            form.style.display = 'none';
            if (successDiv) {
                successDiv.style.display = 'block';
            }

            // Reset form after 10 seconds
            setTimeout(() => {
                const newOrderBtn = successDiv.querySelector('.new-order-btn');
                if (newOrderBtn) {
                    newOrderBtn.addEventListener('click', function() {
                        form.style.display = 'block';
                        successDiv.style.display = 'none';
                        form.reset();
                        submitBtn.classList.remove('loading');
                        submitBtn.disabled = false;
                    });
                }
            }, 100);
        }

        function fallbackToPhone() {
            submitBtn.classList.remove('loading');
            submitBtn.disabled = false;

            // Generate order message
            const message = `Заказ такси:
От: ${orderData.from_address}
До: ${orderData.to_address}  
Класс: ${orderData.car_class}
Телефон: ${orderData.phone}
${orderData.child_seat ? 'Детское кресло: да' : ''}
${orderData.animal_transport ? 'Перевозка животных: да' : ''}
${orderData.meet_with_sign ? 'Встреча с табличкой: да' : ''}`;

            if (/Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                // Mobile: try WhatsApp
                const whatsappUrl = `whatsapp://send?phone=79266410896&text=${encodeURIComponent(message)}`;
                window.open(whatsappUrl, '_blank');
                
                setTimeout(() => {
                    window.location.href = 'tel:+79266410896';
                }, 1000);
            } else {
                // Desktop: show phone
                alert('Позвоните диспетчеру для оформления заказа:\n+7 (926) 641-08-96\n\n' + message);
                window.location.href = 'tel:+79266410896';
            }
        }
    }

    function validateForm() {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            field.classList.remove('error');
            
            if (!field.value.trim()) {
                field.classList.add('error');
                isValid = false;
            }
        });

        // Validate phone format
        const phoneField = form.querySelector('.phone-input');
        if (phoneField.value && !isValidPhone(phoneField.value)) {
            phoneField.classList.add('error');
            isValid = false;
        }

        if (!isValid) {
            // Show first error field
            const firstError = form.querySelector('.error');
            if (firstError) {
                firstError.focus();
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        return isValid;
    }

    function isValidPhone(phone) {
        const phoneRegex = /^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/;
        return phoneRegex.test(phone);
    }

    // Auto-complete for addresses (simplified)
    addressInputs.forEach(input => {
        let suggestionsTimeout;
        
        input.addEventListener('input', function() {
            clearTimeout(suggestionsTimeout);
            const suggestionsList = document.getElementById(
                input.name.includes('from') ? 
                'from-suggestions-' + moduleId : 
                'to-suggestions-' + moduleId
            );
            
            if (this.value.length < 3) {
                suggestionsList.innerHTML = '';
                return;
            }
            
            suggestionsTimeout = setTimeout(() => {
                showAddressSuggestions(this.value, suggestionsList);
            }, 300);
        });
    });

    function showAddressSuggestions(query, container) {
        // Popular addresses for quick selection
        const popularAddresses = [
            'Красная площадь',
            'Аэропорт Домодедово', 
            'Аэропорт Шереметьево',
            'Аэропорт Внуково',
            'Ярославский вокзал',
            'Казанский вокзал',
            'Ленинградский вокзал',
            'Московский Кремль',
            'ТЦ Европейский',
            'Парк Горького'
        ];

        const filtered = popularAddresses.filter(addr => 
            addr.toLowerCase().includes(query.toLowerCase())
        ).slice(0, 5);

        if (filtered.length > 0) {
            container.innerHTML = filtered.map(addr => 
                `<div class="suggestion-item" data-address="${addr}">${addr}</div>`
            ).join('');
            
            // Add click handlers
            container.querySelectorAll('.suggestion-item').forEach(item => {
                item.addEventListener('click', function() {
                    const targetInput = container.id.includes('from') ? 
                        form.querySelector('[name="from_address"]') :
                        form.querySelector('[name="to_address"]');
                    
                    targetInput.value = this.dataset.address;
                    container.innerHTML = '';
                    updatePriceEstimate();
                });
            });
        } else {
            container.innerHTML = '';
        }
    }
});
</script>

<style>
/* Quick Order Module Styles */
.taxi-quick-order-module {
    background: linear-gradient(135deg, #FFFFFF 0%, #FFFBF0 100%);
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border-left: 5px solid #FFD700;
    position: sticky;
    top: 120px;
}

.taxi-quick-order-module.compact-mode {
    padding: 1rem;
}

.module-header {
    text-align: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #f0f0f0;
}

.module-title {
    color: #333;
    margin-bottom: 0.5rem;
    font-size: 1.25rem;
}

.module-subtitle {
    color: #666;
    font-size: 0.9rem;
    margin: 0;
}

.quick-order-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    font-weight: 500;
    color: #333;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.form-control {
    padding: 0.75rem;
    border: 1px solid #E5E5E5;
    border-radius: 10px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    background: #FFFFFF;
}

.form-control:focus {
    outline: none;
    border-color: #FFD700;
    box-shadow: 0 0 0 3px rgba(255,215,0,0.1);
}

.form-control.error {
    border-color: #dc3545;
    box-shadow: 0 0 0 3px rgba(220,53,69,0.1);
}

.form-help {
    font-size: 0.75rem;
    color: #999;
    margin-top: 0.25rem;
}

/* Address suggestions */
.address-suggestions {
    position: relative;
    z-index: 10;
}

.suggestion-item {
    background: #FFFFFF;
    border: 1px solid #E5E5E5;
    padding: 0.5rem 0.75rem;
    cursor: pointer;
    font-size: 0.85rem;
    transition: background-color 0.2s ease;
}

.suggestion-item:hover {
    background: #F8F9FA;
}

.suggestion-item:first-child {
    border-radius: 5px 5px 0 0;
}

.suggestion-item:last-child {
    border-radius: 0 0 5px 5px;
}

/* Car class cards */
.car-class-cards {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.5rem;
}

.car-class-option {
    cursor: pointer;
    transition: all 0.3s ease;
}

.car-class-option input {
    display: none;
}

.class-card {
    background: #FFFFFF;
    border: 2px solid #E5E5E5;
    border-radius: 10px;
    padding: 1rem;
    text-align: center;
    transition: all 0.3s ease;
}

.car-class-option:hover .class-card,
.car-class-option.selected .class-card {
    border-color: #FFD700;
    background: #FFFBF0;
    transform: scale(1.02);
}

.car-class-option.economy.selected .class-card { border-color: #28a745; background: #f8fff8; }
.car-class-option.comfort.selected .class-card { border-color: #FFD700; background: #fffbf0; }
.car-class-option.business.selected .class-card { border-color: #6f42c1; background: #f8f6ff; }
.car-class-option.minivan.selected .class-card { border-color: #fd7e14; background: #fff8f5; }

.class-icon {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
    display: block;
}

.class-info {
    display: flex;
    flex-direction: column;
}

.class-name {
    font-weight: 500;
    color: #333;
    font-size: 0.85rem;
    margin-bottom: 0.25rem;
}

.class-price {
    font-weight: 600;
    color: #FFD700;
    font-size: 0.8rem;
}

.car-class-option.economy .class-price { color: #28a745; }
.car-class-option.comfort .class-price { color: #FFD700; }
.car-class-option.business .class-price { color: #6f42c1; }
.car-class-option.minivan .class-price { color: #fd7e14; }

/* Additional options */
.additional-options {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.option-checkbox {
    display: flex;
    align-items: center;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 8px;
    transition: background-color 0.2s ease;
}

.option-checkbox:hover {
    background: #F8F9FA;
}

.option-checkbox input {
    margin-right: 0.75rem;
}

.option-label {
    flex-grow: 1;
    font-size: 0.85rem;
    color: #333;
}

.option-note {
    font-size: 0.75rem;
    color: #999;
}

/* Price estimation */
.price-estimation {
    background: #F8F9FA;
    border-radius: 10px;
    padding: 1rem;
    text-align: center;
    border-left: 4px solid #FFD700;
    animation: slideDown 0.3s ease;
}

.estimate-price {
    margin-bottom: 0.5rem;
}

.price-label {
    color: #666;
    font-size: 0.9rem;
}

.price-value {
    font-weight: 700;
    color: #FFD700;
    font-size: 1.25rem;
    margin-left: 0.5rem;
}

.estimate-details {
    color: #999;
    font-size: 0.8rem;
}

/* Submit button */
.order-submit {
    background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
    border: none;
    color: #333;
    font-weight: 600;
    font-size: 1rem;
    padding: 1rem;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.order-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255,215,0,0.3);
}

.order-submit:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.btn-icon {
    font-size: 1.1rem;
}

/* Alternative contact */
.alternative-contact {
    text-align: center;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #f0f0f0;
}

.alt-text {
    font-size: 0.8rem;
    color: #666;
    margin-bottom: 0.5rem;
}

.phone-link {
    color: #333;
    font-weight: 600;
    text-decoration: none;
    font-size: 1rem;
}

.phone-link:hover {
    color: #FFD700;
}

.work-hours {
    font-size: 0.75rem;
    color: #999;
    margin: 0.25rem 0 0 0;
}

/* Success message */
.order-success {
    text-align: center;
    padding: 2rem 1rem;
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    border-radius: 15px;
    border-left: 5px solid #28a745;
}

.success-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.order-success h4 {
    color: #155724;
    margin-bottom: 0.5rem;
}

.order-success p {
    color: #155724;
    margin-bottom: 1.5rem;
}

/* Animations */
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

/* Responsive design */
@media (max-width: 768px) {
    .taxi-quick-order-module {
        position: static;
        margin: 1rem 0;
        border-radius: 15px;
    }
    
    .car-class-cards {
        grid-template-columns: 1fr;
    }
    
    .class-card {
        padding: 0.75rem;
    }
}

@media (max-width: 480px) {
    .taxi-quick-order-module {
        margin: 1rem -0.5rem;
        border-radius: 10px;
    }
    
    .module-header {
        margin-bottom: 1rem;
    }
    
    .form-control {
        padding: 0.6rem;
        font-size: 0.85rem;
    }
    
    .order-submit {
        padding: 0.85rem;
        font-size: 0.9rem;
    }
}

/* Loading state */
.order-submit.loading {
    position: relative;
    color: transparent;
}

.order-submit.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #333;
    border-top-color: transparent;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* High contrast support */
@media (prefers-contrast: high) {
    .taxi-quick-order-module {
        border: 2px solid #333;
    }
    
    .class-card {
        border-width: 3px;
    }
    
    .form-control {
        border-width: 2px;
    }
}
</style>