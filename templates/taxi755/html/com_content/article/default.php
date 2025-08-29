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
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\Component\Content\Site\Helper\RouteHelper;

$article = $this->item;
$params = $article->params;
$images = json_decode($article->images);
$urls = json_decode($article->urls);

// Detect article type based on category path or alias
$isAirportArticle = strpos($article->category_alias, 'airport') !== false || 
                   strpos($article->alias, 'domodedovo') !== false ||
                   strpos($article->alias, 'sheremetyevo') !== false ||
                   strpos($article->alias, 'vnukovo') !== false ||
                   strpos($article->alias, 'zhukovsky') !== false;

$isClassArticle = in_array($article->category_alias, ['economy', 'comfort', 'comfort-plus', 'business', 'minivan']);

$isStationArticle = strpos($article->category_alias, 'station') !== false ||
                   strpos($article->alias, 'vokzal') !== false;

// Extract service info from title/alias
$carClass = 'comfort'; // default
$destination = '';
$direction = 'to';

if (preg_match('/(эконом|economy)/ui', $article->title . $article->alias)) {
    $carClass = 'economy';
} elseif (preg_match('/(бизнес|business)/ui', $article->title . $article->alias)) {
    $carClass = 'business';  
} elseif (preg_match('/(минивэн|minivan)/ui', $article->title . $article->alias)) {
    $carClass = 'minivan';
} elseif (preg_match('/(комфорт\+|comfort-plus)/ui', $article->title . $article->alias)) {
    $carClass = 'comfort-plus';
}

// Extract destination
if (preg_match('/(домодедово|domodedovo)/ui', $article->title . $article->alias)) {
    $destination = 'domodedovo';
} elseif (preg_match('/(шереметьево|sheremetyevo)/ui', $article->title . $article->alias)) {
    $destination = 'sheremetyevo';
} elseif (preg_match('/(внуково|vnukovo)/ui', $article->title . $article->alias)) {
    $destination = 'vnukovo';
} elseif (preg_match('/(жуковский|zhukovsky)/ui', $article->title . $article->alias)) {
    $destination = 'zhukovsky';
}

// Extract direction
if (preg_match('/(из|from)/ui', $article->title)) {
    $direction = 'from';
}

// Get pricing data
$pricing = [];
if ($destination) {
    $pricing = [
        'domodedovo' => ['economy' => 800, 'comfort' => 1200, 'business' => 1800, 'minivan' => 1400],
        'sheremetyevo' => ['economy' => 900, 'comfort' => 1300, 'business' => 1900, 'minivan' => 1500],
        'vnukovo' => ['economy' => 700, 'comfort' => 1100, 'business' => 1700, 'minivan' => 1300],
        'zhukovsky' => ['economy' => 1200, 'comfort' => 1600, 'business' => 2200, 'minivan' => 1800]
    ][$destination] ?? [];
}

$currentPrice = $pricing[$carClass] ?? 1000;

?>

<article class="article-page service-landing <?php echo $carClass; ?><?php echo $isAirportArticle ? ' airport-service' : ''; ?>">

    <!-- Article Header -->
    <header class="article-header">
        <div class="article-hero">
            
            <?php if ($images && $images->image_intro) : ?>
                <div class="hero-image">
                    <img src="<?php echo $images->image_intro; ?>" 
                         alt="<?php echo $images->image_intro_alt ?: $article->title; ?>">
                </div>
            <?php endif; ?>
            
            <div class="hero-content">
                <h1 class="article-title"><?php echo $article->title; ?></h1>
                
                <?php if ($isAirportArticle && $currentPrice) : ?>
                    <div class="price-hero">
                        <span class="price-amount">от <?php echo $currentPrice; ?>₽</span>
                        <span class="travel-time"><?php echo $this->getTravelTime($destination); ?> минут</span>
                        <span class="travel-distance"><?php echo $this->getDistance($destination); ?> км</span>
                    </div>
                <?php endif; ?>
                
            </div>
        </div>
    </header>

    <!-- Quick Order Section for Service Pages -->
    <?php if ($isAirportArticle || $isClassArticle || $isStationArticle) : ?>
        <section class="quick-order-section">
            <div class="order-card">
                
                <div class="order-header">
                    <h2>Быстрый заказ</h2>
                    <p>Оформите заказ онлайн или позвоните диспетчеру</p>
                </div>
                
                <div class="order-content">
                    
                    <div class="service-details">
                        <div class="detail-item">
                            <span class="detail-label">Класс автомобиля:</span>
                            <span class="detail-value <?php echo $carClass; ?>">
                                <?php
                                $classNames = [
                                    'economy' => 'Эконом класс',
                                    'comfort' => 'Комфорт класс', 
                                    'comfort-plus' => 'Комфорт+',
                                    'business' => 'Бизнес класс',
                                    'minivan' => 'Минивэн'
                                ];
                                echo $classNames[$carClass] ?? 'Комфорт класс';
                                ?>
                            </span>
                        </div>
                        
                        <?php if ($destination) : ?>
                            <div class="detail-item">
                                <span class="detail-label">Направление:</span>
                                <span class="detail-value">
                                    <?php
                                    $airportNames = [
                                        'domodedovo' => 'Домодедово',
                                        'sheremetyevo' => 'Шереметьево',
                                        'vnukovo' => 'Внуково', 
                                        'zhukovsky' => 'Жуковский'
                                    ];
                                    echo ($direction === 'from' ? 'Из ' : 'В ') . ($airportNames[$destination] ?? $destination);
                                    ?>
                                </span>
                            </div>
                            
                            <div class="detail-item">
                                <span class="detail-label">Стоимость:</span>
                                <span class="detail-value price">от <?php echo $currentPrice; ?>₽</span>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                    
                    <div class="order-buttons">
                        <button class="btn btn-primary btn-lg order-btn" 
                                data-car-type="<?php echo $carClass; ?>"
                                data-destination="<?php echo $destination; ?>"
                                data-direction="<?php echo $direction; ?>">
                            <?php if ($direction === 'from') : ?>
                                Заказать встречу
                            <?php else : ?>
                                Заказать поездку
                            <?php endif; ?>
                        </button>
                        
                        <p class="phone-cta">
                            Или звоните: <a href="tel:+79266410896" class="phone-link">+7 (926) 641-08-96</a>
                        </p>
                    </div>
                    
                </div>
                
            </div>
        </section>
    <?php endif; ?>

    <!-- Car Models Section for Class Articles -->
    <?php if ($isClassArticle) : ?>
        <section class="car-models-section">
            <div class="models-showcase">
                <h2>Автомобили <?php echo mb_strtolower($article->title); ?></h2>
                
                <div class="models-grid">
                    <?php
                    $carModels = [
                        'economy' => [
                            ['name' => 'Hyundai Solaris', 'year' => '2020-2024', 'features' => 'Кондиционер, ABS, подушки безопасности'],
                            ['name' => 'Kia Rio', 'year' => '2019-2024', 'features' => 'Современная мультимедиа, экономичный расход'],
                            ['name' => 'Chevrolet Aveo', 'year' => '2018-2023', 'features' => 'Просторный салон, надежная подвеска']
                        ],
                        'comfort' => [
                            ['name' => 'Toyota Camry', 'year' => '2020-2024', 'features' => 'Кожаный салон, климат-контроль, премиум аудио'],
                            ['name' => 'Hyundai Elantra', 'year' => '2021-2024', 'features' => 'Панорамная крыша, подогрев сидений'],
                            ['name' => 'Volkswagen Polo', 'year' => '2020-2024', 'features' => 'Немецкое качество, цифровая панель']
                        ],
                        'business' => [
                            ['name' => 'Mercedes E-класс', 'year' => '2019-2024', 'features' => 'Массаж сидений, панорамная крыша, Burmester аудио'],
                            ['name' => 'BMW 5 серии', 'year' => '2020-2024', 'features' => 'Спортивные сиденья, лазерная оптика'],
                            ['name' => 'Audi A6', 'year' => '2021-2024', 'features' => 'Виртуальная панель, quattro привод']
                        ],
                        'minivan' => [
                            ['name' => 'Mercedes Vito', 'year' => '2019-2024', 'features' => '6-8 мест, двухзонный климат, багажник 1300л'],
                            ['name' => 'Volkswagen Crafter', 'year' => '2020-2024', 'features' => '8 мест, высокий потолок, удобная посадка'],
                            ['name' => 'Hyundai H1', 'year' => '2018-2023', 'features' => '7 мест, экономичный двигатель, комфортные сиденья']
                        ]
                    ];
                    
                    $models = $carModels[$carClass] ?? $carModels['comfort'];
                    ?>
                    
                    <?php foreach ($models as $model) : ?>
                        <div class="model-card">
                            <div class="model-image">
                                <img src="/images/cars/<?php echo strtolower(str_replace([' ', '-'], '_', $model['name'])); ?>.jpg" 
                                     alt="<?php echo $model['name']; ?>" 
                                     loading="lazy"
                                     onerror="this.src='/images/cars/placeholder.jpg'">
                            </div>
                            <div class="model-details">
                                <h3 class="model-name"><?php echo $model['name']; ?></h3>
                                <p class="model-year">Год: <?php echo $model['year']; ?></p>
                                <p class="model-features"><?php echo $model['features']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Main Article Content -->
    <div class="article-content">
        
        <!-- Intro Text -->
        <?php if ($article->introtext) : ?>
            <div class="article-intro">
                <?php echo $article->introtext; ?>
            </div>
        <?php endif; ?>
        
        <!-- Full Text -->
        <?php if ($article->fulltext) : ?>
            <div class="article-fulltext">
                <?php echo $article->fulltext; ?>
            </div>
        <?php endif; ?>
        
    </div>

    <!-- Alternative Classes (for airport articles) -->
    <?php if ($isAirportArticle && $destination) : ?>
        <section class="alternative-classes">
            <h2>Другие классы автомобилей</h2>
            <p>Выберите подходящий класс для поездки <?php echo $direction === 'from' ? 'из' : 'в'; ?> 
               <?php 
               $airportNames = [
                   'domodedovo' => 'Домодедово',
                   'sheremetyevo' => 'Шереметьево',
                   'vnukovo' => 'Внуково',
                   'zhukovsky' => 'Жуковский'
               ];
               echo $airportNames[$destination] ?? $destination;
               ?>
            </p>
            
            <div class="classes-comparison">
                <?php
                $allClasses = ['economy', 'comfort', 'business', 'minivan'];
                foreach ($allClasses as $class) :
                    if ($class === $carClass) continue; // Skip current class
                    
                    $classData = [
                        'economy' => ['title' => 'Эконом класс', 'cars' => 'Hyundai Solaris, Kia Rio', 'icon' => '🚗'],
                        'comfort' => ['title' => 'Комфорт класс', 'cars' => 'Toyota Camry, Hyundai Elantra', 'icon' => '🚖'],
                        'business' => ['title' => 'Бизнес класс', 'cars' => 'Mercedes E, BMW 5, Audi A6', 'icon' => '🚘'],
                        'minivan' => ['title' => 'Минивэн', 'cars' => 'Mercedes Vito, VW Crafter', 'icon' => '🚐']
                    ];
                    
                    $data = $classData[$class];
                    $price = $pricing[$class] ?? 1000;
                ?>
                    
                    <div class="alt-class-card <?php echo $class; ?>">
                        <div class="alt-class-icon"><?php echo $data['icon']; ?></div>
                        <div class="alt-class-info">
                            <h3><?php echo $data['title']; ?></h3>
                            <p class="alt-class-cars"><?php echo $data['cars']; ?></p>
                            <div class="alt-class-price">от <?php echo $price; ?>₽</div>
                        </div>
                        <div class="alt-class-action">
                            <button class="btn btn-outline order-btn" 
                                    data-car-type="<?php echo $class; ?>"
                                    data-destination="<?php echo $destination; ?>"
                                    data-direction="<?php echo $direction; ?>">
                                Заказать
                            </button>
                        </div>
                    </div>
                    
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- FAQ Section for Airport Articles -->
    <?php if ($isAirportArticle) : ?>
        <section class="service-faq">
            <h2>Частые вопросы</h2>
            <div class="faq-list">
                <div class="faq-item">
                    <h3>Сколько времени занимает поездка?</h3>
                    <p>В среднем <?php echo $this->getTravelTime($destination); ?> минут без учета пробок. 
                       В час пик время может увеличиться на 15-20 минут.</p>
                </div>
                
                <div class="faq-item">
                    <h3>Включена ли подача автомобиля?</h3>
                    <p>Да, подача автомобиля к адресу отправления включена в стоимость. 
                       Дополнительно оплачивается только платная парковка (если требуется).</p>
                </div>
                
                <?php if ($direction === 'from') : ?>
                    <div class="faq-item">
                        <h3>Как происходит встреча в аэропорту?</h3>
                        <p>Водитель встречает вас в зале прилета с персональной табличкой с вашей фамилией. 
                           Ожидание входит в стоимость поездки (до 30 минут).</p>
                    </div>
                <?php else : ?>
                    <div class="faq-item">
                        <h3>За сколько до вылета заказывать?</h3>
                        <p>Рекомендуем заказывать за 3-4 часа до вылета для внутренних рейсов 
                           и за 4-5 часов для международных с учетом возможных пробок.</p>
                    </div>
                <?php endif; ?>
                
                <div class="faq-item">
                    <h3>Можно ли оплатить картой?</h3>
                    <p>Да, принимаем оплату картами Visa, MasterCard, МИР. 
                       Также возможна оплата наличными водителю.</p>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Related Services -->
    <?php if ($isAirportArticle || $isClassArticle) : ?>
        <section class="related-services">
            <h2>Другие направления</h2>
            <div class="related-grid">
                
                <?php if ($isAirportArticle) : ?>
                    <!-- Other airports -->
                    <?php
                    $otherAirports = array_diff(['domodedovo', 'sheremetyevo', 'vnukovo', 'zhukovsky'], [$destination]);
                    foreach (array_slice($otherAirports, 0, 3) as $airport) :
                        $airportNames = [
                            'domodedovo' => 'Домодедово',
                            'sheremetyevo' => 'Шереметьево', 
                            'vnukovo' => 'Внуково',
                            'zhukovsky' => 'Жуковский'
                        ];
                        $name = $airportNames[$airport];
                        $relatedPrice = ($pricing[$carClass] ?? 1000);
                    ?>
                        <div class="related-item">
                            <h4><?php echo $carClass === 'economy' ? 'Эконом' : ucfirst($carClass); ?> в <?php echo $name; ?></h4>
                            <p class="related-price">от <?php echo $relatedPrice; ?>₽</p>
                            <a href="/airports/to-<?php echo $airport; ?>/<?php echo $carClass; ?>" class="btn btn-outline btn-sm">
                                Подробнее
                            </a>
                        </div>
                    <?php endforeach; ?>
                    
                <?php else : ?>
                    <!-- Popular destinations for class articles -->
                    <div class="related-item">
                        <h4>В Домодедово</h4>
                        <p class="related-price">от <?php echo $pricing['domodedovo'] ?? 800; ?>₽</p>
                        <a href="/airports/to-domodedovo/<?php echo $carClass; ?>" class="btn btn-outline btn-sm">Заказать</a>
                    </div>
                    
                    <div class="related-item">
                        <h4>В Шереметьево</h4>
                        <p class="related-price">от <?php echo $pricing['sheremetyevo'] ?? 900; ?>₽</p>
                        <a href="/airports/to-sheremetyevo/<?php echo $carClass; ?>" class="btn btn-outline btn-sm">Заказать</a>
                    </div>
                    
                    <div class="related-item">
                        <h4>Во Внуково</h4>
                        <p class="related-price">от <?php echo $pricing['vnukovo'] ?? 700; ?>₽</p>
                        <a href="/airports/to-vnukovo/<?php echo $carClass; ?>" class="btn btn-outline btn-sm">Заказать</a>
                    </div>
                <?php endif; ?>
                
            </div>
        </section>
    <?php endif; ?>

    <!-- Article Meta Information -->
    <?php if ($params->get('show_create_date') || $params->get('show_modify_date') || $params->get('show_author')) : ?>
        <div class="article-meta">
            <?php if ($params->get('show_author') && !empty($article->author)) : ?>
                <span class="article-author">
                    Автор: <?php echo $article->author; ?>
                </span>
            <?php endif; ?>
            
            <?php if ($params->get('show_create_date')) : ?>
                <time class="article-date" datetime="<?php echo HTMLHelper::_('date', $article->created, 'c'); ?>">
                    <?php echo HTMLHelper::_('date', $article->created, Text::_('DATE_FORMAT_LC3')); ?>
                </time>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</article>

<!-- Schema.org structured data for SEO -->
<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "Service",
  "name": "<?php echo htmlspecialchars($article->title); ?>",
  "description": "<?php echo htmlspecialchars(strip_tags($article->introtext)); ?>",
  "provider": {
    "@type": "Organization",
    "name": "Служба такси 755",
    "url": "https://taxi755.ru",
    "telephone": "+7-926-641-08-96",
    "address": {
      "@type": "PostalAddress", 
      "addressCountry": "RU",
      "addressLocality": "Москва",
      "streetAddress": "ул. Маломосковская, 22с1"
    }
  }
  <?php if ($currentPrice && ($isAirportArticle || $isClassArticle)) : ?>
  ,
  "offers": {
    "@type": "Offer",
    "price": "<?php echo $currentPrice; ?>",
    "priceCurrency": "RUB",
    "description": "Базовая стоимость поездки"
  }
  <?php endif; ?>
  <?php if ($isAirportArticle && $destination) : ?>
  ,
  "serviceArea": {
    "@type": "Place",
    "name": "Аэропорт <?php echo $airportNames[$destination] ?? $destination; ?>"
  }
  <?php endif; ?>
}
</script>

<style>
/* Landing page specific styles */
.service-landing {
    max-width: 1000px;
    margin: 0 auto;
}

.article-hero {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    align-items: center;
    margin-bottom: 3rem;
}

.hero-image {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.hero-image img {
    width: 100%;
    height: 300px;
    object-fit: cover;
}

.hero-content {
    padding: 1rem;
}

.article-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #333;
    line-height: 1.2;
}

.price-hero {
    display: flex;
    align-items: center;
    gap: 2rem;
    margin: 1.5rem 0;
    padding: 1rem;
    background: #F8F9FA;
    border-radius: 15px;
    border-left: 5px solid #FFD700;
}

.price-amount {
    font-size: 2.5rem;
    font-weight: 700;
    color: #FFD700;
}

.travel-time,
.travel-distance {
    font-size: 1rem;
    color: #666;
    display: flex;
    align-items: center;
}

.travel-time:before {
    content: '⏱️';
    margin-right: 0.5rem;
}

.travel-distance:before {
    content: '📍';
    margin-right: 0.5rem;
}

/* Quick order section */
.quick-order-section {
    margin: 3rem 0;
    padding: 2rem 0;
    background: linear-gradient(135deg, #FFFBF0 0%, #FFF8E1 100%);
    border-radius: 20px;
}

.order-card {
    max-width: 600px;
    margin: 0 auto;
    text-align: center;
}

.order-header h2 {
    color: #333;
    margin-bottom: 0.5rem;
}

.order-header p {
    color: #666;
    margin-bottom: 2rem;
}

.service-details {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    margin: 1.5rem 0;
    text-align: left;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 500;
    color: #666;
}

.detail-value {
    font-weight: 600;
    color: #333;
}

.detail-value.price {
    font-size: 1.25rem;
    color: #FFD700;
}

.detail-value.economy { color: #28a745; }
.detail-value.comfort { color: #FFD700; }
.detail-value.business { color: #6f42c1; }
.detail-value.minivan { color: #fd7e14; }

.order-buttons {
    margin-top: 2rem;
}

.phone-cta {
    margin-top: 1rem;
    color: #666;
}

.phone-link {
    color: #333;
    font-weight: 600;
    text-decoration: none;
}

.phone-link:hover {
    color: #FFD700;
}

/* Car models section */
.models-showcase {
    margin: 3rem 0;
}

.models-showcase h2 {
    text-align: center;
    margin-bottom: 2rem;
    color: #333;
}

.models-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.model-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: transform 0.3s ease;
}

.model-card:hover {
    transform: translateY(-3px);
}

.model-image {
    height: 180px;
    background: #F8F9FA;
    overflow: hidden;
}

.model-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.model-details {
    padding: 1.5rem;
}

.model-name {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #333;
}

.model-year {
    color: #FFD700;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.model-features {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.5;
}

/* Alternative classes */
.alternative-classes {
    margin: 3rem 0;
    padding: 2rem;
    background: #F8F9FA;
    border-radius: 20px;
}

.alternative-classes h2 {
    text-align: center;
    margin-bottom: 1rem;
}

.alternative-classes > p {
    text-align: center;
    color: #666;
    margin-bottom: 2rem;
}

.classes-comparison {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.alt-class-card {
    background: white;
    padding: 1.5rem;
    border-radius: 15px;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
    border-left: 4px solid #FFD700;
}

.alt-class-card.economy { border-left-color: #28a745; }
.alt-class-card.comfort { border-left-color: #FFD700; }
.alt-class-card.business { border-left-color: #6f42c1; }
.alt-class-card.minivan { border-left-color: #fd7e14; }

.alt-class-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.alt-class-icon {
    font-size: 2rem;
    flex-shrink: 0;
}

.alt-class-info {
    flex-grow: 1;
}

.alt-class-info h3 {
    margin-bottom: 0.25rem;
    font-size: 1rem;
    color: #333;
}

.alt-class-cars {
    font-size: 0.8rem;
    color: #666;
    margin-bottom: 0.5rem;
}

.alt-class-price {
    font-weight: 600;
    color: #FFD700;
}

.alt-class-action {
    flex-shrink: 0;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}

/* FAQ Section */
.service-faq {
    margin: 3rem 0;
}

.service-faq h2 {
    text-align: center;
    margin-bottom: 2rem;
}

.faq-list {
    max-width: 800px;
    margin: 0 auto;
}

.faq-item {
    background: white;
    padding: 1.5rem;
    margin-bottom: 1rem;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border-left: 4px solid #FFD700;
}

.faq-item h3 {
    color: #333;
    margin-bottom: 0.5rem;
    font-size: 1.125rem;
}

.faq-item p {
    color: #666;
    line-height: 1.6;
    margin: 0;
}

/* Related services */
.related-services {
    margin: 3rem 0;
}

.related-services h2 {
    text-align: center;
    margin-bottom: 2rem;
}

.related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.related-item {
    background: white;
    padding: 1.5rem;
    text-align: center;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    transition: transform 0.3s ease;
}

.related-item:hover {
    transform: translateY(-3px);
}

.related-item h4 {
    margin-bottom: 0.5rem;
    color: #333;
}

.related-price {
    font-weight: 600;
    color: #FFD700;
    margin-bottom: 1rem;
}

/* Article content styling */
.article-content {
    margin: 2rem 0;
    line-height: 1.7;
}

.article-intro {
    font-size: 1.125rem;
    margin-bottom: 2rem;
}

.article-fulltext {
    margin-top: 2rem;
}

.article-meta {
    margin-top: 2rem;
    padding-top: 1rem;
    border-top: 1px solid #E5E5E5;
    font-size: 0.875rem;
    color: #666;
    text-align: center;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .article-hero {
        grid-template-columns: 1fr;
        text-align: center;
    }
    
    .article-title {
        font-size: 2rem;
    }
    
    .price-hero {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }
    
    .price-amount {
        font-size: 2rem;
    }
    
    .models-grid {
        grid-template-columns: 1fr;
    }
    
    .classes-comparison {
        grid-template-columns: 1fr;
    }
    
    .alt-class-card {
        flex-direction: column;
        text-align: center;
    }
    
    .related-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .article-title {
        font-size: 1.75rem;
    }
    
    .quick-order-section {
        margin: 2rem -1rem;
        border-radius: 0;
    }
    
    .order-card {
        padding: 0 1rem;
    }
    
    .service-details {
        margin: 1rem 0;
    }
}
</style>

<?php
// Helper functions for the template
if (!function_exists('getTravelTime')) {
    function getTravelTime($airport) {
        $times = [
            'domodedovo' => 45,
            'sheremetyevo' => 40,
            'vnukovo' => 35,
            'zhukovsky' => 70
        ];
        return $times[$airport] ?? 45;
    }
}

if (!function_exists('getDistance')) {
    function getDistance($airport) {
        $distances = [
            'domodedovo' => 42,
            'sheremetyevo' => 35,
            'vnukovo' => 28,
            'zhukovsky' => 65
        ];
        return $distances[$airport] ?? 40;
    }
}

// Make helpers available to view
if (!method_exists($this, 'getTravelTime')) {
    $this->getTravelTime = 'getTravelTime';
}
if (!method_exists($this, 'getDistance')) {
    $this->getDistance = 'getDistance';
}
?>