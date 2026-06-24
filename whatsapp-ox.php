<?php
/**
 * Plugin Name: WhatsApp OX
 * Plugin URI:  https://ox.tech/
 * Description: WooCommerce WhatsApp operations — notifications, OTP verification, abandoned cart recovery, campaigns, and more.
 * Version:     1.0.0
 * Author:      Ox Tech
 * Text Domain: whatsapp-ox
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 8.0
 * Requires Plugins: woocommerce
 * WC requires at least: 6.0
 * WC tested up to: 10.9.1
 */

defined('ABSPATH') || exit;

define('WOX_VERSION', '1.0.0');
define('WOX_PLUGIN_FILE', __FILE__);
define('WOX_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WOX_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WOX_ASSETS_URL', WOX_PLUGIN_URL . 'assets');

if (!file_exists(WOX_PLUGIN_DIR . 'vendor/autoload.php')) {
    return;
}

require_once WOX_PLUGIN_DIR . 'vendor/autoload.php';

add_action('before_woocommerce_init', function () {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', WOX_PLUGIN_FILE, true);
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('cart_checkout_blocks', WOX_PLUGIN_FILE, true);
    }
});

register_activation_hook(__FILE__, ['Wox\Core\Activator', 'activate']);
register_deactivation_hook(__FILE__, ['Wox\Core\Deactivator', 'deactivate']);

add_action('plugins_loaded', ['Wox\Core\Plugin', 'get_instance']);
