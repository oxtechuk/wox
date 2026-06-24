<?php

namespace Wox\Api;

defined('ABSPATH') || exit;

class MockProvider implements ProviderInterface
{
    public function send_text(string $to, string $message): array
    {
        $this->log("[MOCK] Text to {$to}: {$message}");

        return [
            'messaging_product' => 'whatsapp',
            'contacts' => [['input' => $to, 'wa_id' => $to]],
            'messages' => [['id' => 'mock_' . uniqid()]],
        ];
    }

    public function send_template(string $to, string $template_name, array $parameters = []): array
    {
        $params = implode(', ', $parameters);
        $this->log("[MOCK] Template '{$template_name}' to {$to} with params: {$params}");

        return [
            'messaging_product' => 'whatsapp',
            'contacts' => [['input' => $to, 'wa_id' => $to]],
            'messages' => [['id' => 'mock_' . uniqid()]],
        ];
    }

    public function verify_webhook(string $mode, string $token, string $challenge): mixed
    {
        return $challenge;
    }

    public function handle_webhook_payload(array $payload): array
    {
        return [];
    }

    public function test_connection(): bool
    {
        return true;
    }

    private function log(string $message): void
    {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[WhatsApp OX] ' . $message);
        }
    }
}
