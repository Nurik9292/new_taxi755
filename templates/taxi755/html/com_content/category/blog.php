<?php

/**
 * @package     Joomla.Site  
 * @subpackage  Templates.taxi755
 * @copyright   (C) 2025 Taxi755. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

$app = Factory::getApplication();
$user = $app->getIdentity();

// Category info
$category = $this->category;
$items = $this->items;
$pagination = $this->pagination;

// Check if this is airport or class category for special styling
$isAirportCategory = strpos($category->path, 'airports') === 0;
$isClassCategory = strpos($category->path, 'classes') === 0;
$isDirectionCategory = in_array($category->alias, ['to-airport', 'from-airport']);

// Get template params for pricing
$templateParams = $app->getTemplate(true)->params;
$showPrices = $templateParams->get('show_prices', 1);

?>

<div class="category-page category-<?php echo $category->alias; ?>">

    <!-- Category Header -->
    <div class="category-header">
        <?php if ($category->image) : ?>
            <div class="category-image">
                <img src="<?php echo $category->image; ?>" alt="<?php echo $category->image_alt ?: $category->title; ?>">
            </div>
        <?php endif; ?>

        <div class="category-content">
            <h1 class="category-title"><?php echo $category->title; ?></h1>

            <?php if ($category->description) : ?>
                <div class="category-description">
                    <?php echo $category->description; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Subcategories for airport directions -->
    <?php if ($isDirectionCategory && !empty($this->children[$this->category->id])) : ?>
        <section class="airport-destinations">
            <h2 class="section-title">Выберите аэропорт</h2>
            <div class="destinations-grid">
                <?php foreach ($this->children[$this->category->id] as $child) : ?>
                    <?php
                    // Extract airport name and get pricing
                    $airportAlias = str_replace(['to-', 'from-'], '', $child->alias);
                    $airportNames = [
                        'domodedovo' => 'Домодедово',
                        'sheremetyevo' => 'Шереметьево',
                        'vnukovo' => 'Внуково',
                        'zhukovsky' => 'Жуковский'
                    ];
                    $airportName = $airportNames[$airportAlias] ?? $airportAlias;
                    ?>

                    <div class="destination-card <?php echo $airportAlias; ?>">
                        <div class="destination-image">
                            <?php if ($child->image) : ?>
                                <img src="<?php echo $child->image; ?>" alt="<?php echo $airportName; ?>">
                            <?php else : ?>
                                <div class="airport-icon">✈️</div>
                            <?php endif; ?>
                        </div>

                        <div class="destination-content">
                            <h3><?php echo $child->title; ?></h3>

                            <?php if ($child->description) : ?>
                                <p class="destination-desc"><?php echo strip_tags($child->description); ?></p>
                            <?php endif; ?>

                            <!-- Price ranges for different classes -->
                            <div class="price-ranges">
                                <div class="price-item economy">
                                    <span class="class-name">Эконом:</span>
                                    <span class="price" data-price-airport="<?php echo $airportAlias; ?>" data-price-class="economy">
                                        от <?php echo $this->getPriceForAirport($airportAlias, 'economy'); ?>₽
                                    </span>
                                </div>
                                <div class="price-item comfort">
                                    <span class="class-name">Комфорт:</span>
                                    <span class="price" data-price-airport="<?php echo $airportAlias; ?>" data-price-class="comfort">
                                        от <?php echo $this->getPriceForAirport($airportAlias, 'comfort'); ?>₽
                                    </span>
                                </div>
                                <div class="price-item business">
                                    <span class="class-name">Бизнес:</span>
                                    <span class="price" data-price-airport="<?php echo $airportAlias; ?>" data-price-class="business">
                                        от <?php echo $this->getPriceForAirport($airportAlias, 'business'); ?>₽
                                    </span>
                                </div>
                            </div>

                            <div class="destination-actions">
                                <a href="<?php echo Route::_('index.php?option=com_content&view=category&id=' . $child->id); ?>"
                                    class="btn btn-outline">
                                    Выбрать класс
                                </a>
                                <button class="btn btn-primary order-btn"
                                    data-car-type="any"
                                    data-destination="<?php echo $airportAlias; ?>"
                                    data-direction="<?php echo strpos($category->alias, 'from') === 0 ? 'from' : 'to'; ?>">
                                    Заказать
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- Car class cards for classes category -->
    <?php if ($isClassCategory && !empty($this->children[$this->category->id])) : ?>
        <section class="car-classes">
            <h2 class="section-title">Наши автомобили</h2>
            <div class="classes-grid">
                <?php foreach ($this->children[$this->category->id] as $child) : ?>
                    <?php
                    $classAlias = $child->alias;
                    $classImages = [
                        'economy' => '/images/classes/economy.jpg',
                        'comfort' => '/images/classes/comfort.jpg',
                        'comfort-plus' => '/images/classes/comfort-plus.jpg',
                        'business' => '/images/classes/business.jpg',
                        'minivan' => '/images/classes/minivan.jpg'
                    ];
                    $basePrices = [
                        'economy' => 800,
                        'comfort' => 1000,
                        'comfort-plus' => 1300,
                        'business' => 1500,
                        'minivan' => 1200
                    ];
                    ?>

                    <div class="class-card <?php echo $classAlias; ?>">
                        <div class="class-image">
                            <img src="<?php echo Uri::root() . ($child->image ?: $classImages[$classAlias] ?? '/images/classes/default.jpg'); ?>"
                                alt="<?php echo $child->title; ?>" loading="lazy">
                        </div>

                        <div class="class-content">
                            <h3 class="class-title"><?php echo $child->title; ?></h3>

                            <div class="class-price">
                                от <?php echo $basePrices[$classAlias] ?? 1000; ?>₽
                            </div>

                            <?php if ($child->description) : ?>
                                <div class="class-description">
                                    <?php echo HTMLHelper::_('string.truncate', strip_tags($child->description), 120); ?>
                                </div>
                            <?php endif; ?>

                            <div class="class-actions">
                                <a href="<?php echo Route::_('index.php?option=com_content&view=category&id=' . $child->id); ?>"
                                    class="btn btn-outline">
                                    Подробнее
                                </a>
                                <button class="btn btn-primary order-btn"
                                    data-car-type="<?php echo $classAlias; ?>">
                                    Заказать <?php echo mb_strtolower($child->title); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- Articles in tariff blocks format -->
    <?php if (!empty($items)) : ?>
        <section class="category-articles">

            <?php if ($isAirportCategory || $isClassCategory) : ?>
                <!-- Tariff blocks layout for services -->
                <div class="tariff-blocks-container">
                    <?php if (!$isDirectionCategory && !$isClassCategory) : ?>
                        <div class="tariffs-header">
                            <h2>Доступные тарифы</h2>
                            <p class="tariffs-description">
                                Выберите подходящий класс автомобиля и оформите заказ онлайн или по телефону
                            </p>
                        </div>
                    <?php endif; ?>

                    <div class="tariffs-grid">
                        <?php foreach ($items as $item) : ?>
                            <?php
                            // Determine car class from article or category
                            $carClass = 'comfort'; // default
                            if (strpos($item->title, 'Эконом') !== false || strpos($item->alias, 'economy') !== false) {
                                $carClass = 'economy';
                            } elseif (strpos($item->title, 'Бизнес') !== false || strpos($item->alias, 'business') !== false) {
                                $carClass = 'business';
                            } elseif (strpos($item->title, 'Минивэн') !== false || strpos($item->alias, 'minivan') !== false) {
                                $carClass = 'minivan';
                            } elseif (strpos($item->title, 'Комфорт+') !== false || strpos($item->alias, 'comfort-plus') !== false) {
                                $carClass = 'comfort-plus';
                            }

                            // Extract price from content or use defaults
                            $priceMatch = [];
                            preg_match('/(\d{3,4})\s*₽/', $item->introtext, $priceMatch);
                            $price = $priceMatch[1] ?? 1000;
                            ?>

                            <article class="tariff-block <?php echo $carClass; ?>">

                                <?php if ($item->images && isset(json_decode($item->images)->image_intro) && json_decode($item->images)->image_intro) : ?>
                                    <div class="tariff-image">
                                        <img src="<?php echo json_decode($item->images)->image_intro; ?>"
                                            alt="<?php echo $item->title; ?>" loading="lazy">
                                    </div>
                                <?php else : ?>
                                    <div class="tariff-image">
                                        <div class="car-icon-placeholder <?php echo $carClass; ?>">
                                            <?php
                                            $icons = [
                                                'economy' => '🚗',
                                                'comfort' => '🚖',
                                                'business' => '🚘',
                                                'minivan' => '🚐'
                                            ];
                                            echo $icons[$carClass] ?? '🚗';
                                            ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="tariff-content">
                                    <h3 class="tariff-title">
                                        <a href="<?php echo Route::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->language)); ?>">
                                            <?php echo $item->title; ?>
                                        </a>
                                    </h3>

                                    <div class="tariff-price">от <?php echo $price; ?>₽</div>

                                    <?php if ($item->introtext) : ?>
                                        <div class="tariff-description">
                                            <?php echo HTMLHelper::_('string.truncate', strip_tags($item->introtext), 150); ?>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Car models for the class -->
                                    <?php if ($carClass && $isClassCategory) : ?>
                                        <div class="car-models-list">
                                            <?php
                                            $carModels = [
                                                'economy' => ['Hyundai Solaris', 'Kia Rio', 'Chevrolet Aveo'],
                                                'comfort' => ['Toyota Camry', 'Hyundai Elantra', 'VW Polo'],
                                                'comfort-plus' => ['Toyota Camry Premium', 'VW Passat', 'Skoda Superb'],
                                                'business' => ['Mercedes E-класс', 'BMW 5 серии', 'Audi A6'],
                                                'minivan' => ['Mercedes Vito', 'VW Crafter', 'Hyundai H1']
                                            ];

                                            if (isset($carModels[$carClass])) : ?>
                                                <ul class="models-list">
                                                    <?php foreach ($carModels[$carClass] as $model) : ?>
                                                        <li><?php echo $model; ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="tariff-actions">
                                        <a href="<?php echo Route::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->language)); ?>"
                                            class="btn btn-outline">
                                            Подробнее
                                        </a>

                                        <button class="btn btn-primary order-btn"
                                            data-car-type="<?php echo $carClass; ?>"
                                            data-destination="<?php echo $isAirportCategory ? $airportAlias ?? '' : ''; ?>"
                                            data-direction="<?php echo strpos($category->path, 'from-') !== false ? 'from' : 'to'; ?>">
                                            <?php
                                            if ($isAirportCategory) {
                                                echo strpos($category->path, 'from-') !== false ? 'Встретить' : 'Заказать';
                                            } else {
                                                echo 'Заказать';
                                            }
                                            ?>
                                        </button>
                                    </div>

                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>

            <?php else : ?>
                <!-- Standard blog layout for other categories -->
                <div class="articles-grid">
                    <?php foreach ($items as $item) : ?>
                        <article class="article-card">

                            <?php if ($item->images && isset(json_decode($item->images)->image_intro) && json_decode($item->images)->image_intro) : ?>
                                <div class="article-image">
                                    <a href="<?php echo Route::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->language)); ?>">
                                        <img src="<?php echo json_decode($item->images)->image_intro; ?>"
                                            alt="<?php echo $item->title; ?>" loading="lazy">
                                    </a>
                                </div>
                            <?php endif; ?>

                            <div class="article-content">
                                <h3 class="article-title">
                                    <a href="<?php echo Route::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->language)); ?>">
                                        <?php echo $item->title; ?>
                                    </a>
                                </h3>

                                <?php if ($item->introtext) : ?>
                                    <div class="article-intro">
                                        <?php echo HTMLHelper::_('string.truncate', strip_tags($item->introtext), 200); ?>
                                    </div>
                                <?php endif; ?>

                                <div class="article-meta">
                                    <time class="article-date">
                                        <?php echo HTMLHelper::_('date', $item->created, Text::_('DATE_FORMAT_LC3')); ?>
                                    </time>
                                </div>

                                <div class="article-actions">
                                    <a href="<?php echo Route::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->language)); ?>"
                                        class="btn btn-outline">
                                        Читать далее
                                    </a>
                                </div>
                            </div>

                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </section>
    <?php endif; ?>

    <!-- Pagination -->
    <?php if (($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
        <div class="pagination-wrapper">
            <?php echo $this->pagination->getPagesLinks(); ?>
        </div>
    <?php endif; ?>

</div>

<style>
    /* Category-specific styles */
    .category-header {
        text-align: center;
        padding: 2rem 0;
        background: linear-gradient(135deg, #FFFFFF 0%, #FFFBF0 100%);
        border-radius: 20px;
        margin-bottom: 3rem;
    }

    .category-image {
        margin-bottom: 1rem;
    }

    .category-image img {
        max-height: 200px;
        border-radius: 15px;
        object-fit: cover;
    }

    .category-title {
        font-size: 2.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #333;
    }

    .category-description {
        font-size: 1.125rem;
        color: #666;
        max-width: 700px;
        margin: 0 auto;
    }

    /* Airport destinations grid */
    .destinations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin: 2rem 0;
    }

    .destination-card {
        background: #FFFFFF;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }

    .destination-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(255, 215, 0, 0.2);
    }

    .destination-image {
        height: 150px;
        background: #F8F9FA;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .destination-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .airport-icon {
        font-size: 3rem;
    }

    .destination-content {
        padding: 1.5rem;
    }

    .destination-content h3 {
        margin-bottom: 1rem;
        color: #333;
    }

    .destination-desc {
        color: #666;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
    }

    .price-ranges {
        margin: 1.5rem 0;
    }

    .price-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .price-item:last-child {
        border-bottom: none;
    }

    .class-name {
        font-weight: 500;
        color: #333;
    }

    .price {
        font-weight: 600;
        color: #FFD700;
    }

    .price-item.economy .price {
        color: #28a745;
    }

    .price-item.comfort .price {
        color: #FFD700;
    }

    .price-item.business .price {
        color: #6f42c1;
    }

    .destination-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .destination-actions .btn {
        flex: 1;
    }

    /* Car classes grid */
    .classes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 2rem;
        margin: 2rem 0;
    }

    .class-card {
        background: #FFFFFF;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border-left: 5px solid #FFD700;
    }

    .class-card.economy {
        border-left-color: #28a745;
    }

    .class-card.comfort {
        border-left-color: #FFD700;
    }

    .class-card.business {
        border-left-color: #6f42c1;
    }

    .class-card.minivan {
        border-left-color: #fd7e14;
    }

    .class-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(255, 215, 0, 0.15);
    }

    .class-image {
        height: 200px;
        background: #F8F9FA;
        overflow: hidden;
    }

    .class-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .class-card:hover .class-image img {
        transform: scale(1.05);
    }

    .class-content {
        padding: 2rem;
    }

    .class-title {
        margin-bottom: 0.5rem;
        color: #333;
    }

    .class-price {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 1rem 0;
        color: #FFD700;
    }

    .class-card.economy .class-price {
        color: #28a745;
    }

    .class-card.comfort .class-price {
        color: #FFD700;
    }

    .class-card.business .class-price {
        color: #6f42c1;
    }

    .class-card.minivan .class-price {
        color: #fd7e14;
    }

    .class-description {
        color: #666;
        line-height: 1.6;
        margin: 1rem 0;
    }

    .models-list {
        list-style: none;
        padding: 0;
        margin: 1rem 0;
    }

    .models-list li {
        padding: 0.25rem 0;
        color: #666;
        font-size: 0.9rem;
    }

    .models-list li:before {
        content: '•';
        color: #FFD700;
        margin-right: 0.5rem;
    }

    .class-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .class-actions .btn {
        flex: 1;
    }

    /* Standard articles grid */
    .articles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
        margin: 2rem 0;
    }

    .article-card {
        background: #FFFFFF;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .article-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .article-image {
        height: 200px;
        overflow: hidden;
    }

    .article-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .article-card:hover .article-image img {
        transform: scale(1.05);
    }

    .article-content {
        padding: 1.5rem;
    }

    .article-title {
        margin-bottom: 1rem;
    }

    .article-title a {
        color: #333;
        text-decoration: none;
    }

    .article-title a:hover {
        color: #FFD700;
    }

    .article-intro {
        color: #666;
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .article-meta {
        font-size: 0.875rem;
        color: #999;
        margin-bottom: 1rem;
    }

    .article-actions {
        margin-top: 1rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {

        .destinations-grid,
        .classes-grid,
        .articles-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .destination-actions,
        .class-actions {
            flex-direction: column;
        }

        .category-title {
            font-size: 2rem;
        }

        .price-ranges {
            margin: 1rem 0;
        }
    }

    @media (max-width: 480px) {
        .category-header {
            margin-bottom: 2rem;
            padding: 1.5rem;
        }

        .category-title {
            font-size: 1.75rem;
        }

        .tariff-content,
        .class-content,
        .destination-content {
            padding: 1rem;
        }

        .class-price,
        .tariff-price {
            font-size: 1.25rem;
        }
    }
</style>

<?php
// Helper function for pricing (add to template helper if needed)
if (!function_exists('getPriceForAirport')) {
    function getPriceForAirport($airport, $carClass)
    {
        $prices = [
            'domodedovo' => ['economy' => 800, 'comfort' => 1200, 'business' => 1800, 'minivan' => 1400],
            'sheremetyevo' => ['economy' => 900, 'comfort' => 1300, 'business' => 1900, 'minivan' => 1500],
            'vnukovo' => ['economy' => 700, 'comfort' => 1100, 'business' => 1700, 'minivan' => 1300],
            'zhukovsky' => ['economy' => 1200, 'comfort' => 1600, 'business' => 2200, 'minivan' => 1800]
        ];

        return $prices[$airport][$carClass] ?? 1000;
    }
}

// Make helper available to view
if (!method_exists($this, 'getPriceForAirport')) {
    $this->getPriceForAirport = 'getPriceForAirport';
}
?>