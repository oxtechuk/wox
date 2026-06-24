<?php

namespace Wox\Api;

use Wox\Traits\Singleton;
use Wox\Api\ProviderFactory;

defined('ABSPATH') || exit;

class Webhook
{
    use Singleton;
    const NAMESPACE = 'whatsapp-ox/v1';

    public function register_routes(): void
    {
        register_rest_route(self::NAMESPACE, '/webhook', [
            'methods' => 'GET',
            'callback' => [$this, 'handle_verification'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route(self::NAMESPACE, '/webhook', [
            'methods' => 'POST',
            'callback' => [$this, 'handle_message'],
            'permission_callback' => '__return_true',
        ]);
    }

    public function handle_verification($request)
    {
        $mode = $request->get_param('hub_mode');
        $token = $request->get_param('hub_verify_token');
        $challenge = $request->get_param('hub_challenge');

        $verify_token = get_option('wox_webhook_verify_token', '');

        if ('subscribe' === $mode && $token === $verify_token) {
            return new \WP_REST_Response($challenge, 200);
        }

        return new \WP_REST_Response('Forbidden', 403);
    }

    public function handle_message($request)
    {
        $body = $request->get_json_params();
        $provider = ProviderFactory::create();
        $messages = $provider->handle_webhook_payload($body);

        foreach ($messages as $msg) {
            if ('status' === ($msg['type'] ?? '')) {
                $this->update_message_status($msg['id'], $msg['status'], $msg['recipient_id'] ?? '');
            } elseif ('text' === ($msg['type'] ?? '') && !empty($msg['from'])) {
                $this->store_incoming($msg['from'], $msg['text'] ?? '', $msg['id'] ?? '');
            }
        }

        return new \WP_REST_Response(['status' => 'ok'], 200);
    }

    private function store_incoming(string $phone, string $text, string $provider_id): void
    {
        global $wpdb;

        $name = '';
        $user = get_users([
            'meta_key' => 'billing_phone',
            'meta_value' => $phone,
            'number' => 1,
            'fields' => ['display_name'],
        ]);
        if (!empty($user)) {
            $name = $user[0]->display_name;
        }

        if (!$name) {
            $order = $wpdb->get_var($wpdb->prepare(
                "SELECT p.post_title FROM {$wpdb->posts} p
                INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
                WHERE pm.meta_key = '_billing_phone' AND pm.meta_value = %s
                AND p.post_type = 'shop_order'
                LIMIT 1",
                $phone
            ));
            if ($order) {
                $name = $order;
            }
        }

        $wpdb->insert($wpdb->prefix . 'wox_conversations', [
            'phone' => $phone,
            'name' => $name,
            'message_body' => $text,
            'direction' => 'incoming',
            'status' => 'unread',
            'provider_message_id' => $provider_id,
        ]);
    }

    private function update_message_status(string $provider_id, string $status, string $recipient): void
    {
        global $wpdb;

        $map = [
            'sent' => 'sent',
            'delivered' => 'delivered',
            'read' => 'read',
            'failed' => 'failed',
        ];

        $local_status = $map[$status] ?? 'unknown';
        $date_field = 'sent' === $status ? 'sent_at' : ('delivered' === $status ? 'delivered_at' : null);

        $data = ['status' => $local_status];
        if ($date_field) {
            $data[$date_field] = current_time('mysql');
        }

        $wpdb->update(
            $wpdb->prefix . 'wox_messages',
            $data,
            ['provider_message_id' => $provider_id]
        );
    }
}
