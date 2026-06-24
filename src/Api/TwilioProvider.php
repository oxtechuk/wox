<?php

namespace Wox\Api;

defined('ABSPATH') || exit;

class TwilioProvider implements ProviderInterface
{
    private string $account_sid;
    private string $auth_token;
    private string $from_number;

    public function __construct(string $account_sid = '', string $auth_token = '', string $from_number = '')
    {
        $this->account_sid = $account_sid ?: get_option('wox_twilio_account_sid', '');
        $this->auth_token = $auth_token ?: get_option('wox_twilio_auth_token', '');
        $this->from_number = $from_number ?: get_option('wox_twilio_from_number', '');
    }

    public function send_text(string $to, string $message): array
    {
        $url = "https://api.twilio.com/2010-04-01/Accounts/{$this->account_sid}/Messages.json";

        $response = wp_remote_post($url, [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode("{$this->account_sid}:{$this->auth_token}"),
            ],
            'body' => [
                'From' => 'whatsapp:' . $this->from_number,
                'To' => 'whatsapp:' . $to,
                'Body' => $message,
            ],
            'timeout' => 30,
        ]);

        if (is_wp_error($response)) {
            return ['error' => $response->get_error_message()];
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        if (isset($body['error_message'])) {
            return ['error' => $body['error_message']];
        }

        return [
            'messaging_product' => 'twilio',
            'sid' => $body['sid'] ?? '',
            'status' => $body['status'] ?? '',
            'messages' => [['id' => $body['sid'] ?? '']],
        ];
    }

    public function send_template(string $to, string $template_name, array $parameters = []): array
    {
        $message = $template_name;
        if (!empty($parameters)) {
            $message .= ': ' . implode(', ', $parameters);
        }

        return $this->send_text($to, $message);
    }

    public function verify_webhook(string $mode, string $token, string $challenge): mixed
    {
        return $challenge;
    }

    public function handle_webhook_payload(array $payload): array
    {
        $messages = [];

        if (isset($payload['SmsMessageSid'])) {
            $messages[] = [
                'type' => 'status',
                'id' => $payload['SmsMessageSid'] ?? '',
                'status' => $payload['SmsStatus'] ?? 'delivered',
                'recipient_id' => $payload['To'] ?? '',
                'timestamp' => $payload['Timestamp'] ?? '',
            ];
        }

        return $messages;
    }

    public function test_connection(): bool
    {
        if (empty($this->account_sid) || empty($this->auth_token) || empty($this->from_number)) {
            return false;
        }

        $url = "https://api.twilio.com/2010-04-01/Accounts/{$this->account_sid}.json";

        $response = wp_remote_get($url, [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode("{$this->account_sid}:{$this->auth_token}"),
            ],
            'timeout' => 15,
        ]);

        if (is_wp_error($response)) {
            return false;
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);
        return isset($body['sid']) && empty($body['error_message']);
    }
}
