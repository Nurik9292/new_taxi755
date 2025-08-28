<?php
/**
 * Layout for Taxi755 Tariff Blocks
 * Replaces old table structure with modern card blocks
 */

defined('_JEXEC') or die;

$tariffs = $displayData['tariffs'] ?? [];
$direction = $displayData['direction'] ?? 'to'; // 'to' or 'from'
$destination = $displayData['destination'] ?? 'аэропорт';

?>

<div class="tariff-blocks-container">

    <?php if (!empty($tariffs)) : ?>

        <div class="tariffs-header">
            <h2 class="section-title">
                <?php if ($direction === 'to') : ?>
                    Трансфер в <?php echo $destination; ?>
                <?php else : ?>
                    Трансфер из <?php echo $destination; ?>
                <?php endif; ?>
            </h2>
            <p class="tariffs-description">
                Выберите подходящий класс автомобиля для комфортной поездки
            </p>
        </div>

        <div class="tariffs-grid">

            <?php foreach ($tariffs as $tariff) : ?>
                <div class="tariff-block" data-car-class="<?php echo $tariff['class']; ?>">

                    <!-- Car Image -->
                    <div class="tariff-image">
                        <?php if (!empty($tariff['image'])) : ?>
                            <img src="<?php echo $tariff['image']; ?>"
                                 alt="<?php echo $tariff['title']; ?>"
                                 loading="lazy">
                        <?php else : ?>
                            <div class="tariff-icon">
                                <?php echo $tariff['icon'] ?? '🚗'; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Tariff Info -->
                    <div class="tariff-content">
                        <h3 class="tariff-title"><?php echo $tariff['title']; ?></h3>

                        <?php if (!empty($tariff['description'])) : ?>
                            <p class="tariff-description"><?php echo $tariff['description']; ?></p>
                        <?php endif; ?>

                        <?php if (!empty($tariff['features'])) : ?>
                            <ul class="tariff-features">
                                <?php foreach ($tariff['features'] as $feature) : ?>
                                    <li>✓ <?php echo $feature; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <!-- Price -->
                        <div class="tariff-price">
                            <?php if (isset($tariff['price_from'])) : ?>
                                <span class="price-label">от</span>
                                <span class="price-amount"><?php echo $tariff['price_from']; ?></span>
                                <span class="price-currency">₽</span>
                            <?php endif; ?>

                            <?php if (!empty($tariff['price_note'])) : ?>
                                <div class="price-note"><?php echo $tariff['price_note']; ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Order Button -->
                        <button class="order-btn"
                                data-car-type="<?php echo $tariff['class']; ?>"
                                data-direction="<?php echo $direction; ?>"
                                data-destination="<?php echo $destination; ?>"
                                onclick="orderTaxi('<?php echo $tariff['class']; ?>')">
                            <?php echo $tariff['button_text'] ?? 'Заказать'; ?>
                        </button>

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
            <p>Тарифы временно недоступны. Пожалуйста, позвоните нам для уточнения цен.</p>
            <a href="tel:+79266410896" class="btn btn-primary">Позвонить диспетчеру</a>
        </div>

    <?php endif; ?>

</div>

<style>
    /* Specific styles for tariff blocks */
    .tariff-blocks-container {
        margin: 2rem 0;
    }

    .tariffs-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .tariffs-description {
        font-size: 1.1rem;
        color: #666;
        margin-top: 1rem;
    }

    .tariffs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
        margin: 2rem 0;
    }

    .tariff-block {
        background: linear-gradient(135deg, #fff 0%, #fffbf0 100%);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }

    .tariff-block:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(255,215,0,0.2);
        border-color: #FFD700;
    }

    .tariff-image {
        height: 200px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
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

    .tariff-icon {
        font-size: 4rem;
        opacity: 0.7;
    }

    .tariff-content {
        padding: 2rem;
    }

    .tariff-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 1rem;
        text-align: center;
    }

    .tariff-description {
        color: #666;
        margin-bottom: 1.5rem;
        text-align: center;
        line-height: 1.6;
    }

    .tariff-features {
        list-style: none;
        padding: 0;
        margin: 1.5rem 0;
    }

    .tariff-features li {
        color: #555;
        margin-bottom: 0.5rem;
        padding-left: 1rem;
    }

    .tariff-price {
        text-align: center;
        margin: 2rem 0 1.5rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, #fff8e1 0%, #fff 100%);
        border-radius: 15px;
        border: 2px solid #FFD700;
    }

    .price-label {
        font-size: 1rem;
        color: #666;
        margin-right: 0.5rem;
    }

    .price-amount {
        font-size: 2rem;
        font-weight: bold;
        color: #FFD700;
    }

    .price-currency {
        font-size: 1.2rem;
        color: #666;
        margin-left: 0.3rem;
    }

    .price-note {
        font-size: 0.9rem;
        color: #666;
        margin-top: 0.5rem;
        font-style: italic;
    }

    .tariff-block .order-btn {
        width: 100%;
        padding: 1rem;
        font-size: 1.1rem;
        margin-top: 1rem;
    }

    .tariffs-additional-info {
        background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
        padding: 2rem;
        border-radius: 15px;
        margin-top: 3rem;
        border-left: 4px solid #FFD700;
    }

    .no-tariffs {
        text-align: center;
        padding: 3rem 2rem;
        background: #f8f9fa;
        border-radius: 15px;
        color: #666;
    }

    .no-tariffs .btn {
        margin-top: 1rem;
    }

    /* Mobile optimizations */
    @media (max-width: 768px) {
        .tariffs-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .tariff-content {
            padding: 1.5rem;
        }

        .tariff-image {
            height: 150px;
        }

        .price-amount {
            font-size: 1.8rem;
        }
    }
</style>