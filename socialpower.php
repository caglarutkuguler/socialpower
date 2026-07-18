<?php
/**
 * Social Share Buttons - Grow Traffic with One-Click Sharing
 *
 * @author    MEG Venture <info@megventure.com>
 * @copyright 2019-2026 MEG Venture
 * @license   Academic Free License 3.0 (AFL-3.0)
 */

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

if (!defined('_PS_VERSION_')) {
    exit;
}

class Socialpower extends Module implements WidgetInterface
{
    /**
     * All share targets the module knows about, in the fixed display order.
     * The merchant turns individual networks on/off; the order stays stable.
     */
    const NETWORKS = [
        'facebook',
        'x',
        'whatsapp',
        'pinterest',
        'linkedin',
        'telegram',
        'reddit',
        'email',
        'copy',
        'native',
    ];

    /** Default configuration seeded on install. */
    const SETTINGS = [
        'SP_NETWORKS'      => 'facebook,x,whatsapp,pinterest,linkedin,email,copy,native',
        'SP_SHOW_PRODUCT'  => 1,
        'SP_SHOW_FLOATING' => 0,
        'SP_FLOAT_POS'     => 'left',
        'SP_FLOAT_PAGES'   => 'all',
        'SP_STYLE'         => 'solid',
        'SP_SHAPE'         => 'rounded',
        'SP_SIZE'          => 'm',
        'SP_COLOR'         => 'brand',
        'SP_SHOW_LABEL'    => 0,
        'SP_SHOW_HEADING'  => 1,
        'SP_X_VIA'         => '',
    ];

    public function __construct()
    {
        $this->name = 'socialpower';
        $this->tab = 'front_office_features';
        $this->version = '3.0.0';
        $this->author = 'MEG Venture';
        $this->module_key = 'a428eb0bed4543e61e8c8ddfc45122e7';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->ps_versions_compliancy = ['min' => '1.7.0.0', 'max' => _PS_VERSION_];

        parent::__construct();

        $this->displayName = $this->l('Social Share Buttons - Grow Traffic with One-Click Sharing');
        $this->description = $this->l('Let shoppers share your products and pages on Facebook, X, WhatsApp, Pinterest, LinkedIn and more with one click. Lightweight share links load no third-party tracking scripts, so your store stays fast and GDPR-friendly. Add a share row to product pages or a floating bar to every page.');

        $this->confirmUninstall = $this->l('Are you sure? The share buttons and all their settings will be removed.');
    }

    public function install()
    {
        if (!parent::install()
            || !$this->registerHook('actionFrontControllerSetMedia')
            || !$this->registerHook('displayFooterProduct')
            || !$this->registerHook('displayBeforeBodyClosingTag')
        ) {
            return false;
        }

        foreach (self::SETTINGS as $key => $value) {
            Configuration::updateValue($key, $value);
        }

        return true;
    }

    public function uninstall()
    {
        foreach (array_keys(self::SETTINGS) as $key) {
            Configuration::deleteByName($key);
        }

        return parent::uninstall();
    }

    /* ---------------------------------------------------------------------
     * Front office assets
     * ------------------------------------------------------------------- */

    public function hookActionFrontControllerSetMedia()
    {
        $controller = $this->context->controller;
        if (!$controller) {
            return;
        }

        $controller->registerStylesheet(
            'modules-socialpower',
            'modules/' . $this->name . '/views/css/socialpower.css',
            ['media' => 'all', 'priority' => 150]
        );
        $controller->registerJavascript(
            'modules-socialpower',
            'modules/' . $this->name . '/views/js/front.js',
            ['position' => 'bottom', 'priority' => 150]
        );
    }

    /* ---------------------------------------------------------------------
     * Display hooks
     * ------------------------------------------------------------------- */

    /*
     * The module implements WidgetInterface, so PrestaShop routes every display
     * hook through renderWidget() rather than the hookDisplayXxx() methods. All
     * placement logic and on/off gating therefore lives in renderWidget(); the
     * hook methods below are thin delegators kept only for forks or direct calls.
     */
    public function hookDisplayFooterProduct($params)
    {
        return $this->renderWidget('displayFooterProduct', $params);
    }

    public function hookDisplayBeforeBodyClosingTag($params)
    {
        return $this->renderWidget('displayBeforeBodyClosingTag', $params);
    }

    /**
     * Optional footer placement. Not registered on install; a merchant can
     * transplant the module onto this hook from the Positions page.
     */
    public function hookDisplayFooter($params)
    {
        return $this->renderWidget('displayFooter', $params);
    }

    /* ---------------------------------------------------------------------
     * WidgetInterface: the single entry point for every placement, including
     * {widget name='socialpower'} used directly in a theme.
     * ------------------------------------------------------------------- */

    public function renderWidget($hookName, array $configuration)
    {
        if ($hookName === 'displayFooterProduct') {
            if (!Configuration::get('SP_SHOW_PRODUCT')) {
                return '';
            }

            return $this->renderShareBlock('inline', $this->resolveProductShare($configuration));
        }

        if ($hookName === 'displayBeforeBodyClosingTag') {
            if (!Configuration::get('SP_SHOW_FLOATING')) {
                return '';
            }

            // "Product only" mode limits the floating bar to product pages.
            if ((Configuration::get('SP_FLOAT_PAGES') ?: 'all') === 'product') {
                $controller = $this->context->controller;
                if (!$controller || $controller->php_self !== 'product') {
                    return '';
                }
            }

            return $this->renderShareBlock('floating', $this->resolveProductShare($configuration));
        }

        // displayFooter, a column, or a manual {widget} placement.
        return $this->renderShareBlock('block', $this->resolveProductShare($configuration));
    }

    public function getWidgetVariables($hookName, array $configuration)
    {
        return $this->getShareVariables('block', $this->resolveProductShare($configuration));
    }

    /* ---------------------------------------------------------------------
     * Rendering helpers
     * ------------------------------------------------------------------- */

    /**
     * Build and fetch the shared template for every placement mode.
     */
    protected function renderShareBlock($mode, array $share)
    {
        $networks = $this->getEnabledNetworks();
        if (empty($networks)) {
            return '';
        }

        $this->context->smarty->assign($this->getShareVariables($mode, $share));

        return $this->display(__FILE__, 'views/templates/hook/share.tpl');
    }

    /**
     * Assemble every variable the share template needs.
     */
    protected function getShareVariables($mode, array $share)
    {
        return [
            'sp' => [
                'mode' => $mode,
                'networks' => $this->getEnabledNetworks(),
                'url' => isset($share['url']) ? $share['url'] : '',
                'title' => isset($share['title']) ? $share['title'] : '',
                'image' => isset($share['image']) ? $share['image'] : '',
                'via' => (string) Configuration::get('SP_X_VIA'),
                'style' => Configuration::get('SP_STYLE') ?: 'solid',
                'shape' => Configuration::get('SP_SHAPE') ?: 'rounded',
                'size' => Configuration::get('SP_SIZE') ?: 'm',
                'color' => Configuration::get('SP_COLOR') ?: 'brand',
                'show_label' => (bool) Configuration::get('SP_SHOW_LABEL'),
                'show_heading' => (bool) Configuration::get('SP_SHOW_HEADING'),
                'float_pos' => Configuration::get('SP_FLOAT_POS') ?: 'left',
                'heading' => $this->l('Share'),
                'copied_label' => $this->l('Copied!'),
            ],
        ];
    }

    /**
     * Full definitions (label, brand color, SVG glyph) for every enabled
     * network, in the canonical order.
     */
    public function getEnabledNetworks()
    {
        $enabled = $this->getEnabledKeys();
        $definitions = $this->getNetworkDefinitions();

        $out = [];
        foreach (self::NETWORKS as $key) {
            if (in_array($key, $enabled, true) && isset($definitions[$key])) {
                $out[] = array_merge(['key' => $key], $definitions[$key]);
            }
        }

        return $out;
    }

    /**
     * The list of enabled network keys, sanitised against the known set.
     */
    protected function getEnabledKeys()
    {
        $raw = (string) Configuration::get('SP_NETWORKS');
        $keys = array_filter(array_map('trim', explode(',', $raw)));

        return array_values(array_intersect(self::NETWORKS, $keys));
    }

    /**
     * Label + brand color + inline SVG for each network. SVGs are simple
     * single-path brand glyphs on a 24x24 viewBox using currentColor so the
     * stylesheet controls solid/outline/monochrome appearance.
     */
    public function getNetworkDefinitions()
    {
        return [
            'facebook' => [
                'label' => $this->l('Facebook'),
                'color' => '#1877F2',
                'svg' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M24 12.07C24 5.4 18.63 0 12 0S0 5.4 0 12.07c0 6.02 4.39 11.01 10.13 11.93v-8.44H7.08v-3.49h3.05V9.41c0-3.02 1.79-4.69 4.53-4.69 1.31 0 2.68.24 2.68.24v2.97h-1.51c-1.49 0-1.96.93-1.96 1.89v2.25h3.33l-.53 3.49h-2.8V24C19.61 23.08 24 18.09 24 12.07z"/></svg>',
            ],
            'x' => [
                'label' => $this->l('X'),
                'color' => '#000000',
                'svg' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M18.9 1.15h3.68l-8.04 9.19L24 22.85h-7.41l-5.8-7.58-6.64 7.58H.46l8.6-9.83L0 1.15h7.6l5.24 6.93 6.06-6.93zm-1.29 19.5h2.04L6.48 3.24H4.29L17.61 20.65z"/></svg>',
            ],
            'whatsapp' => [
                'label' => $this->l('WhatsApp'),
                'color' => '#25D366',
                'svg' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M.06 24l1.68-6.15A11.87 11.87 0 01.16 11.9C.16 5.34 5.5 0 12.06 0a11.82 11.82 0 018.42 3.5 11.82 11.82 0 013.48 8.42c0 6.56-5.34 11.9-11.9 11.9a11.9 11.9 0 01-5.7-1.45L.06 24zM6.6 20.2l.36.22a9.87 9.87 0 005.03 1.38 9.9 9.9 0 009.9-9.88 9.83 9.83 0 00-2.9-7 9.83 9.83 0 00-7-2.9 9.9 9.9 0 00-9.9 9.88c0 1.9.54 3.75 1.57 5.35l.24.38-1 3.64 3.7-1.07zM18.02 14.3c-.1-.16-.36-.26-.75-.46-.4-.2-2.33-1.15-2.69-1.28-.36-.13-.62-.2-.88.2-.26.4-1.01 1.28-1.24 1.54-.23.26-.46.3-.85.1-.4-.2-1.67-.61-3.18-1.96-1.17-1.05-1.97-2.34-2.2-2.74-.23-.4-.02-.61.18-.81.18-.18.4-.46.6-.69.2-.23.26-.4.4-.66.13-.26.06-.49-.03-.69-.1-.2-.88-2.12-1.2-2.9-.32-.77-.64-.66-.88-.67l-.75-.01c-.26 0-.69.1-1.05.49-.36.4-1.37 1.34-1.37 3.26 0 1.92 1.4 3.78 1.6 4.04.2.26 2.76 4.22 6.68 5.92.93.4 1.66.64 2.23.82.94.3 1.79.26 2.46.16.75-.11 2.33-.95 2.66-1.87.33-.92.33-1.71.23-1.87z"/></svg>',
            ],
            'pinterest' => [
                'label' => $this->l('Pinterest'),
                'color' => '#E60023',
                'svg' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 0C5.37 0 0 5.37 0 12c0 5.08 3.16 9.42 7.62 11.17-.1-.95-.2-2.4.04-3.44.22-.93 1.4-5.94 1.4-5.94s-.36-.72-.36-1.78c0-1.67.97-2.92 2.17-2.92 1.02 0 1.51.77 1.51 1.69 0 1.03-.65 2.56-.99 3.98-.28 1.19.6 2.16 1.77 2.16 2.12 0 3.76-2.24 3.76-5.47 0-2.86-2.06-4.86-5-4.86-3.4 0-5.4 2.55-5.4 5.19 0 1.03.4 2.13.89 2.73.1.12.11.22.08.34l-.33 1.36c-.05.22-.17.27-.4.16-1.5-.7-2.43-2.89-2.43-4.65 0-3.78 2.75-7.26 7.92-7.26 4.16 0 7.39 2.96 7.39 6.92 0 4.13-2.6 7.45-6.22 7.45-1.21 0-2.35-.63-2.74-1.38l-.75 2.84c-.27 1.04-1 2.35-1.49 3.15A12 12 0 0012 24c6.63 0 12-5.37 12-12S18.63 0 12 0z"/></svg>',
            ],
            'linkedin' => [
                'label' => $this->l('LinkedIn'),
                'color' => '#0A66C2',
                'svg' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M20.45 20.45h-3.56v-5.57c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.35V9h3.42v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.06 2.06 0 110-4.13 2.06 2.06 0 010 4.13zM7.12 20.45H3.56V9h3.56v11.45zM22.22 0H1.77C.8 0 0 .78 0 1.75v20.5C0 23.2.8 24 1.77 24h20.45c.98 0 1.78-.8 1.78-1.75V1.75C24 .78 23.2 0 22.22 0z"/></svg>',
            ],
            'telegram' => [
                'label' => $this->l('Telegram'),
                'color' => '#26A5E4',
                'svg' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M23.91 3.79L20.3 20.84c-.25 1.21-.98 1.5-2 .94l-5.5-4.07-2.66 2.57c-.3.3-.55.56-1.1.56l.38-5.56 10.12-9.15c.44-.39-.1-.61-.68-.22L6.8 13.16 1.4 11.47c-1.17-.37-1.2-1.17.24-1.73l21.26-8.2c.97-.36 1.82.22 1.51 1.75z"/></svg>',
            ],
            'reddit' => [
                'label' => $this->l('Reddit'),
                'color' => '#FF4500',
                'svg' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M24 11.78a2.6 2.6 0 00-2.6-2.6c-.7 0-1.34.28-1.8.73a12.7 12.7 0 00-6.94-2.2l1.18-5.56 3.87.82a1.86 1.86 0 103.7-.16 1.86 1.86 0 00-3.5-.88l-4.32-.92a.44.44 0 00-.52.34l-1.32 6.2a12.72 12.72 0 00-7.04 2.2 2.6 2.6 0 10-2.87 4.27 5.1 5.1 0 00-.06.8c0 4.06 4.73 7.35 10.56 7.35 5.83 0 10.56-3.29 10.56-7.35 0-.27-.02-.53-.06-.8A2.6 2.6 0 0024 11.78zM6.67 13.7a1.86 1.86 0 113.72 0 1.86 1.86 0 01-3.72 0zm10.35 4.9c-1.26 1.26-3.67 1.36-4.37 1.36-.71 0-3.12-.1-4.38-1.36a.48.48 0 01.68-.68c.8.8 2.5.99 3.7.99 1.19 0 2.9-.19 3.7-.99a.48.48 0 01.67 0 .48.48 0 010 .68zm-.28-3.05a1.86 1.86 0 110-3.71 1.86 1.86 0 010 3.71z"/></svg>',
            ],
            'email' => [
                'label' => $this->l('Email'),
                'color' => '#6B7280',
                'svg' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M2 4h20a2 2 0 012 2v12a2 2 0 01-2 2H2a2 2 0 01-2-2V6a2 2 0 012-2zm10 7L2.5 6h19L12 11zm0 2.2L2 6.8V18h20V6.8l-10 6.4z"/></svg>',
            ],
            'copy' => [
                'label' => $this->l('Copy link'),
                'color' => '#6B7280',
                'svg' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M10.6 13.4a1 1 0 010-1.4l3-3a1 1 0 011.4 0 3.5 3.5 0 010 5l-2 2a3.5 3.5 0 01-5 0 1 1 0 011.4-1.4 1.5 1.5 0 002.2 0l2-2a1.5 1.5 0 000-2.2 1 1 0 01-3 3zM13.4 10.6a1 1 0 010 1.4l-3 3a1 1 0 01-1.4 0 3.5 3.5 0 010-5l2-2a3.5 3.5 0 015 0 1 1 0 01-1.4 1.4 1.5 1.5 0 00-2.2 0l-2 2a1.5 1.5 0 000 2.2 1 1 0 01-1.4 0z"/></svg>',
            ],
            'native' => [
                'label' => $this->l('Share'),
                'color' => '#6B7280',
                'svg' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M18 16.1a3 3 0 00-2.3 1.1l-6.8-3.9a3 3 0 000-2.6l6.7-3.9a3 3 0 10-.9-1.7L7.7 9.2a3 3 0 100 5.6l6.8 3.9a3 3 0 103.5-2.6z"/></svg>',
            ],
        ];
    }

    /**
     * Resolve the URL/title/image to share on the product page.
     * Leaves them empty for generic placements, where front.js falls back to
     * the page's canonical URL and document title.
     */
    protected function resolveProductShare($params)
    {
        $url = '';
        $title = '';
        $image = '';

        $product = isset($params['product']) ? $params['product'] : null;

        if (is_array($product)) {
            $url = isset($product['canonical_url']) ? $product['canonical_url']
                : (isset($product['url']) ? $product['url'] : '');
            $title = isset($product['name']) ? $product['name'] : '';
            if (isset($product['cover']['large']['url'])) {
                $image = $product['cover']['large']['url'];
            } elseif (isset($product['cover']['bySize']['large_default']['url'])) {
                $image = $product['cover']['bySize']['large_default']['url'];
            }
        }

        // Fallback when the presented product is not in the hook params.
        if ($url === '') {
            $id = (int) Tools::getValue('id_product');
            if ($id) {
                $url = $this->context->link->getProductLink($id);
                if ($title === '') {
                    $product = new Product($id, false, (int) $this->context->language->id);
                    if (Validate::isLoadedObject($product)) {
                        $title = is_array($product->name) ? reset($product->name) : $product->name;
                    }
                }
            }
        }

        return ['url' => $url, 'title' => $title, 'image' => $image];
    }

    /* ---------------------------------------------------------------------
     * Configuration page
     * ------------------------------------------------------------------- */

    public function getContent()
    {
        $output = '';

        if (Tools::isSubmit('submitSocialpower')) {
            $output .= $this->postProcess();
        }

        return $output . $this->renderIntro() . $this->renderForm();
    }

    protected function postProcess()
    {
        // Networks: rebuild the CSV from the posted per-network switches so the
        // stored order always follows the canonical NETWORKS order. Each switch
        // always posts 0 or 1; if none of them came through, the form did not
        // render them, so leave the saved selection untouched rather than
        // silently clearing every network.
        $selected = [];
        $posted = false;
        foreach (self::NETWORKS as $key) {
            $value = Tools::getValue('SP_NET_' . $key, null);
            if ($value === null) {
                continue;
            }
            $posted = true;
            if ((int) $value) {
                $selected[] = $key;
            }
        }
        if ($posted) {
            Configuration::updateValue('SP_NETWORKS', implode(',', $selected));
        }

        foreach (['SP_SHOW_PRODUCT', 'SP_SHOW_FLOATING', 'SP_SHOW_LABEL', 'SP_SHOW_HEADING'] as $switch) {
            $this->saveIfPosted($switch, function ($value) {
                return (int) (bool) $value;
            });
        }

        $this->saveIfPosted('SP_FLOAT_POS', function ($value) {
            return $value === 'right' ? 'right' : 'left';
        });
        $this->saveIfPosted('SP_FLOAT_PAGES', function ($value) {
            return $value === 'product' ? 'product' : 'all';
        });

        $enums = [
            'SP_STYLE' => [['solid', 'outline', 'minimal'], 'solid'],
            'SP_SHAPE' => [['rounded', 'circle', 'square'], 'rounded'],
            'SP_SIZE' => [['s', 'm', 'l'], 'm'],
            'SP_COLOR' => [['brand', 'mono'], 'brand'],
        ];
        foreach ($enums as $key => $rule) {
            $module = $this;
            $this->saveIfPosted($key, function ($value) use ($module, $rule) {
                return $module->oneOf($value, $rule[0], $rule[1]);
            });
        }

        // X/Twitter handle: letters, digits and underscore only, no leading @.
        $this->saveIfPosted('SP_X_VIA', function ($value) {
            $via = ltrim(trim((string) $value), '@');

            return Tools::substr(preg_replace('/[^A-Za-z0-9_]/', '', $via), 0, 15);
        });

        return $this->displayConfirmation($this->l('Settings saved.'));
    }

    /**
     * Persist a setting only when the request actually carried it.
     *
     * HelperForm can render each panel as its own form, so a save may post only
     * part of the settings. Without this check the untouched panel would be
     * silently reset to its default.
     */
    protected function saveIfPosted($key, $sanitize)
    {
        $raw = Tools::getValue($key, null);
        if ($raw === null) {
            return;
        }

        Configuration::updateValue($key, $sanitize($raw));
    }

    public function oneOf($value, array $allowed, $default)
    {
        return in_array($value, $allowed, true) ? $value : $default;
    }

    /**
     * Guided panel: how it works + live preview of the current settings.
     */
    protected function renderIntro()
    {
        // Render the buttons with the saved settings for a true live preview.
        $preview = $this->renderShareBlock('inline', [
            'url' => $this->context->shop->getBaseURL(true),
            'title' => Configuration::get('PS_SHOP_NAME'),
            'image' => '',
        ]);

        $this->context->smarty->assign([
            'sp_module_version' => $this->version,
            'sp_css_url' => $this->_path . 'views/css/socialpower.css',
            'sp_preview_html' => $preview,
            'sp_show_product' => (bool) Configuration::get('SP_SHOW_PRODUCT'),
            'sp_show_floating' => (bool) Configuration::get('SP_SHOW_FLOATING'),
            'sp_positions_url' => $this->context->link->getAdminLink('AdminModulesPositions'),
        ]);

        return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
    }

    protected function renderForm()
    {
        // One Yes/No switch per network. HelperForm's "checkbox" type names its
        // inputs inconsistently across PrestaShop versions (and posts nothing
        // when unticked), so switches are used instead: they always post 0 or 1
        // under a predictable SP_NET_<key> name.
        $networkInputs = [];
        foreach ($this->getNetworkDefinitions() as $key => $def) {
            $networkInputs[] = $this->switchInput(
                'SP_NET_' . $key,
                $this->networkFormLabel($key, $def),
                $this->networkFormDesc($key)
            );
        }

        $inputs = [];

        $inputs[] = $this->switchInput('SP_SHOW_PRODUCT', $this->l('Show on product pages'), $this->l('Display a share row under each product.'));
        $inputs[] = $this->switchInput('SP_SHOW_FLOATING', $this->l('Show floating bar'), $this->l('Pin a vertical share bar to the side of the screen.'));
        $inputs[] = [
            'type' => 'radio',
            'label' => $this->l('Floating bar side'),
            'name' => 'SP_FLOAT_POS',
            'values' => [
                ['id' => 'pos_left', 'value' => 'left', 'label' => $this->l('Left')],
                ['id' => 'pos_right', 'value' => 'right', 'label' => $this->l('Right')],
            ],
        ];
        $inputs[] = [
            'type' => 'radio',
            'label' => $this->l('Floating bar pages'),
            'name' => 'SP_FLOAT_PAGES',
            'values' => [
                ['id' => 'pages_all', 'value' => 'all', 'label' => $this->l('All pages')],
                ['id' => 'pages_product', 'value' => 'product', 'label' => $this->l('Product pages only')],
            ],
        ];
        $inputs[] = [
            'type' => 'radio',
            'label' => $this->l('Button style'),
            'name' => 'SP_STYLE',
            'values' => [
                ['id' => 'style_solid', 'value' => 'solid', 'label' => $this->l('Solid')],
                ['id' => 'style_outline', 'value' => 'outline', 'label' => $this->l('Outline')],
                ['id' => 'style_minimal', 'value' => 'minimal', 'label' => $this->l('Minimal (icon only)')],
            ],
        ];
        $inputs[] = [
            'type' => 'radio',
            'label' => $this->l('Button shape'),
            'name' => 'SP_SHAPE',
            'values' => [
                ['id' => 'shape_rounded', 'value' => 'rounded', 'label' => $this->l('Rounded')],
                ['id' => 'shape_circle', 'value' => 'circle', 'label' => $this->l('Circle')],
                ['id' => 'shape_square', 'value' => 'square', 'label' => $this->l('Square')],
            ],
        ];
        $inputs[] = [
            'type' => 'radio',
            'label' => $this->l('Button size'),
            'name' => 'SP_SIZE',
            'values' => [
                ['id' => 'size_s', 'value' => 's', 'label' => $this->l('Small')],
                ['id' => 'size_m', 'value' => 'm', 'label' => $this->l('Medium')],
                ['id' => 'size_l', 'value' => 'l', 'label' => $this->l('Large')],
            ],
        ];
        $inputs[] = [
            'type' => 'radio',
            'label' => $this->l('Colors'),
            'name' => 'SP_COLOR',
            'values' => [
                ['id' => 'color_brand', 'value' => 'brand', 'label' => $this->l('Brand colors')],
                ['id' => 'color_mono', 'value' => 'mono', 'label' => $this->l('Neutral (single color)')],
            ],
        ];
        $inputs[] = $this->switchInput('SP_SHOW_LABEL', $this->l('Show text labels'), $this->l('Show the network name next to each icon.'));
        $inputs[] = $this->switchInput('SP_SHOW_HEADING', $this->l('Show "Share" heading'), $this->l('Display a small heading above the buttons.'));
        $inputs[] = [
            'type' => 'text',
            'label' => $this->l('X (Twitter) handle'),
            'name' => 'SP_X_VIA',
            'desc' => $this->l('Optional. Your handle without @, e.g. megventure. Added to tweets as "via @handle".'),
            'col' => 3,
        ];

        $networksForm = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Networks'),
                    'icon' => 'icon-share',
                ],
                'input' => $networkInputs,
                'submit' => [
                    'title' => $this->l('Save'),
                    'name' => 'submitSocialpower',
                ],
            ],
        ];

        $displayForm = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Appearance and placement'),
                    'icon' => 'icon-cogs',
                ],
                'input' => $inputs,
                'submit' => [
                    'title' => $this->l('Save'),
                    'name' => 'submitSocialpower',
                ],
            ],
        ];

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->submit_action = 'submitSocialpower';
        $helper->default_form_language = (int) $this->context->language->id;
        $helper->fields_value = $this->getFormValues();

        return $helper->generateForm([$networksForm, $displayForm]);
    }

    /**
     * Form label for a network. The "native" button is labelled "Share" on the
     * storefront, which would be ambiguous as a settings row.
     */
    protected function networkFormLabel($key, array $def)
    {
        if ($key === 'native') {
            return $this->l('Native share (mobile)');
        }

        return $def['label'];
    }

    /**
     * Extra guidance for the two non-obvious buttons.
     */
    protected function networkFormDesc($key)
    {
        if ($key === 'copy') {
            return $this->l('Copies the page address to the clipboard.');
        }

        if ($key === 'native') {
            return $this->l('Opens the built-in share sheet. Only appears on supported mobile devices.');
        }

        return '';
    }

    protected function switchInput($name, $label, $desc)
    {
        $input = [
            'type' => 'switch',
            'label' => $label,
            'name' => $name,
            'is_bool' => true,
            'values' => [
                ['id' => $name . '_on', 'value' => 1, 'label' => $this->l('Yes')],
                ['id' => $name . '_off', 'value' => 0, 'label' => $this->l('No')],
            ],
        ];

        if ($desc !== '') {
            $input['desc'] = $desc;
        }

        return $input;
    }

    protected function getFormValues()
    {
        $values = [
            'SP_SHOW_PRODUCT' => (int) Configuration::get('SP_SHOW_PRODUCT'),
            'SP_SHOW_FLOATING' => (int) Configuration::get('SP_SHOW_FLOATING'),
            'SP_FLOAT_POS' => Configuration::get('SP_FLOAT_POS') ?: 'left',
            'SP_FLOAT_PAGES' => Configuration::get('SP_FLOAT_PAGES') ?: 'all',
            'SP_STYLE' => Configuration::get('SP_STYLE') ?: 'solid',
            'SP_SHAPE' => Configuration::get('SP_SHAPE') ?: 'rounded',
            'SP_SIZE' => Configuration::get('SP_SIZE') ?: 'm',
            'SP_COLOR' => Configuration::get('SP_COLOR') ?: 'brand',
            'SP_SHOW_LABEL' => (int) Configuration::get('SP_SHOW_LABEL'),
            'SP_SHOW_HEADING' => (int) Configuration::get('SP_SHOW_HEADING'),
            'SP_X_VIA' => Configuration::get('SP_X_VIA'),
        ];

        // One value per network switch, matching the SP_NET_<key> input names.
        $enabled = $this->getEnabledKeys();
        foreach (self::NETWORKS as $key) {
            $values['SP_NET_' . $key] = in_array($key, $enabled, true) ? 1 : 0;
        }

        return $values;
    }
}
