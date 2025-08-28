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

/** @var Joomla\CMS\Document\ErrorDocument $this */

$app = Factory::getApplication();
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');
$templatePath = 'templates/' . $this->template;

// Add template CSS
HTMLHelper::_('stylesheet', $templatePath . '/css/template.css', array('version' => 'auto'));

?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->error->getCode(); ?> - <?php echo $this->error->getMessage(); ?></title>
    <meta name="description" content="<?php echo $this->error->getCode(); ?> - <?php echo $this->error->getMessage(); ?>">
    <jdoc:include type="head" />
</head>

<body class="error-page">

<header class="header">
    <div class="container">
        <div class="header-content">
            <div class="logo">
                <a href="<?php echo Uri::root(); ?>" class="logo-text">ТАКСИ 755</a>
            </div>
            <div class="contact-info">
                <a href="tel:+79266410896" class="phone">+7 (926) 641-08-96</a>
            </div>
        </div>
    </div>
</header>

<main class="error-main">
    <div class="container">
        <div class="error-content">

            <div class="error-icon">
                <?php if ($this->error->getCode() == '404') : ?>
                    🚕💨
                <?php elseif ($this->error->getCode() == '500') : ?>
                    🔧
                <?php else : ?>
                    ⚠️
                <?php endif; ?>
            </div>

            <h1 class="error-title">
                <?php if ($this->error->getCode() == '404') : ?>
                    Страница уехала!
                <?php elseif ($this->error->getCode() == '500') : ?>
                    Технические работы
                <?php else : ?>
                    Ошибка <?php echo $this->error->getCode(); ?>
                <?php endif; ?>
            </h1>

            <p class="error-description">
                <?php if ($this->error->getCode() == '404') : ?>
                    Похоже, эта страница отправилась в поездку и еще не вернулась.
                    Но не переживайте - наши такси всегда на месте!
                <?php elseif ($this->error->getCode() == '500') : ?>
                    Мы временно проводим техническое обслуживание сайта.
                    Заказать такси можно по телефону.
                <?php else : ?>
                    <?php echo $this->error->getMessage(); ?>
                <?php endif; ?>
            </p>

            <div class="error-actions">
                <a href="<?php echo Uri::root(); ?>" class="btn btn-primary">
                    🏠 На главную
                </a>
                <a href="tel:+79266410896" class="btn" style="background: #333; color: white; margin-left: 1rem;">
                    📞 Позвонить
                </a>
            </div>

            <?php if ($this->debug) : ?>
                <div class="error-debug">
                    <h3>Информация для разработчика:</h3>
                    <p><strong>Код ошибки:</strong> <?php echo $this->error->getCode(); ?></p>
                    <p><strong>Сообщение:</strong> <?php echo $this->error->getMessage(); ?></p>
                    <p><strong>Файл:</strong> <?php echo $this->error->getFile(); ?></p>
                    <p><strong>Строка:</strong> <?php echo $this->error->getLine(); ?></p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</main>

<footer class="footer">
    <div class="container">
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Такси 755. Все права защищены.</p>
        </div>
    </div>
</footer>

<style>
    .error-page {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        background: linear-gradient(135deg, #fff 0%, #fffbf0 100%);
    }

    .error-main {
        flex: 1;
        display: flex;
        align-items: center;
        padding: 4rem 0;
    }

    .error-content {
        text-align: center;
        max-width: 600px;
        margin: 0 auto;
    }

    .error-icon {
        font-size: 6rem;
        margin-bottom: 2rem;
        opacity: 0.8;
    }

    .error-title {
        font-size: 3rem;
        color: #333;
        margin-bottom: 1.5rem;
        font-weight: 700;
    }

    .error-description {
        font-size: 1.2rem;
        color: #666;
        margin-bottom: 3rem;
        line-height: 1.6;
    }

    .error-actions {
        margin-bottom: 3rem;
    }

    .error-debug {
        background: #f8f9fa;
        padding: 2rem;
        border-radius: 10px;
        text-align: left;
        border: 1px solid #e0e0e0;
        margin-top: 3rem;
    }

    .error-debug h3 {
        color: #dc3545;
        margin-bottom: 1rem;
    }

    .error-debug p {
        margin-bottom: 0.5rem;
        font-family: monospace;
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .error-icon {
            font-size: 4rem;
        }

        .error-title {
            font-size: 2rem;
        }

        .error-description {
            font-size: 1rem;
        }

        .error-actions .btn {
            display: block;
            margin: 0.5rem auto;
            max-width: 200px;
        }
    }
</style>

</body>
</html>