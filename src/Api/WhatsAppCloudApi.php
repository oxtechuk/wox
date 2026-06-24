<?php

namespace Wox\Api;

defined('ABSPATH') || exit;

class WhatsAppCloudApi implements ProviderInterface
{
    private string $phone_number_id;
    private string $access_token;
    private string $api_version;
    private string $base_url;

    public function __construct(string $phone_number_id = '', string $access_token = '', string $api_version = 'v21.0')
    {
        $this->phone_number_id = $phone_number_id ?: get_option('wox_phone_number_id', '');
        $this->access_token = $access_token ?: get_option('wox_access_token', '');
        $this->api_version = $api_version;
        $this->base_url = "https://graph.facebook.com/{$this->api_version}/{$this->phone_number_id}/messages";
    }

    public function send_text(string $to, string $message): array
    {
        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'text',
            'text' => ['preview_url' => false, 'body' => $message],
        ];

        return $this->post($body);
    }

    public function send_template(string $to, string $template_name, array $parameters = []): array
    {
        $components = [];

        if (!empty($parameters)) {
            $params = [];
            foreach ($parameters as $value) {
                $params[] = ['type' => 'text', 'text' => $value];
            }
            $components[] = ['type' => 'body', 'parameters' => $params];
        }

        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'template',
            'template' => [
                'name' => $template_name,
                'language' => ['code' => 'en'],
            ],
        ];

        if (!empty($components)) {
            $body['template']['components'] = $components;
        }

        return $this->post($body);
    }

    public function verify_webhook(string $mode, string $token, string $challenge): mixed
    {
        $verify_token = get_option('wox_webhook_verify_token', '');

        if ('subscribe' === $mode && $token === $verify_token) {
            return $challenge;
        }

        return false;
    }

    public function handle_webhook_payload(array $payload): array
    {
        $messages = [];

        if (!isset($payload['entry'])) {
            return $messages;
        }

        foreach ($payload['entry'] as $entry) {
            foreach ($entry['changes'] ?? [] as $change) {
                if (($change['field'] ?? '') !== 'messages') {
                    continue;
                }

                $value = $change['value'] ?? [];
                foreach ($value['messages'] ?? [] as $msg) {
                    $messages[] = [
                        'from' => $msg['from'] ?? '',
                        'id' => $msg['id'] ?? '',
                        'type' => $msg['type'] ?? '',
                        'timestamp' => $msg['timestamp'] ?? '',
                        'text' => $msg['text']['body'] ?? '',
                    ];
                }

                foreach ($value['statuses'] ?? [] as $status) {
                    $messages[] = [
                        'type' => 'status',
                        'id' => $status['id'] ?? '',
                        'status' => $status['status'] ?? '',
                        'recipient_id' => $status['recipient_id'] ?? '',
                        'timestamp' => $status['timestamp'] ?? '',
                    ];
                }
            }
        }

        return $messages;
    }

    public function test_connection(): bool
    {
        $response = $this->send_text(
            get_option('wox_test_phone', ''),
            __('WhatsApp OX connection test successful.', 'whatsapp-ox')
        );

        return isset($response['error']) === false;
    }

    private function post(array $body): array
    {
        $response = wp_remote_post($this->base_url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->access_token,
                'Content-Type' => 'application/json',
            ],
            'body' => wp_json_encode($body),
            'timeout' => 30,
        ]);

        if (is_wp_error($response)) {
            return ['error' => $response->get_error_message()];
        }

        return json_decode(wp_remote_retrieve_body($response), true) ?: ['error' => 'Invalid response'];
    }
}
