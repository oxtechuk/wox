<?php

namespace Wox\Core;

use Wox\Database\Schema;
use Wox\Services\CartCronService;

defined('ABSPATH') || exit;

class Activator
{
    public static function activate(): void
    {
        self::check_requirements();

        Schema::create_tables();

        $cart_cron = new CartCronService();
        $cart_cron->schedule();

        update_option('wox_version', WOX_VERSION);
        update_option('wox_db_version', Schema::DB_VERSION);
    }

    private static function check_requirements(): void
    {
        if (version_compare(PHP_VERSION, '8.0', '<')) {
            deactivate_plugins(plugin_basename(WOX_PLUGIN_FILE));
            wp_die(esc_html__('WhatsApp OX requires PHP 8.0 or higher.', 'whatsapp-ox'));
        }

        if (!class_exists('WooCommerce')) {
            deactivate_plugins(plugin_basename(WOX_PLUGIN_FILE));
            wp_die(esc_html__('WhatsApp OX requires WooCommerce to be installed and active.', 'whatsapp-ox'));
        }
    }
}
