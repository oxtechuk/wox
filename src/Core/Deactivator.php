<?php

namespace Wox\Core;

use Wox\Services\CartCronService;

defined('ABSPATH') || exit;

class Deactivator
{
    public static function deactivate(): void
    {
        $cart_cron = new CartCronService();
        $cart_cron->unschedule();

        wp_clear_scheduled_hook('wox_daily_cleanup');
    }
}
