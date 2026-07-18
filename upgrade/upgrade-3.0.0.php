<?php
/**
 * Social Share Buttons - Grow Traffic with One-Click Sharing
 *
 * Upgrade from 2.x to 3.0.0:
 *   - drop the old ObjectModel tables (all settings now live in Configuration);
 *   - unregister every legacy hook and register the modern ones;
 *   - delete the stale files the 2.x package left on disk (module upgrades
 *     extract over the existing folder, so old files are not removed on their own);
 *   - seed the new default settings without overwriting anything already set.
 *
 * @author    MEG Venture <info@megventure.com>
 * @copyright 2019-2026 MEG Venture
 * @license   Academic Free License 3.0 (AFL-3.0)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_3_0_0($module)
{
    // 1. Remove the 2.x per-shop configuration tables.
    Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'socialpower`');
    Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'socialpower_lang`');

    // 2. Swap legacy hooks for the modern ones.
    $legacyHooks = [
        'header', 'displayHeader',
        'productfooter', 'displayProductFooter',
        'footer', 'displayFooter',
        'top', 'displayTop',
        'home', 'displayHome',
        'leftColumn', 'displayLeftColumn',
        'rightColumn', 'displayRightColumn',
        'extraleft', 'displayExtraLeft',
        'extraright', 'displayExtraRight',
    ];
    foreach ($legacyHooks as $hook) {
        $module->unregisterHook($hook);
    }

    $module->registerHook('actionFrontControllerSetMedia');
    $module->registerHook('displayFooterProduct');
    $module->registerHook('displayBeforeBodyClosingTag');

    // 3. Seed new settings, keeping any value a merchant may already have.
    foreach (Socialpower::SETTINGS as $key => $value) {
        if (Configuration::get($key) === false) {
            Configuration::updateValue($key, $value);
        }
    }

    // 4. Delete the 2.x files that are no longer used.
    socialpower_cleanup_legacy_files($module);

    return true;
}

/**
 * Remove obsolete 2.x files/folders shipped inside the module directory.
 */
function socialpower_cleanup_legacy_files($module)
{
    $base = _PS_MODULE_DIR_ . $module->name . '/';

    $files = [
        'logo.gif',
        'classes/SocialpowerClass.php',
        'classes/index.php',
        'backward_compatibility/backward.ini',
        'backward_compatibility/backward.php',
        'backward_compatibility/Context.php',
        'backward_compatibility/Display.php',
        'backward_compatibility/index.php',
        'views/templates/front/socialpower_extraleft.tpl',
        'views/templates/front/socialpower_extraright.tpl',
        'views/templates/front/socialpower_footer.tpl',
        'views/templates/front/socialpower_header.tpl',
        'views/templates/front/socialpower_left.tpl',
        'views/templates/front/socialpower_product_footer.tpl',
        'views/templates/front/socialpower_top.tpl',
        'views/templates/front/index.php',
    ];
    foreach ($files as $file) {
        if (is_file($base . $file)) {
            @unlink($base . $file);
        }
    }

    // The old widget screenshots are no longer needed (icons are inline SVG now).
    socialpower_rrmdir($base . 'views/img');
    socialpower_rrmdir($base . 'classes');
    socialpower_rrmdir($base . 'backward_compatibility');
    socialpower_rrmdir($base . 'views/templates/front');
}

/**
 * Recursively delete a directory if it still exists.
 */
function socialpower_rrmdir($dir)
{
    if (!is_dir($dir)) {
        return;
    }

    $items = scandir($dir);
    if ($items === false) {
        return;
    }

    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        $path = $dir . '/' . $item;
        if (is_dir($path)) {
            socialpower_rrmdir($path);
        } else {
            @unlink($path);
        }
    }

    @rmdir($dir);
}
