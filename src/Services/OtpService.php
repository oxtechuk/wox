<?php

namespace Wox\Services;

use Wox\Api\ProviderInterface;
use Wox\Api\ProviderFactory;

defined('ABSPATH') || exit;

class OtpService
{
    private ProviderInterface $provider;

    public function __construct()
    {
        $this->provider = $this->get_provider();
    }

    public function generate(string $phone, string $context = 'checkout'): ?string
    {
        if ('yes' !== get_option('wox_otp_enabled', 'no')) {
            return null;
        }

        global $wpdb;

        $cooldown = (int) get_option('wox_otp_cooldown', 60);
        $last = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT created_at FROM {$wpdb->prefix}wox_otps WHERE phone = %s AND context = %s ORDER BY created_at DESC LIMIT 1",
                $phone,
                $context
            )
        );

        if ($last && strtotime($last) + $cooldown > time()) {
            return null;
        }

        $length = (int) get_option('wox_otp_length', 6);
        $code = $this->generate_code($length);
        $expiry = (int) get_option('wox_otp_expiry', 300);

        $wpdb->insert($wpdb->prefix . 'wox_otps', [
            'phone' => $phone,
            'code' => wp_hash($code),
            'context' => $context,
            'max_attempts' => (int) get_option('wox_otp_max_attempts', 5),
            'expires_at' => gmdate('Y-m-d H:i:s', time() + $expiry),
        ]);

        $this->provider->send_text($phone, sprintf(__('Your verification code is: %s', 'whatsapp-ox'), $code));

        return $code;
    }

    public function verify(string $phone, string $code, string $context = 'checkout'): bool
    {
        global $wpdb;

        $otp = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}wox_otps WHERE phone = %s AND context = %s AND verified_at IS NULL ORDER BY created_at DESC LIMIT 1",
                $phone,
                $context
            )
        );

        if (!$otp) {
            return false;
        }

        if (strtotime($otp->expires_at) < time()) {
            return false;
        }

        if ((int) $otp->attempts >= (int) $otp->max_attempts) {
            return false;
        }

        $wpdb->update(
            $wpdb->prefix . 'wox_otps',
            ['attempts' => $otp->attempts + 1],
            ['id' => $otp->id]
        );

        if (!wp_check_password($code, $otp->code)) {
            return false;
        }

        $wpdb->update(
            $wpdb->prefix . 'wox_otps',
            ['verified_at' => current_time('mysql')],
            ['id' => $otp->id]
        );

        return true;
    }

    private function generate_code(int $length): string
    {
        return (string) random_int(10 ** ($length - 1), (10 ** $length) - 1);
    }

    private function get_provider(): ProviderInterface
    {
        return ProviderFactory::create();
    }
}
