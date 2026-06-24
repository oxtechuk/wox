<?php

namespace Wox\Api;

defined('ABSPATH') || exit;

class ProviderFactory
{
    public static function create(): ProviderInterface
    {
        if ('yes' === get_option('wox_sandbox_mode', 'no')) {
            return new MockProvider();
        }

        $provider = get_option('wox_provider', 'whatsapp_cloud');

        return match ($provider) {
            'twilio' => new TwilioProvider(),
            default => new WhatsAppCloudApi(),
        };
    }

    public static function create_for_test(): array
    {
        $provider = get_option('wox_provider', 'whatsapp_cloud');

        if ('twilio' === $provider) {
            $twilio = new TwilioProvider();
            return [$twilio, $twilio->test_connection()];
        }

        $wa = new WhatsAppCloudApi();
        return [$wa, $wa->test_connection()];
    }
}
