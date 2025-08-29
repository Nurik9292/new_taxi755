<?php

/**
 * @package     Joomla.Site
 * @subpackage  Templates.taxi755
 * @copyright   (C) 2025 Taxi755. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

$app = Factory::getApplication();
$wa = $app->getDocument()->getWebAssetManager();

// Template parameters
$templateParams = $app->getTemplate(true)->params;
$sitename = $app->get('sitename');
$menu = $app->getMenu()->getActive();
$pageclass = $menu ? $menu->getParams()->get('pageclass_sfx', '') : '';

// Template settings
$logo = $templateParams->get('logo');
$sitename_display = $templateParams->get('sitename', $sitename);
$phone = $templateParams->get('phone', '+7 (926) 641-08-96');
$email = $templateParams->get('email', 'info@taxi755.ru');
$address = $templateParams->get('address', 'г. Москва, ул. Маломосковская, 22с1');


// SEDI integration
$sedi_enable = $templateParams->get('sedi_enable', 1);
$sedi_key = $templateParams->get('sedi_key', '1A047663-E78D-44C3-A043-C0A0D2960631');

// Advanced settings
$enable_sidebar = $templateParams->get('enable_sidebar', 1);

$templatePath = 'templates/' . $this->template;
$wa->registerAndUseStyle('template.style', $templatePath . '/css/template.css');
$wa->registerAndUseScript('template.script', $templatePath . '/js/template.js', [], ['defer' => true]);

// Load external libraries
HTMLHelper::_('bootstrap.framework');
HTMLHelper::_('formbehavior.chosen');

// Page class and body classes
$bodyClass = 'site-taxi755 ' . $pageclass;
if ($menu && $menu->home) {
    $bodyClass .= ' page-home';
}

// Get component info for body classes
$option = $app->input->getCmd('option', '');
$view = $app->input->getCmd('view', '');
$layout = $app->input->getCmd('layout', '');
$task = $app->input->getCmd('task', '');
$itemid = $app->input->getCmd('Itemid', '');

$bodyClass .= ' ' . $option
    . ($view ? ' view-' . $view : '')
    . ($layout ? ' layout-' . $layout : ' no-layout')
    . ($task ? ' task-' . $task : ' no-task')
    . ($itemid ? ' itemid-' . $itemid : '');

?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">

<head>
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
    <jdoc:include type="scripts" />

    <!-- Viewport for mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo Uri::root(); ?>templates/<?php echo $this->template; ?>/favicon.ico">

    <!-- Template CSS -->
    <link rel="stylesheet" href="<?php echo Uri::root(); ?>templates/<?php echo $this->template; ?>/css/template.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="<?php echo $bodyClass; ?>">

    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">

                <!-- Logo -->
                <div class="logo">
                    <jdoc:include type="modules" name="logo" style="none" />
                    <?php if (!$this->countModules('logo')) : ?>
                        <a href="<?php echo Uri::root(); ?>" class="logo-link">
                            <?php if ($logo) : ?>
                                <img src="<?php echo Uri::root() . $logo; ?>" alt="<?php echo $sitename_display; ?>" class="logo-img">
                            <?php else : ?>
                                <span class="logo-text">ТАКСИ 755</span>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Contact Info -->
                <div class="contact-info">
                    <jdoc:include type="modules" name="phone" style="none" />
                    <?php if (!$this->countModules('phone')) : ?>
                        <div class="phone-widget">
                            <div class="phone-icon">📞</div>
                            <div class="phone-details">
                                <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $phone); ?>" class="phone-number"><?php echo $phone; ?></a>
                                <span class="phone-note">Работаем 24/7</span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle" aria-label="Открыть меню">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="navigation">
        <div class="container">
            <jdoc:include type="modules" name="mainmenu" style="none" />
        </div>
    </nav>

    <!-- Hero Section (только на главной) -->
    <?php if ($menu && $menu->home) : ?>
        <section class="hero">
            <div class="container">
                <jdoc:include type="modules" name="hero" style="none" />
                <?php if (!$this->countModules('hero')) : ?>
                    <div class="hero-content">
                        <h1 class="hero-title">Надежное такси в Москве</h1>
                        <p class="hero-subtitle">Комфортные поездки по городу и трансферы в аэропорты на автомобилях всех классов</p>

                        <div class="hero-features">
                            <div class="feature">⏰ Работаем 24/7</div>
                            <div class="feature">💳 Онлайн оплата</div>
                            <div class="feature">🛡️ Проверенные водители</div>
                            <div class="feature">📍 GPS мониторинг</div>
                        </div>

                        <div class="hero-cta">
                            <button class="btn btn-primary btn-xl order-btn" data-car-type="any">
                                Заказать такси сейчас
                            </button>
                            <p class="phone-cta">
                                Или звоните: <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $phone); ?>"><?php echo $phone; ?></a>
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <div class="container">

            <!-- Breadcrumbs -->
            <?php if (!$menu || !$menu->home) : ?>
                <div class="breadcrumbs">
                    <jdoc:include type="modules" name="breadcrumbs" style="none" />
                </div>
            <?php endif; ?>

            <div class="content-wrapper">

                <!-- Left Column - Main Content -->
                <div class="main-column">

                    <!-- Messages -->
                    <jdoc:include type="message" />

                    <!-- Component Output -->
                    <jdoc:include type="component" />

                </div>

                <!-- Sidebar (if enabled) -->
                <?php if ($enable_sidebar && $this->countModules('sidebar')) : ?>
                    <aside class="sidebar">
                        <jdoc:include type="modules" name="sidebar" style="taxi755card" />
                    </aside>
                <?php endif; ?>

            </div>

        </div>
    </main>

    <!-- Home Page Sections (только на главной) -->
    <?php if ($menu && $menu->home) : ?>

        <!-- Services Section -->
        <section class="home-services">
            <div class="container">
                <jdoc:include type="modules" name="services" style="taxi755card" />
                <?php if (!$this->countModules('services')) : ?>
                    <!-- Default services content -->
                    <h2 class="section-title">Наши услуги</h2>
                    <div class="services-grid">
                        <div class="service-card economy">
                            <div class="service-image">
                                <img src="<?php echo Uri::root(); ?>images/services/economy.jpg" alt="Эконом класс" loading="lazy">
                            </div>
                            <div class="service-content">
                                <h3>Эконом класс</h3>
                                <p class="service-price">от 800₽</p>
                                <p>Hyundai Solaris, Kia Rio, Chevrolet Aveo</p>
                                <a href="/classes/economy" class="btn btn-outline">Подробнее</a>
                            </div>
                        </div>

                        <div class="service-card comfort">
                            <div class="service-image">
                                <img src="<?php echo Uri::root(); ?>images/services/comfort.jpg" alt="Комфорт класс" loading="lazy">
                            </div>
                            <div class="service-content">
                                <h3>Комфорт класс</h3>
                                <p class="service-price">от 1000₽</p>
                                <p>Toyota Camry, Hyundai Elantra, VW Polo</p>
                                <a href="/classes/comfort" class="btn btn-outline">Подробнее</a>
                            </div>
                        </div>

                        <div class="service-card business">
                            <div class="service-image">
                                <img src="<?php echo Uri::root(); ?>images/services/business.jpg" alt="Бизнес класс" loading="lazy">
                            </div>
                            <div class="service-content">
                                <h3>Бизнес класс</h3>
                                <p class="service-price">от 1500₽</p>
                                <p>Mercedes E-класс, BMW 5, Audi A6</p>
                                <a href="/classes/business" class="btn btn-outline">Подробнее</a>
                            </div>
                        </div>

                        <div class="service-card minivan">
                            <div class="service-image">
                                <img src="<?php echo Uri::root(); ?>images/services/minivan.jpg" alt="Минивэн" loading="lazy">
                            </div>
                            <div class="service-content">
                                <h3>Минивэн</h3>
                                <p class="service-price">от 1200₽</p>
                                <p>Mercedes Vito, VW Crafter (до 8 мест)</p>
                                <a href="/classes/minivan" class="btn btn-outline">Подробнее</a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Features Section -->
        <section class="home-features">
            <div class="container">
                <jdoc:include type="modules" name="features" style="none" />
                <?php if (!$this->countModules('features')) : ?>
                    <h2 class="section-title">Почему выбирают нас</h2>
                    <div class="features-grid">
                        <div class="feature-item">
                            <div class="feature-icon">⏰</div>
                            <h3>Работаем 24/7</h3>
                            <p>Круглосуточная диспетчерская служба</p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">💳</div>
                            <h3>Онлайн оплата</h3>
                            <p>Принимаем карты Visa, MasterCard, МИР</p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">🛡️</div>
                            <h3>Безопасность</h3>
                            <p>Проверенные водители и застрахованные авто</p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">📍</div>
                            <h3>GPS мониторинг</h3>
                            <p>Отслеживание поездки в режиме реального времени</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Directions Section -->
        <section class="home-directions">
            <div class="container">
                <h2 class="section-title">Популярные направления</h2>
                <div class="directions-grid">

                    <div class="direction-card airports">
                        <h3>✈️ Аэропорты</h3>
                        <div class="direction-links">
                            <a href="/airports/to-domodedovo" class="direction-link">
                                В Домодедово <span class="price">от 800₽</span>
                            </a>
                            <a href="/airports/to-sheremetyevo" class="direction-link">
                                В Шереметьево <span class="price">от 900₽</span>
                            </a>
                            <a href="/airports/to-vnukovo" class="direction-link">
                                Во Внуково <span class="price">от 700₽</span>
                            </a>
                            <a href="/airports/from-domodedovo" class="direction-link">
                                Встреча с табличкой <span class="price">от 800₽</span>
                            </a>
                        </div>
                        <a href="/airports" class="btn btn-yellow">Все аэропорты</a>
                    </div>

                    <div class="direction-card stations">
                        <h3>🚂 Вокзалы</h3>
                        <div class="direction-links">
                            <a href="/stations/yaroslavsky" class="direction-link">
                                Ярославский вокзал <span class="price">от 400₽</span>
                            </a>
                            <a href="/stations/kazansky" class="direction-link">
                                Казанский вокзал <span class="price">от 450₽</span>
                            </a>
                            <a href="/stations/leningradsky" class="direction-link">
                                Ленинградский вокзал <span class="price">от 500₽</span>
                            </a>
                            <a href="/stations/from-station" class="direction-link">
                                Встреча с поездов <span class="price">от 400₽</span>
                            </a>
                        </div>
                        <a href="/stations" class="btn btn-yellow">Все вокзалы</a>
                    </div>

                    <div class="direction-card city">
                        <h3>🌆 По Москве</h3>
                        <div class="direction-links">
                            <a href="/city/hourly" class="direction-link">
                                Почасовая аренда <span class="price">от 500₽/час</span>
                            </a>
                            <a href="/city/business" class="direction-link">
                                Деловые встречи <span class="price">от 400₽</span>
                            </a>
                            <a href="/city/shopping" class="direction-link">
                                Шоппинг и развлечения <span class="price">от 300₽</span>
                            </a>
                            <a href="/city/medical" class="direction-link">
                                В больницу/поликлинику <span class="price">от 300₽</span>
                            </a>
                        </div>
                        <a href="/city" class="btn btn-yellow">Подробнее</a>
                    </div>

                </div>
            </div>
        </section>

        <!-- Reviews Section -->
        <section class="home-reviews">
            <div class="container">
                <jdoc:include type="modules" name="reviews" style="none" />
            </div>
        </section>

    <?php endif; ?>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">

                <div class="footer-column">
                    <h4>Контакты</h4>
                    <p class="footer-phone">
                        <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $phone); ?>"><?php echo $phone; ?></a>
                    </p>
                    <p class="footer-email">
                        <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
                    </p>
                    <p class="footer-address"><?php echo $address; ?></p>
                </div>

                <div class="footer-column">
                    <h4>Услуги</h4>
                    <ul class="footer-links">
                        <li><a href="/classes/economy">Эконом класс</a></li>
                        <li><a href="/classes/comfort">Комфорт класс</a></li>
                        <li><a href="/classes/business">Бизнес класс</a></li>
                        <li><a href="/classes/minivan">Минивэн</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h4>Направления</h4>
                    <ul class="footer-links">
                        <li><a href="/airports">Аэропорты</a></li>
                        <li><a href="/stations">Вокзалы</a></li>
                        <li><a href="/city">По городу</a></li>
                        <li><a href="/region">За город</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h4>О нас</h4>
                    <ul class="footer-links">
                        <li><a href="/about">О компании</a></li>
                        <li><a href="/reviews">Отзывы</a></li>
                        <li><a href="/news">Новости</a></li>
                        <li><a href="/contacts">Контакты</a></li>
                    </ul>
                </div>

            </div>

            <div class="footer-bottom">
                <jdoc:include type="modules" name="footer" style="none" />
                <?php if (!$this->countModules('footer')) : ?>
                    <p class="copyright">
                        © <?php echo date('Y'); ?> Служба такси 755.
                        <a href="/privacy">Политика конфиденциальности</a>
                    </p>
                <?php endif; ?>
            </div>

        </div>
    </footer>

    <?php if ($enable_sidebar && $this->countModules('sidebar')) : ?>
        <!-- Mobile Sidebar -->
        <div class="mobile-sidebar">
            <div class="sidebar-header">
                <h3>Быстрый заказ</h3>
                <button class="sidebar-close">×</button>
            </div>
            <div class="sidebar-content">
                <jdoc:include type="modules" name="sidebar" style="none" />
            </div>
        </div>
        <div class="sidebar-overlay"></div>
    <?php endif; ?>

    <!-- SEDI Integration -->
    <?php if ($sedi_enable) : ?>
        <script type="text/javascript">
            window.SeDi_ = {
                key: "<?php echo $sedi_key; ?>"
            };
        </script>
        <script src="https://msk1.sedi.ru/api.js" type="text/javascript" async></script>
    <?php endif; ?>

    <!-- Template JavaScript -->
    <script src="<?php echo Uri::root(); ?>templates/<?php echo $this->template; ?>/js/taxi-app.js"></script>

    <!-- Debug -->
    <jdoc:include type="modules" name="debug" style="none" />

</body>

</html>