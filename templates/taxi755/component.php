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
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\Document\HtmlDocument $this */

$app = Factory::getApplication();
$wa  = $this->getWebAssetManager();

// Template path
$templatePath = 'templates/' . $this->template;

// Add template CSS
HTMLHelper::_('stylesheet', $templatePath . '/css/template.css', array('version' => 'auto'));

?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <jdoc:include type="head" />
</head>

<body class="component-page">

<div class="component-wrapper">
    <jdoc:include type="message" />
    <jdoc:include type="component" />
</div>

<style>
    .component-page {
        background: #fff;
        padding: 2rem;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: #333;
    }

    .component-wrapper {
        max-width: 1000px;
        margin: 0 auto;
    }
</style>

</body>
</html>