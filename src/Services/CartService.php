<?php

namespace Wox\Services;

defined('ABSPATH') || exit;

class CartService
{
    public function capture_cart(): void
    {
        if ('yes' !== get_option('wox_cart_enabled', 'no')) {
            return;
        }

        $cart = WC()->cart;
        if (!$cart || $cart->is_empty()) {
            return;
        }

        global $wpdb;

        $phone = $this->get_customer_phone();
        if (empty($phone)) {
            return;
        }

        $session_id = WC()->session ? WC()->session->get_customer_id() : '';
        $user_id = get_current_user_id();
        $cart_data = wp_json_encode($cart->get_cart());
        $cart_total = $cart->get_total('edit');

        $existing = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT id FROM {$wpdb->prefix}wox_carts WHERE (session_id = %s OR (user_id = %d AND user_id > 0)) AND status = %s LIMIT 1",
                $session_id,
                $user_id,
                'abandoned'
            )
        );

        if ($existing) {
            $wpdb->update(
                $wpdb->prefix . 'wox_carts',
                [
                    'cart_data' => $cart_data,
                    'cart_total' => $cart_total,
                    'phone' => $phone,
                    'updated_at' => current_time('mysql'),
                ],
                ['id' => $existing]
            );
        } else {
            $wpdb->insert($wpdb->prefix . 'wox_carts', [
                'session_id' => $session_id,
                'user_id' => $user_id ?: 0,
                'phone' => $phone,
                'cart_data' => $cart_data,
                'cart_total' => $cart_total,
                'currency' => get_woocommerce_currency(),
                'status' => 'abandoned',
            ]);
        }
    }

    public function mark_recovered(int $cart_id): void
    {
        global $wpdb;
        $wpdb->update(
            $wpdb->prefix . 'wox_carts',
            [
                'status' => 'recovered',
                'recovered_at' => current_time('mysql'),
            ],
            ['id' => $cart_id]
        );
    }

    private function get_customer_phone(): string
    {
        if (is_user_logged_in()) {
            $customer = new \WC_Customer(get_current_user_id());
            return $customer->get_billing_phone() ?: '';
        }

        if (isset($_POST['billing_phone'])) {
            return sanitize_text_field($_POST['billing_phone']);
        }

        return '';
    }
}
