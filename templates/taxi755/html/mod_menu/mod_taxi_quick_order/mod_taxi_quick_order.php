<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_taxi_quick_order
 * @copyright   (C) 2025 Taxi755. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;

// Module parameters
$app = Factory::getApplication();
$document = $app->getDocument();
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx', ''), ENT_COMPAT, 'UTF-8');

// Get current page context for smart defaults
$option = $app->input->get('option', '');
$view = $app->input->get('view', '');
$id = $app->input->get('id', 0);

// Auto-detect service context
$defaultCarType = 'comfort';
$defaultDestination = '';
$contextualTitle = 'Быстрый заказ';

// Detect context from URL/menu
if ($option === 'com_content' && $view === 'category') {
    $menu = $app->getMenu()->getActive();
    if ($menu) {
        $menuAlias = $menu->alias;
        
        // Car class detection
        if (strpos($menuAlias, 'economy') !== false) {
            $defaultCarType = 'economy';
            $contextualTitle = 'Заказать эконом класс';
        } elseif (strpos($menuAlias, 'business') !== false) {
            $defaultCarType = 'business';
            $contextualTitle = 'Заказать бизнес класс';
        } elseif (strpos($menuAlias, 'minivan') !== false) {
            $defaultCarType = 'minivan';
            $contextualTitle = 'Заказать минивэн';
        }
        
        // Airport detection
        if (strpos($menuAlias, 'domodedovo') !== false) {
            $defaultDestination = 'domodedovo';
            $contextualTitle = 'Заказ в Домодедово';
        } elseif (strpos($menuAlias, 'sheremetyevo') !== false) {
            $defaultDestination = 'sheremetyevo';
            $contextualTitle = 'Заказ в Шереметьево';
        } elseif (strpos($menuAlias, 'vnukovo') !== false) {
            $defaultDestination = 'vnukovo';
            $contextualTitle = 'Заказ во Внуково';
        } elseif (strpos($menuAlias, 'zhukovsky') !== false) {
            $defaultDestination = 'zhukovsky';
            $contextualTitle = 'Заказ в Жуковский';
        }
    }
}

// Module settings
$showTitle = $params->get('show_title', 1);
$customTitle = $params->get('custom_title', $contextualTitle);
$enableSedi = $params->get('enable_sedi', 1);
$showPriceEstimate = $params->get('show_price_estimate', 1);
$compactMode = $params->get('compact_mode', 0);

// Load module assets
$document->addScript('/modules/mod_taxi_quick_order/assets/js/quick-order.js');
$document->addStyleSheet('/modules/mod_taxi_quick_order/assets/css/quick-order.css');

// Require the layout
require ModuleHelper::getLayoutPath('mod_taxi_quick_order', $params->get('layout', 'default'));
?>