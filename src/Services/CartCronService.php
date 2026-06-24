<?php

namespace Wox\Services;

use Wox\Api\ProviderFactory;
use Wox\Traits\Singleton;

defined('ABSPATH') || exit;

class CartCronService
{
    use Singleton;
    public function __construct()
    {
        add_action('wox_cart_reminder', [$this, 'process_reminders']);
    }

    public function schedule(): void
    {
        if (!wp_next_scheduled('wox_cart_reminder')) {
            wp_schedule_event(time(), 'wox_15min', 'wox_cart_reminder');
        }
    }

    public function unschedule(): void
    {
        wp_clear_scheduled_hooks('wox_cart_reminder');
    }

    public function add_cron_interval($schedules): array
    {
        $schedules['wox_15min'] = [
            'interval' => 900,
            'display' => __('Every 15 minutes', 'whatsapp-ox'),
        ];
        return $schedules;
    }

    public function process_reminders(): void
    {
        if ('yes' !== get_option('wox_cart_enabled', 'no')) {
            return;
        }

        global $wpdb;

        $delay = (int) get_option('wox_cart_delay', 60);
        $max_reminders = (int) get_option('wox_cart_reminder_count', 3);
        $coupon = get_option('wox_cart_coupon', '');

        $carts = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}wox_carts
                WHERE status = %s
                AND reminder_count < %d
                AND TIMESTAMPDIFF(MINUTE, updated_at, NOW()) >= %d
                ORDER BY updated_at ASC
                LIMIT 20",
                'abandoned',
                $max_reminders,
                $delay
            )
        );

        if (empty($carts)) {
            return;
        }

        $provider = ProviderFactory::create();
        $store_name = get_option('wox_store_name', get_bloginfo('name'));

        foreach ($carts as $cart) {
            if (empty($cart->phone)) {
                continue;
            }

            $items = json_decode($cart->cart_data, true);
            $item_names = [];
            if (is_array($items)) {
                foreach ($items as $item) {
                    $item_names[] = $item['data']->get_name() ?? 'Product';
                }
            }

            $message = sprintf(
                __("Hello! You left items in your cart at %s:\n%s\n\nTotal: %s", 'whatsapp-ox'),
                $store_name,
                implode(', ', array_slice($item_names, 0, 3)),
                wc_price($cart->cart_total)
            );

            if ($coupon) {
                $message .= sprintf(__("\n\nUse coupon: %s to complete your order!", 'whatsapp-ox'), $coupon);
            }

            $message .= sprintf("\n\n%s", __('Shop now: ', 'whatsapp-ox') . home_url('/cart'));

            $result = $provider->send_text($cart->phone, $message);

            $wpdb->update(
                $wpdb->prefix . 'wox_carts',
                ['reminder_count' => $cart->reminder_count + 1],
                ['id' => $cart->id]
            );

            $wpdb->insert($wpdb->prefix . 'wox_messages', [
                'phone' => $cart->phone,
                'message_body' => $message,
                'status' => isset($result['error']) ? 'failed' : 'sent',
                'error_message' => $result['error'] ?? null,
                'provider_message_id' => $result['messages'][0]['id'] ?? null,
                'sent_at' => current_time('mysql'),
            ]);
        }
    }
}
