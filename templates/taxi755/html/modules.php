<?php
/**
 * @package     Taxi755 Template
 * @subpackage  Module Layouts
 * @copyright   2025 Taxi755. All rights reserved.
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/*
 * Module chrome for different styles
 */

function modChrome_taxi755card($module, &$params, &$attribs)
{
    $moduleTag     = htmlspecialchars($params->get('module_tag', 'div'), ENT_QUOTES, 'UTF-8');
    $headerTag     = htmlspecialchars($params->get('header_tag', 'h3'), ENT_QUOTES, 'UTF-8');
    $headerClass   = htmlspecialchars($params->get('header_class', 'service-title'), ENT_QUOTES, 'UTF-8');

    if (!empty ($module->content)) : ?>
        <<?php echo $moduleTag; ?> class="service-card moduletable<?php echo htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8'); ?>">
        <?php if ((bool) $module->showtitle) : ?>
            <<?php echo $headerTag; ?> class="<?php echo $headerClass; ?>">
            <?php echo $module->title; ?>
            </<?php echo $headerTag; ?>>
        <?php endif; ?>
        <div class="module-content">
            <?php echo $module->content; ?>
        </div>
        </<?php echo $moduleTag; ?>>
    <?php endif;
}

function modChrome_taxi755sidebar($module, &$params, &$attribs)
{
    $moduleTag     = htmlspecialchars($params->get('module_tag', 'div'), ENT_QUOTES, 'UTF-8');
    $headerTag     = htmlspecialchars($params->get('header_tag', 'h3'), ENT_QUOTES, 'UTF-8');
    $headerClass   = htmlspecialchars($params->get('header_class', 'module-title'), ENT_QUOTES, 'UTF-8');

    if (!empty ($module->content)) : ?>
        <<?php echo $moduleTag; ?> class="moduletable sidebar-module<?php echo htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8'); ?>">
        <?php if ((bool) $module->showtitle) : ?>
            <<?php echo $headerTag; ?> class="<?php echo $headerClass; ?>">
            <?php echo $module->title; ?>
            </<?php echo $headerTag; ?>>
        <?php endif; ?>
        <div class="module-content">
            <?php echo $module->content; ?>
        </div>
        </<?php echo $moduleTag; ?>>
    <?php endif;
}

function modChrome_taxi755clean($module, &$params, &$attribs)
{
    if (!empty ($module->content)) : ?>
        <?php echo $module->content; ?>
    <?php endif;
}

function modChrome_taxi755hero($module, &$params, &$attribs)
{
    if (!empty ($module->content)) : ?>
        <div class="hero-module<?php echo htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8'); ?>">
            <?php if ((bool) $module->showtitle) : ?>
                <h1 class="hero-title"><?php echo $module->title; ?></h1>
            <?php endif; ?>
            <div class="hero-content">
                <?php echo $module->content; ?>
            </div>
        </div>
    <?php endif;
}

function modChrome_none($module, &$params, &$attribs)
{
    if (!empty ($module->content)) : ?>
        <?php echo $module->content; ?>
    <?php endif;
}