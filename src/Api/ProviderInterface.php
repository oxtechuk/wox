<?php

namespace Wox\Api;

defined('ABSPATH') || exit;

interface ProviderInterface
{
    public function send_text(string $to, string $message): array;
    public function send_template(string $to, string $template_name, array $parameters = []): array;
    public function verify_webhook(string $mode, string $token, string $challenge): mixed;
    public function handle_webhook_payload(array $payload): array;
    public function test_connection(): bool;
}
