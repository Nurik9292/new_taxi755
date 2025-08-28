<?php
/**
 * @package     Taxi755 Template
 * @subpackage  Templates.taxi755
 * @copyright   2025 Taxi755. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\Document\HtmlDocument $this */

$app = Factory::getApplication();
$wa  = $this->getWebAssetManager();

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');
$menu     = $app->getMenu()->getActive();

// Template path
$templatePath = 'templates/' . $this->template;

// Add template CSS and JS
HTMLHelper::_('stylesheet', $templatePath . '/css/template.css', array('version' => 'auto'));
HTMLHelper::_('script', $templatePath . '/js/template.js', array('version' => 'auto'));

// Get template parameters
$logo = $this->params->get('logo', '');
$phone = $this->params->get('phone', '+7 (926) 641-08-96');
$sitename_display = $this->params->get('sitename', $sitename);

?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    
    <!-- Geo tags for local SEO -->
    <meta name="geo.placename" content="Маломосковская улица, 22с1, Москва, 129626">
    <meta name="geo.position" content="55.811745;37.652625">
    <meta name="geo.region" content="RU-Москва">
    <meta name="ICBM" content="55.811745, 37.652625">
    
    <jdoc:include type="head" />
    
    <!-- Schema.org markup for taxi service -->
    <script type="application/ld+json">
    {
      "@context": "http://www.schema.org",
      "@type": "TaxiService",
      "name": "Служба такси 755",
      "url": "<?php echo Uri::root(); ?>",
      "logo": "<?php echo Uri::root(); ?>images/logo.png",
      "description": "Компания по перевозкам пассажиров «Служба такси 755» занимается транспортными перевозками на автомобилях разных типов и классов",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "ул Маломосковская, 22 стр., 1",
        "addressLocality": "Москва",
        "addressRegion": "Москва",
        "postalCode": "129626",
        "addressCountry": "Россия"
      },
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "<?php echo $phone; ?>",
        "contactType": "customer support",
        "areaServed": "Moscow",
        "availableLanguage": "Russian"
      },
      "openingHours": "Mo,Tu,We,Th,Fr,Sa,Su 00:00-23:59",
      "priceRange": "800-3000 RUB"
    }
    </script>
</head>

<body class="site <?php echo $option . ' view-' . $view . ($layout ? ' layout-' . $layout : ' no-layout') . ($task ? ' task-' . $task : ' no-task') . ($itemid ? ' itemid-' . $itemid : ''); ?>">

    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <?php if ($logo) : ?>
                        <img src="<?php echo Uri::root() . $logo; ?>" alt="<?php echo $sitename_display; ?>">
                    <?php else : ?>
                        <span class="logo-text">ТАКСИ 755</span>
                    <?php endif; ?>
                </div>
                <div class="contact-info">
                    <jdoc:include type="modules" name="phone" style="none" />
                    <?php if (!$this->countModules('phone')) : ?>
                        <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $phone); ?>" class="phone"><?php echo $phone; ?></a>
                        <span class="phone-desc">Диспетчерская</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="nav">
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
                <h1>Надежное такси в Москве</h1>
                <p class="hero-subtitle">Комфортные поездки по городу и трансферы в аэропорты</p>
                <a href="#order" class="cta-button">📱 Заказать такси</a>
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
                <div class="left-content">
                    <!-- Messages -->
                    <jdoc:include type="message" />
                    
                    <!-- Component Output -->
                    <jdoc:include type="component" />
                    
                    <!-- Home Page Sections (только на главной) -->
                    <?php if ($menu && $menu->home) : ?>
                        
                        <!-- Services Section -->
                        <section class="home-services">
                            <jdoc:include type="modules" name="services" style="taxi755card" />
                        </section>

                        <!-- Features Section -->
                        <section class="home-features">
                            <jdoc:include type="modules" name="features" style="none" />
                        </section>

                        <!-- Payment Info -->
                        <section class="home-payment">
                            <jdoc:include type="modules" name="payment" style="none" />
                        </section>

                        <!-- Reviews Section -->
                        <section class="home-reviews">
                            <jdoc:include type="modules" name="reviews" style="none" />
                        </section>

                        <!-- News Section -->
                        <section class="home-news">
                            <jdoc:include type="modules" name="news" style="none" />
                        </section>

                        <!-- Partners Section -->
                        <section class="home-partners">
                            <jdoc:include type="modules" name="partners" style="none" />
                        </section>

                        <!-- Additional Services -->
                        <section class="home-additional">
                            <jdoc:include type="modules" name="additional" style="none" />
                        </section>

                        <!-- About Text -->
                        <section class="home-about">
                            <jdoc:include type="modules" name="about" style="none" />
                        </section>

                    <?php endif; ?>
                </div>

                <!-- Right Sidebar -->
                <?php if ($this->countModules('sidebar')) : ?>
                <aside class="right-sidebar">
                    <jdoc:include type="modules" name="sidebar" style="taxi755sidebar" />
                </aside>
                <?php endif; ?>

            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Контакты</h3>
                    <jdoc:include type="modules" name="footer-contacts" style="none" />
                    <?php if (!$this->countModules('footer-contacts')) : ?>
                        <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $phone); ?>"><?php echo $phone; ?></a>
                        <a href="mailto:info@taxi755.ru">info@taxi755.ru</a>
                        <p>Москва, ул. Маломосковская, 22с1</p>
                        <p>Режим работы: круглосуточно</p>
                    <?php endif; ?>
                </div>
                <div class="footer-section">
                    <h3>Услуги</h3>
                    <jdoc:include type="modules" name="footer-services" style="none" />
                </div>
                <div class="footer-section">
                    <h3>Информация</h3>
                    <jdoc:include type="modules" name="footer-info" style="none" />
                </div>
                <div class="footer-section">
                    <h3>Партнеры</h3>
                    <jdoc:include type="modules" name="footer-partners" style="none" />
                </div>
            </div>
            <div class="footer-bottom">
                <jdoc:include type="modules" name="footer-bottom" style="none" />
                <?php if (!$this->countModules('footer-bottom')) : ?>
                    <p>&copy; <?php echo date('Y'); ?> Такси 755. Все права защищены.</p>
                <?php endif; ?>
            </div>
        </div>
    </footer>

    <!-- SEDI Integration -->
    <script type="text/javascript">
    window.SeDi_ = {
        key: "1A047663-E78D-44C3-A043-C0A0D2960631"
    };
    </script>
    <script src="https://msk1.sedi.ru/api.js" type="text/javascript" async></script>

    <!-- Debug -->
    <jdoc:include type="modules" name="debug" style="none" />

</body>
</html>