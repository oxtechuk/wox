<?php

namespace Wox\Services;

use Wox\Api\ProviderFactory;

defined('ABSPATH') || exit;

class CampaignService
{
    public function get_customers(array $segment = []): array
    {
        $args = [
            'role__in' => ['customer', 'subscriber'],
            'number' => -1,
            'fields' => ['ID', 'display_name', 'user_email'],
        ];

        $users = get_users($args);
        $customers = [];

        foreach ($users as $user) {
            $phone = get_user_meta($user->ID, 'billing_phone', true);
            if (empty($phone)) {
                continue;
            }

            $phone = $this->normalize_phone($phone);

            $spent = wc_get_customer_total_spent($user->ID);
            $orders = wc_get_customer_order_count($user->ID);

            if (!empty($segment['min_spent']) && $spent < (float) $segment['min_spent']) {
                continue;
            }
            if (!empty($segment['max_spent']) && $spent > (float) $segment['max_spent']) {
                continue;
            }
            if (!empty($segment['min_orders']) && $orders < (int) $segment['min_orders']) {
                continue;
            }

            $customers[] = [
                'id' => $user->ID,
                'name' => $user->display_name,
                'phone' => $phone,
                'email' => $user->user_email,
                'spent' => $spent,
                'orders' => $orders,
            ];
        }

        return $customers;
    }

    public function get_guest_phones(): array
    {
        global $wpdb;

        return $wpdb->get_col(
            "SELECT DISTINCT meta_value FROM {$wpdb->prefix}wc_orders_meta
            WHERE meta_key = '_billing_phone' AND meta_value != ''
            AND order_id NOT IN (
                SELECT order_id FROM {$wpdb->prefix}wc_orders_meta WHERE meta_key = '_customer_user' AND meta_value > 0
            )"
        );
    }

    public function send_broadcast(int $campaign_id): array
    {
        global $wpdb;

        $campaign = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}wox_campaigns WHERE id = %d", $campaign_id
        ));
        if (!$campaign) {
            return ['sent' => 0, 'failed' => 0];
        }

        $logs = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}wox_campaign_log WHERE campaign_id = %d AND status = 'pending'", $campaign_id
        ));

        if (empty($logs)) {
            return ['sent' => 0, 'failed' => 0];
        }

        $provider = ProviderFactory::create();
        $store_name = get_option('wox_store_name', get_bloginfo('name'));
        $results = ['sent' => 0, 'failed' => 0];

        $template_body = '';
        if ($campaign->template_id) {
            $tpl = $wpdb->get_row($wpdb->prepare(
                "SELECT body FROM {$wpdb->prefix}wox_templates WHERE id = %d", $campaign->template_id
            ));
            $template_body = $tpl->body ?? '';
        }

        foreach ($logs as $log) {
            $customer = $log->customer_id ? get_user_by('id', $log->customer_id) : null;
            $name = $customer ? $customer->display_name : __('Customer', 'whatsapp-ox');

            $variables = [
                '{{first_name}}' => $name,
                '{{full_name}}' => $name,
                '{{store_name}}' => $store_name,
            ];

            $message = str_replace(array_keys($variables), array_values($variables), $template_body);

            $response = $provider->send_text($log->phone, $message);
            $status = isset($response['error']) ? 'failed' : 'sent';

            $wpdb->update(
                $wpdb->prefix . 'wox_campaign_log',
                [
                    'status' => $status,
                    'error_message' => $response['error'] ?? null,
                    'sent_at' => current_time('mysql'),
                ],
                ['id' => $log->id]
            );

            if ('sent' === $status) {
                $results['sent']++;
            } else {
                $results['failed']++;
            }
        }

        $wpdb->update(
            $wpdb->prefix . 'wox_campaigns',
            [
                'sent_count' => $results['sent'],
                'status' => 'completed',
                'completed_at' => current_time('mysql'),
            ],
            ['id' => $campaign_id]
        );

        return $results;
    }

    private function normalize_phone(string $phone): string
    {
        return preg_replace('/[^0-9]/', '', $phone);
    }
}
