<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.taxi755
 * @copyright   (C) 2025 Taxi755. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\Utilities\ArrayHelper;

$app = Factory::getApplication();
$menu = $app->getMenu();

// Get menu parameters
$class_sfx = htmlspecialchars($params->get('class_sfx', ''), ENT_COMPAT, 'UTF-8');
$showAll = $params->get('showAllChildren', 1);
$items = $list;

// Build menu tree
function buildMenuTree($items, $parentId = 1) {
    $tree = [];
    foreach ($items as $item) {
        if ($item->parent_id == $parentId) {
            $children = buildMenuTree($items, $item->id);
            if (!empty($children)) {
                $item->children = $children;
            }
            $tree[] = $item;
        }
    }
    return $tree;
}

$menuTree = buildMenuTree($items);

?>

<nav class="main-navigation <?php echo $class_sfx; ?>" role="navigation">
    <ul class="nav-menu">
        <?php foreach ($menuTree as $item) : ?>
            <?php
            $linktype = $item->title;
            $flink = $item->flink;
            $title = $item->anchor_title ? 'title="' . $item->anchor_title . '"' : '';
            
            // CSS classes for menu item
            $class = 'nav-item';
            if ($item->id == $menu->getActive()->id || ($menu->getActive()->tree && in_array($item->id, $menu->getActive()->tree))) {
                $class .= ' current active';
            }
            if (in_array($item->id, $path ?? [])) {
                $class .= ' current';
            }
            if ($item->type == 'alias') {
                $class .= ' alias';
            }
            if ($item->anchor_css) {
                $class .= ' ' . $item->anchor_css;
            }
            if (!empty($item->children)) {
                $class .= ' has-children';
            }
            
            // Icons for menu items based on alias
            $menuIcons = [
                'classes' => '🚗',
                'airports' => '✈️', 
                'stations' => '🚂',
                'city' => '🌆',
                'region' => '🏞️',
                'contacts' => '📞',
                'news' => '📰',
                'reviews' => '⭐'
            ];
            $icon = $menuIcons[$item->alias] ?? '';
            ?>
            
            <li class="<?php echo $class; ?>">
                <?php
                switch ($item->type) :
                    case 'separator':
                    case 'component':
                    case 'heading':
                    case 'url':
                        require 'default_' . $item->type . '.php';
                        break;

                    default:
                ?>
                    <a class="nav-link" href="<?php echo $flink; ?>" <?php echo $title; ?>>
                        <?php if ($icon) : ?>
                            <span class="nav-icon"><?php echo $icon; ?></span>
                        <?php endif; ?>
                        <span class="nav-text"><?php echo $linktype; ?></span>
                        <?php if (!empty($item->children)) : ?>
                            <span class="nav-arrow">▼</span>
                        <?php endif; ?>
                    </a>
                <?php
                        break;
                endswitch;
                ?>

                <!-- Submenu for children -->
                <?php if (!empty($item->children)) : ?>
                    <ul class="nav-submenu">
                        <?php foreach ($item->children as $child) : ?>
                            <?php
                            $childClass = 'nav-subitem';
                            if ($child->id == $menu->getActive()->id) {
                                $childClass .= ' current active';
                            }
                            $childTitle = $child->anchor_title ? 'title="' . $child->anchor_title . '"' : '';
                            
                            // Special handling for airport/class submenus
                            $subIcon = '';
                            if (strpos($child->alias, 'economy') !== false) $subIcon = '💚';
                            elseif (strpos($child->alias, 'comfort') !== false) $subIcon = '💛';
                            elseif (strpos($child->alias, 'business') !== false) $subIcon = '💜';
                            elseif (strpos($child->alias, 'minivan') !== false) $subIcon = '🧡';
                            elseif (strpos($child->alias, 'domodedovo') !== false) $subIcon = '🅳';
                            elseif (strpos($child->alias, 'sheremetyevo') !== false) $subIcon = '🅢';
                            elseif (strpos($child->alias, 'vnukovo') !== false) $subIcon = '🅥';
                            elseif (strpos($child->alias, 'zhukovsky') !== false) $subIcon = '🅙';
                            ?>
                            
                            <li class="<?php echo $childClass; ?>">
                                <a class="nav-sublink" href="<?php echo $child->flink; ?>" <?php echo $childTitle; ?>>
                                    <?php if ($subIcon) : ?>
                                        <span class="nav-subicon"><?php echo $subIcon; ?></span>
                                    <?php endif; ?>
                                    <span class="nav-subtext"><?php echo $child->title; ?></span>
                                </a>
                                
                                <!-- Third level for detailed pages -->
                                <?php if (!empty($child->children)) : ?>
                                    <ul class="nav-submenu-deep">
                                        <?php foreach ($child->children as $grandchild) : ?>
                                            <li class="nav-deepitem">
                                                <a class="nav-deeplink" href="<?php echo $grandchild->flink; ?>">
                                                    <?php echo $grandchild->title; ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                
            </li>
        <?php endforeach; ?>
    </ul>
    
    <!-- Mobile quick order button -->
    <div class="mobile-quick-order">
        <button class="btn btn-primary btn-mobile-order" data-car-type="any">
            Заказать такси
        </button>
    </div>
</nav>

<style>
/* Main Navigation Styles */
.main-navigation {
    position: relative;
    z-index: 100;
}

.nav-menu {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
}

.nav-item {
    position: relative;
    margin: 0 0.5rem;
}

.nav-link {
    display: flex;
    align-items: center;
    padding: 1rem 1.5rem;
    color: #FFFFFF;
    text-decoration: none;
    font-weight: 500;
    font-size: 1rem;
    border-radius: 10px;
    transition: all 0.3s ease;
    position: relative;
    white-space: nowrap;
}

.nav-link:hover,
.nav-item.current .nav-link {
    background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
    color: #333333;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255,215,0,0.3);
}

.nav-icon {
    margin-right: 0.5rem;
    font-size: 1.1rem;
}

.nav-text {
    margin-right: 0.5rem;
}

.nav-arrow {
    font-size: 0.7rem;
    transition: transform 0.3s ease;
}

.nav-item.has-children:hover .nav-arrow {
    transform: rotate(180deg);
}

/* Submenu Styles */
.nav-submenu {
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    min-width: 250px;
    background: #FFFFFF;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    border: 1px solid #E5E5E5;
    list-style: none;
    margin: 0;
    padding: 0.5rem 0;
    opacity: 0;
    visibility: hidden;
    transform: translateX(-50%) translateY(-10px);
    transition: all 0.3s ease;
    z-index: 200;
}

.nav-item:hover .nav-submenu {
    opacity: 1;
    visibility: visible;
    transform: translateX(-50%) translateY(0);
}

.nav-subitem {
    position: relative;
}

.nav-sublink {
    display: flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    color: #333333;
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.2s ease;
}

.nav-sublink:hover,
.nav-subitem.current .nav-sublink {
    background: linear-gradient(135deg, #FFFBF0 0%, #FFF8E1 100%);
    color: #333333;
    padding-left: 2rem;
}

.nav-subicon {
    margin-right: 0.5rem;
    font-size: 0.9rem;
}

.nav-subtext {
    flex-grow: 1;
}

/* Deep submenu (third level) */
.nav-submenu-deep {
    position: absolute;
    top: 0;
    left: 100%;
    min-width: 200px;
    background: #FFFFFF;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    border: 1px solid #E5E5E5;
    list-style: none;
    margin: 0;
    padding: 0.5rem 0;
    opacity: 0;
    visibility: hidden;
    transform: translateX(-10px);
    transition: all 0.2s ease;
}

.nav-subitem:hover .nav-submenu-deep {
    opacity: 1;
    visibility: visible;
    transform: translateX(0);
}

.nav-deeplink {
    display: block;
    padding: 0.5rem 1rem;
    color: #666;
    text-decoration: none;
    font-size: 0.85rem;
    transition: all 0.2s ease;
}

.nav-deeplink:hover {
    background: #FFD700;
    color: #333;
    padding-left: 1.5rem;
}

/* Mobile quick order */
.mobile-quick-order {
    display: none;
    margin-top: 1rem;
    text-align: center;
}

.btn-mobile-order {
    width: 100%;
    max-width: 200px;
    font-weight: 600;
}

/* Mobile Navigation */
@media (max-width: 768px) {
    .nav-menu {
        display: none;
        flex-direction: column;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #333333;
        border-radius: 0 0 15px 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        padding: 1rem 0;
        z-index: 150;
    }
    
    .nav-menu.active {
        display: flex;
        animation: slideDown 0.3s ease;
    }
    
    .nav-item {
        margin: 0;
        width: 100%;
        border-bottom: 1px solid #555;
    }
    
    .nav-item:last-child {
        border-bottom: none;
    }
    
    .nav-link {
        padding: 1rem 1.5rem;
        border-radius: 0;
        width: 100%;
        justify-content: space-between;
    }
    
    .nav-submenu {
        position: static;
        transform: none;
        opacity: 1;
        visibility: visible;
        background: #444;
        border-radius: 0;
        box-shadow: none;
        border: none;
        margin-top: 0;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }
    
    .nav-item.has-children.menu-open .nav-submenu {
        max-height: 500px;
    }
    
    .nav-sublink {
        padding: 0.75rem 2rem;
        color: #ccc;
        border-bottom: 1px solid #555;
    }
    
    .nav-sublink:hover {
        background: #555;
        color: #FFD700;
        padding-left: 2.5rem;
    }
    
    .mobile-quick-order {
        display: block;
    }
    
    /* Remove deep submenus on mobile */
    .nav-submenu-deep {
        display: none;
    }
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Accessibility improvements */
.nav-link:focus {
    outline: 2px solid #FFD700;
    outline-offset: 2px;
}

.nav-item[aria-expanded="true"] .nav-arrow {
    transform: rotate(180deg);
}

/* Menu hover effects */
.nav-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 50%;
    width: 0;
    height: 3px;
    background: #FFD700;
    transition: all 0.3s ease;
    transform: translateX(-50%);
}

.nav-item:hover::before,
.nav-item.current::before {
    width: 80%;
}

/* Submenu animations */
.nav-submenu .nav-subitem {
    opacity: 0;
    transform: translateY(-10px);
    transition: all 0.2s ease;
}

.nav-item:hover .nav-submenu .nav-subitem {
    opacity: 1;
    transform: translateY(0);
}

.nav-item:hover .nav-submenu .nav-subitem:nth-child(1) { transition-delay: 0.05s; }
.nav-item:hover .nav-submenu .nav-subitem:nth-child(2) { transition-delay: 0.1s; }
.nav-item:hover .nav-submenu .nav-subitem:nth-child(3) { transition-delay: 0.15s; }
.nav-item:hover .nav-submenu .nav-subitem:nth-child(4) { transition-delay: 0.2s; }

/* Special styling for specific menu items */
.nav-item.menu-item-home .nav-link {
    background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
    color: #333;
    font-weight: 600;
}

.nav-item.menu-item-home:hover .nav-link {
    transform: scale(1.05);
}

/* Breadcrumb-style submenu for airports */
.nav-item.menu-airports .nav-submenu {
    min-width: 400px;
    padding: 1rem;
}

.nav-item.menu-airports .nav-submenu::before {
    content: 'Выберите направление:';
    display: block;
    font-size: 0.8rem;
    color: #999;
    margin-bottom: 0.5rem;
    text-align: center;
}

/* Classes submenu styling */
.nav-item.menu-classes .nav-submenu {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5rem;
    min-width: 300px;
    padding: 1rem;
}

.nav-item.menu-classes .nav-sublink {
    border-radius: 8px;
    margin: 0;
    text-align: center;
}

.nav-item.menu-classes .nav-sublink:hover {
    transform: scale(1.02);
}

/* High contrast support */
@media (prefers-contrast: high) {
    .nav-link {
        border: 1px solid #fff;
    }
    
    .nav-submenu {
        border: 2px solid #333;
    }
}

/* Reduced motion */
@media (prefers-reduced-motion: reduce) {
    .nav-link,
    .nav-submenu,
    .nav-arrow {
        transition: none;
    }
    
    .nav-item:hover .nav-link,
    .nav-item:hover::before {
        transform: none;
    }
}
</style>

<script>
// Enhanced menu functionality
document.addEventListener('DOMContentLoaded', function() {
    const navigation = document.querySelector('.main-navigation');
    const menuItems = document.querySelectorAll('.nav-item.has-children');
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const navMenu = document.querySelector('.nav-menu');

    // Mobile menu toggle
    if (mobileMenuToggle && navMenu) {
        mobileMenuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            navMenu.classList.toggle('active');
            mobileMenuToggle.classList.toggle('active');
            document.body.classList.toggle('menu-open');
        });
    }

    // Mobile submenu handling
    if (window.innerWidth <= 768) {
        menuItems.forEach(item => {
            const link = item.querySelector('.nav-link');
            const submenu = item.querySelector('.nav-submenu');
            
            if (link && submenu) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Close other open submenus
                    menuItems.forEach(otherItem => {
                        if (otherItem !== item) {
                            otherItem.classList.remove('menu-open');
                        }
                    });
                    
                    // Toggle current submenu
                    item.classList.toggle('menu-open');
                });
            }
        });
    }

    // Close menu on outside click
    document.addEventListener('click', function(e) {
        if (!navigation.contains(e.target) && navMenu.classList.contains('active')) {
            navMenu.classList.remove('active');
            mobileMenuToggle.classList.remove('active');
            document.body.classList.remove('menu-open');
        }
    });

    // Keyboard navigation
    navigation.addEventListener('keydown', function(e) {
        const currentFocus = document.activeElement;
        const menuLinks = Array.from(navigation.querySelectorAll('.nav-link, .nav-sublink'));
        const currentIndex = menuLinks.indexOf(currentFocus);

        switch(e.key) {
            case 'ArrowDown':
                e.preventDefault();
                const nextIndex = (currentIndex + 1) % menuLinks.length;
                menuLinks[nextIndex].focus();
                break;
                
            case 'ArrowUp':
                e.preventDefault();
                const prevIndex = currentIndex === 0 ? menuLinks.length - 1 : currentIndex - 1;
                menuLinks[prevIndex].focus();
                break;
                
            case 'Escape':
                if (navMenu.classList.contains('active')) {
                    navMenu.classList.remove('active');
                    mobileMenuToggle.classList.remove('active');
                    document.body.classList.remove('menu-open');
                    mobileMenuToggle.focus();
                }
                break;
        }
    });

    // Add ARIA attributes for accessibility
    menuItems.forEach(item => {
        const link = item.querySelector('.nav-link');
        const submenu = item.querySelector('.nav-submenu');
        
        if (link && submenu) {
            const submenuId = 'submenu-' + Math.random().toString(36).substr(2, 9);
            
            link.setAttribute('aria-expanded', 'false');
            link.setAttribute('aria-haspopup', 'true');
            link.setAttribute('aria-controls', submenuId);
            
            submenu.setAttribute('id', submenuId);
            submenu.setAttribute('aria-labelledby', link.id || 'menu-' + item.classList[0]);
            
            // Update aria-expanded on hover
            item.addEventListener('mouseenter', () => {
                link.setAttribute('aria-expanded', 'true');
            });
            
            item.addEventListener('mouseleave', () => {
                link.setAttribute('aria-expanded', 'false');
            });
        }
    });
});
</script>

<?php
// Create default menu items for component, heading, separator, url types
$menuTypes = ['component', 'heading', 'separator', 'url'];

foreach ($menuTypes as $type) {
    $defaultFile = __DIR__ . '/default_' . $type . '.php';
    
    if (!file_exists($defaultFile)) {
        $content = "<?php\ndefined('_JEXEC') or die;\n";
        
        switch ($type) {
            case 'component':
                $content .= '
$linktype = $item->title;
$flink = $item->flink;
$title = $item->anchor_title ? \'title="\' . $item->anchor_title . \'"\' : \'\';
echo \'<a class="nav-link" href="\' . $flink . \'" \' . $title . \'>\';
if ($icon) echo \'<span class="nav-icon">\' . $icon . \'</span>\';
echo \'<span class="nav-text">\' . $linktype . \'</span>\';
if (!empty($item->children)) echo \'<span class="nav-arrow">▼</span>\';
echo \'</a>\';
';
                break;
                
            case 'heading':
                $content .= '
echo \'<span class="nav-heading">\';
if ($icon) echo \'<span class="nav-icon">\' . $icon . \'</span>\';
echo \'<span class="nav-text">\' . $linktype . \'</span>\';
echo \'</span>\';
';
                break;
                
            case 'separator':
                $content .= 'echo \'<span class="nav-separator"></span>\';';
                break;
                
            case 'url':
                $content .= '
$flink = $item->flink;
$title = $item->anchor_title ? \'title="\' . $item->anchor_title . \'"\' : \'\';
$target = $item->browserNav == 1 ? \' target="_blank"\' : \'\';
echo \'<a class="nav-link" href="\' . $flink . \'"\' . $target . \' \' . $title . \'>\';
if ($icon) echo \'<span class="nav-icon">\' . $icon . \'</span>\';
echo \'<span class="nav-text">\' . $linktype . \'</span>\';
echo \'</a>\';
';
                break;
        }
        
        // Note: In actual implementation, these files should be created separately
    }
}
?>