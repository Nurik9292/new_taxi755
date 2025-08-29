<?php
/**
 * Layout for displaying tariff blocks
 * @package     Joomla.Site
 * @subpackage  Templates.taxi755
 */

defined('_JEXEC') or die;

use Joomla\CMS\Uri\Uri;

// Extract data from displayData
$tariffs = $displayData['tariffs'] ?? [];
$title = $displayData['title'] ?? 'Наши тарифы';
$description = $displayData['description'] ?? '';
$airport = $displayData['airport'] ?? '';
$direction = $displayData['direction'] ?? 'to';

?>

<div class="tariff-blocks-container">

    <?php if (!empty($tariffs)) : ?>

        <!-- Header Section -->
        <div class="tariffs-header">
            <h2 class="tariffs-title"><?php echo $title; ?></h2>
            <?php if ($description) : ?>
                <p class="tariffs-description"><?php echo $description; ?></p>
            <?php endif; ?>
        </div>

        <!-- Tariffs Grid -->
        <div class="tariffs-grid">
            <?php foreach ($tariffs as $tariff) : ?>
                
                <div class="tariff-block <?php echo $tariff['class']; ?>" 
                     data-car-type="<?php echo $tariff['class']; ?>"
                     data-destination="<?php echo $airport; ?>">
                    
                    <!-- Tariff Image -->
                    <div class="tariff-image">
                        <?php if (!empty($tariff['image'])) : ?>
                            <img src="<?php echo $tariff['image']; ?>" 
                                 alt="<?php echo $tariff['title']; ?>" 
                                 loading="lazy">
                        <?php else : ?>
                            <div class="car-icon <?php echo $tariff['class']; ?>">
                                <?php
                                $icons = [
                                    'economy' => '🚗',
                                    'comfort' => '🚖',
                                    'comfort-plus' => '🚖',
                                    'business' => '🚘',
                                    'minivan' => '🚐'
                                ];
                                echo $icons[$tariff['class']] ?? '🚗';
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Tariff Content -->
                    <div class="tariff-content">
                        
                        <h3 class="tariff-title"><?php echo $tariff['title']; ?></h3>
                        
                        <?php if (!empty($tariff['price'])) : ?>
                            <div class="tariff-price">от <?php echo $tariff['price']; ?>₽</div>
                        <?php endif; ?>
                        
                        <?php if (!empty($tariff['description'])) : ?>
                            <p class="tariff-description"><?php echo $tariff['description']; ?></p>
                        <?php endif; ?>
                        
                        <!-- Car Models -->
                        <?php if (!empty($tariff['models'])) : ?>
                            <div class="car-models">
                                <h4>Модели автомобилей:</h4>
                                <ul class="models-list">
                                    <?php foreach ($tariff['models'] as $model) : ?>
                                        <li><?php echo $model; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Features -->
                        <?php if (!empty($tariff['features'])) : ?>
                            <div class="tariff-features">
                                <h4>Особенности:</h4>
                                <ul class="features-list">
                                    <?php foreach ($tariff['features'] as $feature) : ?>
                                        <li><?php echo $feature; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Additional Info -->
                        <?php if (!empty($tariff['info'])) : ?>
                            <div class="additional-info">
                                <?php foreach ($tariff['info'] as $key => $value) : ?>
                                    <div class="info-item">
                                        <span class="info-label"><?php echo ucfirst($key); ?>:</span>
                                        <span class="info-value"><?php echo $value; ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Order Button -->
                        <div class="tariff-order">
                            <button class="btn btn-primary btn-block order-btn" 
                                    data-car-type="<?php echo $tariff['class']; ?>"
                                    data-destination="<?php echo $airport; ?>"
                                    data-direction="<?php echo $direction; ?>">
                                <?php echo $tariff['button_text'] ?? 'Заказать'; ?>
                            </button>
                        </div>

                    </div>

                </div>
            <?php endforeach; ?>

        </div>

        <!-- Additional Info -->
        <?php if (!empty($displayData['additional_info'])) : ?>
            <div class="tariffs-additional-info">
                <?php echo $displayData['additional_info']; ?>
            </div>
        <?php endif; ?>

    <?php else : ?>

        <div class="no-tariffs">
            <div class="no-tariffs-content">
                <div class="no-tariffs-icon">📞</div>
                <h3>Тарифы временно недоступны</h3>
                <p>Пожалуйста, позвоните нам для уточнения актуальных цен и оформления заказа.</p>
                <a href="tel:+79266410896" class="btn btn-primary btn-lg">
                    Позвонить диспетчеру
                </a>
            </div>
        </div>

    <?php endif; ?>

</div>

<!-- Default tariff data generator -->
<?php if (empty($tariffs)) : ?>
<script>
// Generate default tariffs for demonstration
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.no-tariffs');
    if (!container) return;
    
    // Default tariff data based on current page
    const pageUrl = window.location.pathname;
    const airport = pageUrl.match(/(domodedovo|sheremetyevo|vnukovo|zhukovsky)/)?.[1];
    
    if (airport) {
        const defaultTariffs = {
            domodedovo: {
                economy: { price: 800, time: 45, distance: 42 },
                comfort: { price: 1200, time: 45, distance: 42 },
                business: { price: 1800, time: 45, distance: 42 },
                minivan: { price: 1400, time: 45, distance: 42 }
            },
            sheremetyevo: {
                economy: { price: 900, time: 40, distance: 35 },
                comfort: { price: 1300, time: 40, distance: 35 },
                business: { price: 1900, time: 40, distance: 35 },
                minivan: { price: 1500, time: 40, distance: 35 }
            },
            vnukovo: {
                economy: { price: 700, time: 35, distance: 28 },
                comfort: { price: 1100, time: 35, distance: 28 },
                business: { price: 1700, time: 35, distance: 28 },
                minivan: { price: 1300, time: 35, distance: 28 }
            },
            zhukovsky: {
                economy: { price: 1200, time: 70, distance: 65 },
                comfort: { price: 1600, time: 70, distance: 65 },
                business: { price: 2200, time: 70, distance: 65 },
                minivan: { price: 1800, time: 70, distance: 65 }
            }
        };
        
        // Update price elements on page with dynamic data
        const priceElements = document.querySelectorAll('[data-price-airport][data-price-class]');
        priceElements.forEach(element => {
            const airportCode = element.dataset.priceAirport;
            const carClass = element.dataset.priceClass;
            
            if (defaultTariffs[airportCode] && defaultTariffs[airportCode][carClass]) {
                const tariff = defaultTariffs[airportCode][carClass];
                element.textContent = `от ${tariff.price}₽`;
            }
        });
    }
});
</script>
<?php endif; ?>

<style>
/* Tariff Blocks Layout Styles */
.tariff-blocks-container {
    margin: 2rem 0;
}

.tariffs-header {
    text-align: center;
    margin-bottom: 3rem;
}

.tariffs-title {
    font-size: 2.5rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #333;
}

.tariffs-description {
    font-size: 1.125rem;
    color: #666;
    margin-top: 1rem;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.6;
}

.tariffs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
    margin: 2rem 0;
}

.tariff-block {
    background: linear-gradient(135deg, #FFFFFF 0%, #FFFBF0 100%);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border: 1px solid #f0f0f0;
    position: relative;
}

.tariff-block:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(255,215,0,0.2);
    border-color: #FFD700;
}

/* Class-specific border colors */
.tariff-block.economy {
    border-left: 5px solid #28a745;
}

.tariff-block.economy:hover {
    box-shadow: 0 15px 40px rgba(40,167,69,0.2);
    border-color: #28a745;
}

.tariff-block.comfort {
    border-left: 5px solid #FFD700;
}

.tariff-block.business {
    border-left: 5px solid #6f42c1;
}

.tariff-block.business:hover {
    box-shadow: 0 15px 40px rgba(111,66,193,0.2);
    border-color: #6f42c1;
}

.tariff-block.minivan {
    border-left: 5px solid #fd7e14;
}

.tariff-block.minivan:hover {
    box-shadow: 0 15px 40px rgba(253,126,20,0.2);
    border-color: #fd7e14;
}

.tariff-image {
    height: 200px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    position: relative;
}

.tariff-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.tariff-block:hover .tariff-image img {
    transform: scale(1.05);
}

.car-icon {
    font-size: 4rem;
    opacity: 0.7;
}

.tariff-content {
    padding: 2rem;
}

.tariff-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #333;
}

.tariff-price {
    font-size: 2rem;
    font-weight: 700;
    margin: 1rem 0;
    display: block;
}

.tariff-block.economy .tariff-price { color: #28a745; }
.tariff-block.comfort .tariff-price { color: #FFD700; }
.tariff-block.business .tariff-price { color: #6f42c1; }
.tariff-block.minivan .tariff-price { color: #fd7e14; }

.tariff-description {
    color: #666;
    margin: 1rem 0;
    line-height: 1.6;
}

.car-models h4,
.tariff-features h4 {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
    color: #333;
}

.models-list,
.features-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.models-list li,
.features-list li {
    padding: 0.25rem 0;
    color: #666;
    font-size: 0.9rem;
    position: relative;
    padding-left: 1rem;
}

.models-list li:before {
    content: '•';
    color: #FFD700;
    position: absolute;
    left: 0;
}

.features-list li:before {
    content: '✓';
    color: #28a745;
    position: absolute;
    left: 0;
    font-weight: bold;
}

.additional-info {
    margin: 1rem 0;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 10px;
    border-left: 3px solid #FFD700;
}

.info-item {
    display: flex;
    justify-content: space-between;
    margin: 0.5rem 0;
}

.info-label {
    color: #666;
    font-weight: 500;
}

.info-value {
    color: #333;
    font-weight: 600;
}

.tariff-order {
    margin-top: 2rem;
    text-align: center;
}

.btn-block {
    width: 100%;
}

.tariffs-additional-info {
    margin-top: 3rem;
    padding: 2rem;
    background: #f8f9fa;
    border-radius: 15px;
    text-align: center;
}

/* No tariffs fallback */
.no-tariffs {
    text-align: center;
    padding: 4rem 2rem;
    background: #f8f9fa;
    border-radius: 20px;
    margin: 2rem 0;
}

.no-tariffs-content {
    max-width: 400px;
    margin: 0 auto;
}

.no-tariffs-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.no-tariffs h3 {
    color: #333;
    margin-bottom: 1rem;
}

.no-tariffs p {
    color: #666;
    margin-bottom: 2rem;
    line-height: 1.6;
}

/* Animation classes */
.tariff-block.animate {
    opacity: 0;
    transform: translateY(30px);
    animation: tariffSlideIn 0.6s ease forwards;
}

.tariff-block.animate:nth-child(1) { animation-delay: 0.1s; }
.tariff-block.animate:nth-child(2) { animation-delay: 0.2s; }
.tariff-block.animate:nth-child(3) { animation-delay: 0.3s; }
.tariff-block.animate:nth-child(4) { animation-delay: 0.4s; }

@keyframes tariffSlideIn {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .tariffs-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .tariff-content {
        padding: 1.5rem;
    }
    
    .tariff-title {
        font-size: 1.25rem;
    }
    
    .tariff-price {
        font-size: 1.75rem;
    }
    
    .tariffs-title {
        font-size: 2rem;
    }
    
    .additional-info {
        margin: 1rem -0.5rem;
    }
}

@media (max-width: 480px) {
    .tariff-blocks-container {
        margin: 1rem 0;
    }
    
    .tariffs-header {
        margin-bottom: 2rem;
    }
    
    .tariffs-title {
        font-size: 1.75rem;
    }
    
    .tariff-content {
        padding: 1rem;
    }
    
    .tariff-image {
        height: 150px;
    }
    
    .car-icon {
        font-size: 3rem;
    }
    
    .tariff-price {
        font-