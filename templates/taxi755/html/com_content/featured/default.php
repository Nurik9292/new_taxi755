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
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper;

$app = Factory::getApplication();
$templateParams = $app->getTemplate(true)->params;
$items = $this->items;
$params = $this->params;

// Get enabled home sections
$enabledSections = $templateParams->get('home_sections', 'services,features,directions,reviews');
$enabledSectionsArray = explode(',', $enabledSections);

?>

<div class="featured-content home-page">

    <!-- Main Featured Article -->
    <?php if (!empty($items)) : ?>
        <?php $mainArticle = $items[0]; ?>
        <article class="main-featured-article">
            <div class="featured-content-wrapper">
                <?php echo $mainArticle->introtext; ?>
                <?php if ($mainArticle->fulltext) : ?>
                    <?php echo $mainArticle->fulltext; ?>
                <?php endif; ?>
            </div>
        </article>
    <?php endif; ?>

    <!-- Services Section -->
    <?php if (in_array('services', $enabledSectionsArray)) : ?>
        <section class="home-services-featured">
            <div class="container">
                <h2 class="section-title">Наши услуги</h2>
                <p class="section-description">
                    Выберите подходящий класс автомобиля для комфортной поездки
                </p>
                
                <div class="services-showcase">
                    <?php
                    // Service data with real pricing
                    $services = [
                        [
                            'class' => 'economy',
                            'title' => 'Эконом класс',
                            'price' => 800,
                            'description' => 'Оптимальное соотношение цены и качества',
                            'models' => ['Hyundai Solaris', 'Kia Rio', 'Chevrolet Aveo'],
                            'features' => ['Кондиционер', 'Багажник 400л', '4 места'],
                            'link' => '/classes/economy',
                            'popular_routes' => [
                                ['name' => 'В Домодедово', 'price' => 800],
                                ['name' => 'В Шереметьево', 'price' => 900],
                                ['name' => 'Во Внуково', 'price' => 700]
                            ]
                        ],
                        [
                            'class' => 'comfort',
                            'title' => 'Комфорт класс',
                            'price' => 1000,
                            'description' => 'Повышенный комфорт для деловых поездок',
                            'models' => ['Toyota Camry', 'Hyundai Elantra', 'VW Polo'],
                            'features' => ['Климат-контроль', 'Кожаный салон', 'Premium аудио'],
                            'link' => '/classes/comfort',
                            'popular_routes' => [
                                ['name' => 'В Домодедово', 'price' => 1200],
                                ['name' => 'В Шереметьево', 'price' => 1300],
                                ['name' => 'Во Внуково', 'price' => 1100]
                            ]
                        ],
                        [
                            'class' => 'business',
                            'title' => 'Бизнес класс',
                            'price' => 1500,
                            'description' => 'Премиум автомобили для VIP клиентов',
                            'models' => ['Mercedes E-класс', 'BMW 5 серии', 'Audi A6'],
                            'features' => ['Массаж сидений', 'Панорамная крыша', 'Wi-Fi'],
                            'link' => '/classes/business',
                            'popular_routes' => [
                                ['name' => 'В Домодедово', 'price' => 1800],
                                ['name' => 'В Шереметьево', 'price' => 1900],
                                ['name' => 'Во Внуково', 'price' => 1700]
                            ]
                        ],
                        [
                            'class' => 'minivan',
                            'title' => 'Минивэн',
                            'price' => 1200,
                            'description' => 'Вместительные автомобили для групп до 8 человек',
                            'models' => ['Mercedes Vito', 'VW Crafter', 'Hyundai H1'],
                            'features' => ['6-8 мест', 'Большой багажник', 'Удобная посадка'],
                            'link' => '/classes/minivan',
                            'popular_routes' => [
                                ['name' => 'В Домодедово', 'price' => 1400],
                                ['name' => 'В Шереметьево', 'price' => 1500],
                                ['name' => 'Во Внуково', 'price' => 1300]
                            ]
                        ]
                    ];
                    ?>
                    
                    <?php foreach ($services as $service) : ?>
                        <div class="service-showcase-card <?php echo $service['class']; ?>">
                            <div class="service-header">
                                <div class="service-icon">
                                    <?php
                                    $icons = [
                                        'economy' => '🚗',
                                        'comfort' => '🚖',
                                        'business' => '🚘',
                                        'minivan' => '🚐'
                                    ];
                                    echo $icons[$service['class']];
                                    ?>
                                </div>
                                <div class="service-title-block">
                                    <h3 class="service-title"><?php echo $service['title']; ?></h3>
                                    <div class="service-price">от <?php echo $service['price']; ?>₽</div>
                                </div>
                            </div>
                            
                            <div class="service-body">
                                <p class="service-description"><?php echo $service['description']; ?></p>
                                
                                <div class="service-models">
                                    <h4>Автомобили:</h4>
                                    <ul>
                                        <?php foreach ($service['models'] as $model) : ?>
                                            <li><?php echo $model; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                
                                <div class="service-features">
                                    <h4>Особенности:</h4>
                                    <div class="features-tags">
                                        <?php foreach ($service['features'] as $feature) : ?>
                                            <span class="feature-tag"><?php echo $feature; ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                
                                <div class="popular-routes">
                                    <h4>Популярные маршруты:</h4>
                                    <div class="routes-list">
                                        <?php foreach ($service['popular_routes'] as $route) : ?>
                                            <div class="route-item">
                                                <span class="route-name"><?php echo $route['name']; ?></span>
                                                <span class="route-price">от <?php echo $route['price']; ?>₽</span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="service-footer">
                                <a href="<?php echo $service['link']; ?>" class="btn btn-outline">
                                    Подробнее
                                </a>
                                <button class="btn btn-primary order-btn" 
                                        data-car-type="<?php echo $service['class']; ?>">
                                    Заказать
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Popular Destinations -->
    <?php if (in_array('directions', $enabledSectionsArray)) : ?>
        <section class="popular-destinations">
            <div class="container">
                <h2 class="section-title">Популярные направления</h2>
                
                <div class="destinations-showcase">
                    
                    <!-- Airports -->
                    <div class="destination-category airports">
                        <div class="category-header">
                            <h3 class="category-icon">✈️</h3>
                            <div class="category-info">
                                <h4>Аэропорты Москвы</h4>
                                <p>Комфортные трансферы во все аэропорты</p>
                            </div>
                        </div>
                        
                        <div class="destinations-grid">
                            <?php
                            $airports = [
                                ['name' => 'Домодедово', 'code' => 'domodedovo', 'distance' => '42 км', 'time' => '45 мин', 'price' => 800],
                                ['name' => 'Шереметьево', 'code' => 'sheremetyevo', 'distance' => '35 км', 'time' => '40 мин', 'price' => 900],
                                ['name' => 'Внуково', 'code' => 'vnukovo', 'distance' => '28 км', 'time' => '35 мин', 'price' => 700],
                                ['name' => 'Жуковский', 'code' => 'zhukovsky', 'distance' => '65 км', 'time' => '70 мин', 'price' => 1200]
                            ];
                            ?>
                            
                            <?php foreach ($airports as $airport) : ?>
                                <div class="destination-item airport-<?php echo $airport['code']; ?>">
                                    <div class="destination-header">
                                        <h5>Аэропорт <?php echo $airport['name']; ?></h5>
                                        <div class="destination-stats">
                                            <span class="stat distance"><?php echo $airport['distance']; ?></span>
                                            <span class="stat time"><?php echo $airport['time']; ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="destination-options">
                                        <a href="/airports/to-<?php echo $airport['code']; ?>" class="option-link to-airport">
                                            <span class="option-label">В аэропорт</span>
                                            <span class="option-price">от <?php echo $airport['price']; ?>₽</span>
                                        </a>
                                        
                                        <a href="/airports/from-<?php echo $airport['code']; ?>" class="option-link from-airport">
                                            <span class="option-label">Из аэропорта</span>
                                            <span class="option-price">от <?php echo $airport['price']; ?>₽</span>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="category-footer">
                            <a href="/airports" class="btn btn-yellow">Все аэропорты</a>
                        </div>
                    </div>

                    <!-- Train Stations -->
                    <div class="destination-category stations">
                        <div class="category-header">
                            <h3 class="category-icon">🚂</h3>
                            <div class="category-info">
                                <h4>Вокзалы Москвы</h4>
                                <p>Трансферы на все вокзалы столицы</p>
                            </div>
                        </div>
                        
                        <div class="stations-list">
                            <?php
                            $stations = [
                                ['name' => 'Ярославский', 'area' => 'Комсомольская площадь', 'price' => 400],
                                ['name' => 'Казанский', 'area' => 'Комсомольская площадь', 'price' => 450],
                                ['name' => 'Ленинградский', 'area' => 'Комсомольская площадь', 'price' => 500],
                                ['name' => 'Курский', 'area' => 'Садовое кольцо', 'price' => 350],
                                ['name' => 'Белорусский', 'area' => 'Тверская застава', 'price' => 380],
                                ['name' => 'Киевский', 'area' => 'Дорогомилово', 'price' => 420]
                            ];
                            ?>
                            
                            <?php foreach (array_chunk($stations, 3) as $stationGroup) : ?>
                                <div class="stations-row">
                                    <?php foreach ($stationGroup as $station) : ?>
                                        <div class="station-item">
                                            <h5><?php echo $station['name']; ?> вокзал</h5>
                                            <p class="station-area"><?php echo $station['area']; ?></p>
                                            <div class="station-price">от <?php echo $station['price']; ?>₽</div>
                                            <button class="btn btn-outline btn-sm order-btn" 
                                                    data-car-type="comfort" 
                                                    data-destination="<?php echo strtolower($station['name']); ?>_station">
                                                Заказать
                                            </button>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="category-footer">
                            <a href="/stations" class="btn btn-yellow">Все вокзалы</a>
                        </div>
                    </div>

                    <!-- City Services -->
                    <div class="destination-category city-services">
                        <div class="category-header">
                            <h3 class="category-icon">🌆</h3>
                            <div class="category-info">
                                <h4>Поездки по городу</h4>
                                <p>Комфортные поездки в пределах МКАД</p>
                            </div>
                        </div>
                        
                        <div class="city-options">
                            <div class="city-option">
                                <h5>Почасовая аренда</h5>
                                <p>Персональный водитель на несколько часов</p>
                                <div class="option-pricing">
                                    <span class="hourly-rate">от 500₽/час</span>
                                    <span class="min-hours">минимум 3 часа</span>
                                </div>
                                <button class="btn btn-primary order-btn" data-car-type="comfort" data-service="hourly">
                                    Заказать на час
                                </button>
                            </div>
                            
                            <div class="city-option">
                                <h5>Деловые встречи</h5>
                                <p>Поездки по делам с ожиданием</p>
                                <div class="option-pricing">
                                    <span class="business-rate">от 400₽</span>
                                    <span class="wait-time">ожидание 15 мин бесплатно</span>
                                </div>
                                <button class="btn btn-primary order-btn" data-car-type="comfort" data-service="business">
                                    Заказать
                                </button>
                            </div>
                        </div>
                        
                        <div class="category-footer">
                            <a href="/city" class="btn btn-yellow">Все услуги по городу</a>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Calculator Section -->
    <section class="home-calculator">
        <div class="container">
            <div class="calculator-widget">
                <h2>Рассчитать стоимость поездки</h2>
                <p>Узнайте примерную стоимость до оформления заказа</p>
                
                <form class="calc-form" id="taxi-calculator">
                    <div class="calc-inputs">
                        <div class="input-group">
                            <label for="calc-from">Откуда:</label>
                            <input type="text" id="calc-from" name="from_address" 
                                   placeholder="Укажите адрес отправления" required>
                        </div>
                        
                        <div class="input-group">
                            <label for="calc-to">Куда:</label>
                            <input type="text" id="calc-to" name="to_address" 
                                   placeholder="Укажите адрес назначения" required>
                        </div>
                        
                        <div class="input-group">
                            <label for="calc-class">Класс автомобиля:</label>
                            <select id="calc-class" name="car_class" required>
                                <option value="">Выберите класс</option>
                                <option value="economy">Эконом (от 25₽/км)</option>
                                <option value="comfort">Комфорт (от 35₽/км)</option>
                                <option value="business">Бизнес (от 50₽/км)</option>
                                <option value="minivan">Минивэн (от 40₽/км)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="calc-result">
                        <button type="button" class="btn btn-outline calc-btn">
                            Рассчитать стоимость
                        </button>
                        
                        <div class="calc-output" id="calc-result" style="display: none;">
                            <div class="result-info">
                                <div class="result-price">Примерная стоимость: <span id="result-price">-</span>₽</div>
                                <div class="result-details">
                                    <span id="result-distance">-</span> км, 
                                    <span id="result-time">-</span> мин
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                Заказать за <span id="order-price">-</span>₽
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Features -->
    <?php if (in_array('features', $enabledSectionsArray)) : ?>
        <section class="why-choose-us">
            <div class="container">
                <h2 class="section-title">Почему выбирают Такси 755</h2>
                
                <div class="advantages-grid">
                    <div class="advantage-item reliability">
                        <div class="advantage-icon">🛡️</div>
                        <h3>Надежность</h3>
                        <p>Работаем с 2010 года. Более 50,000 довольных клиентов. 
                           Проверенные водители с опытом работы от 3 лет.</p>
                        <ul class="advantage-details">
                            <li>Все водители с лицензией</li>
                            <li>Регулярные медосмотры</li>
                            <li>Страхование пассажиров</li>
                        </ul>
                    </div>
                    
                    <div class="advantage-item speed">
                        <div class="advantage-icon">⚡</div>
                        <h3>Быстрая подача</h3>
                        <p>Средняя подача автомобиля 8-12 минут. 
                           Онлайн отслеживание приближения машины в реальном времени.</p>
                        <ul class="advantage-details">
                            <li>GPS мониторинг</li>
                            <li>SMS уведомления</li>
                            <li>Точное время подачи</li>
                        </ul>
                    </div>
                    
                    <div class="advantage-item pricing">
                        <div class="advantage-icon">💰</div>
                        <h3>Честные цены</h3>
                        <p>Фиксированная стоимость без скрытых доплат. 
                           Цена известна до посадки в автомобиль.</p>
                        <ul class="advantage-details">
                            <li>Без доплат за пробки</li>
                            <li>Фиксированная цена в аэропорты</li>
                            <li>Скидки постоянным клиентам</li>
                        </ul>
                    </div>
                    
                    <div class="advantage-item comfort">
                        <div class="advantage-icon">⭐</div>
                        <h3>Комфорт поездки</h3>
                        <p>Только современные автомобили с кондиционером. 
                           Вежливые водители, чистый салон, приятная музыка.</p>
                        <ul class="advantage-details">
                            <li>Автомобили не старше 7 лет</li>
                            <li>Ежедневная мойка и уборка</li>
                            <li>Бесплатная вода и зарядка</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Testimonials Preview -->
    <?php if (in_array('reviews', $enabledSectionsArray)) : ?>
        <section class="home-testimonials">
            <div class="container">
                <h2 class="section-title">Отзывы клиентов</h2>
                
                <div class="testimonials-preview">
                    <?php
                    // Sample testimonials (replace with real data from database)
                    $testimonials = [
                        [
                            'author' => 'Анна К.',
                            'rating' => 5,
                            'text' => 'Отличное такси! Водитель приехал точно в срок, машина чистая и комфортная. Довезли в Домодедово быстро и аккуратно.',
                            'service' => 'Трансфер в Домодедово',
                            'date' => '15.08.2025'
                        ],
                        [
                            'author' => 'Михаил П.',
                            'rating' => 5,
                            'text' => 'Заказывали минивэн для семьи. Вместились все с багажом, водитель помог донести чемоданы. Рекомендую!',
                            'service' => 'Минивэн в Шереметьево',
                            'date' => '12.08.2025'
                        ],
                        [
                            'author' => 'Елена В.',
                            'rating' => 5,
                            'text' => 'Пользуюсь услугами уже 2 года. Всегда качественно и в срок. Цены адекватные, водители вежливые.',
                            'service' => 'Постоянный клиент',
                            'date' => '10.08.2025'
                        ]
                    ];
                    ?>
                    
                    <?php foreach ($testimonials as $review) : ?>
                        <div class="testimonial-card">
                            <div class="testimonial-header">
                                <div class="testimonial-author">
                                    <span class="author-name"><?php echo $review['author']; ?></span>
                                    <div class="author-rating">
                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                            <span class="star <?php echo $i <= $review['rating'] ? 'filled' : ''; ?>">★</span>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <div class="testimonial-service"><?php echo $review['service']; ?></div>
                            </div>
                            
                            <div class="testimonial-content">
                                <p>"<?php echo $review['text']; ?>"</p>
                            </div>
                            
                            <div class="testimonial-footer">
                                <span class="review-date"><?php echo $review['date']; ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="testimonials-footer">
                    <a href="/reviews" class="btn btn-outline">Читать все отзывы</a>
                    <a href="/reviews/add" class="btn btn-yellow">Оставить отзыв</a>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Call to Action Section -->
    <section class="home-cta">
        <div class="container">
            <div class="cta-content">
                <h2>Готовы заказать такси?</h2>
                <p>Оформите заказ онлайн или позвоните нашему диспетчеру</p>
                
                <div class="cta-options">
                    <button class="btn btn-primary btn-xl order-btn" data-car-type="any">
                        Заказать онлайн
                    </button>
                    
                    <div class="cta-divider">или</div>
                    
                    <a href="tel:+79266410896" class="btn btn-outline btn-xl">
                        Позвонить: +7 (926) 641-08-96
                    </a>
                </div>
                
                <div class="cta-features">
                    <div class="cta-feature">Подача 8-12 минут</div>
                    <div class="cta-feature">Фиксированные цены</div>
                    <div class="cta-feature">Оплата картой и наличными</div>
                </div>
            </div>
        </div>
    </section>

</div>

<style>
/* Featured Content Styles */
.featured-content {
    margin: 0;
}

.main-featured-article {
    margin-bottom: 3rem;
}

.featured-content-wrapper {
    text-align: center;
    padding: 2rem;
    background: linear-gradient(135deg, #FFFFFF 0%, #FFFBF0 100%);
    border-radius: 20px;
}

/* Services Showcase */
.home-services-featured {
    padding: 4rem 0;
    background: #FFFFFF;
}

.services-showcase {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin: 3rem 0;
}

.service-showcase-card {
    background: #FFFFFF;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border-left: 5px solid #FFD700;
}

.service-showcase-card.economy { border-left-color: #28a745; }
.service-showcase-card.comfort { border-left-color: #FFD700; }
.service-showcase-card.business { border-left-color: #6f42c1; }
.service-showcase-card.minivan { border-left-color: #fd7e14; }

.service-showcase-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(255,215,0,0.2);
}

.service-header {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    background: #F8F9FA;
}

.service-icon {
    font-size: 2.5rem;
    margin-right: 1rem;
}

.service-title-block {
    flex-grow: 1;
}

.service-title {
    margin: 0 0 0.25rem 0;
    color: #333;
    font-size: 1.25rem;
}

.service-price {
    font-size: 1.5rem;
    font-weight: 700;
    color: #FFD700;
}

.service-showcase-card.economy .service-price { color: #28a745; }
.service-showcase-card.comfort .service-price { color: #FFD700; }
.service-showcase-card.business .service-price { color: #6f42c1; }
.service-showcase-card.minivan .service-price { color: #fd7e14; }

.service-body {
    padding: 1.5rem;
}

.service-description {
    color: #666;
    margin-bottom: 1rem;
    line-height: 1.6;
}

.service-models h4,
.service-features h4,
.popular-routes h4 {
    font-size: 0.9rem;
    font-weight: 600;
    color: #333;
    margin: 1rem 0 0.5rem 0;
}

.service-models ul {
    list-style: none;
    padding: 0;
    margin: 0 0 1rem 0;
}

.service-models li {
    color: #666;
    font-size: 0.85rem;
    padding: 0.2rem 0;
}

.features-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.taxi755
 * @copyright   (C) 2025 Taxi755. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper;

$app = Factory::getApplication();
$templateParams = $app->getTemplate(true)->params;
$items = $this->items;
$params = $this->params;

// Get enabled home sections
$enabledSections = $templateParams->get('home_sections