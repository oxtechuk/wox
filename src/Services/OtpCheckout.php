<?php

namespace Wox\Services;

defined('ABSPATH') || exit;

class OtpCheckout
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public function enqueue_scripts(): void
    {
        if (!is_checkout() && !is_account_page()) {
            return;
        }

        if ('yes' !== get_option('wox_otp_enabled', 'no')) {
            return;
        }

        wp_enqueue_script('wox-otp', WOX_ASSETS_URL . '/js/otp.js', ['jquery'], WOX_VERSION, true);

        wp_localize_script('wox-otp', 'wox_otp', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wox_otp_nonce'),
            'strings' => [
                'send' => __('Send Code', 'whatsapp-ox'),
                'verify' => __('Verify', 'whatsapp-ox'),
                'resend' => __('Resend Code', 'whatsapp-ox'),
                'sending' => __('Sending...', 'whatsapp-ox'),
                'enter_code' => __('Enter verification code', 'whatsapp-ox'),
            ],
        ]);
    }

    public function add_checkout_fields($fields): array
    {
        if ('yes' !== get_option('wox_otp_enabled', 'no') || 'yes' !== get_option('wox_otp_checkout', 'no')) {
            return $fields;
        }

        $fields['billing']['wox_otp_verify'] = [
            'label' => __('Phone Verification', 'whatsapp-ox'),
            'required' => false,
            'type' => 'text',
            'class' => ['form-row-wide'],
            'priority' => 115,
            'clear' => true,
            'custom_attributes' => [
                'placeholder' => __('Click "Send Code" to verify your phone', 'whatsapp-ox'),
                'readonly' => 'readonly',
            ],
        ];

        return $fields;
    }

    public function validate_checkout($fields, $errors): void
    {
        if ('yes' !== get_option('wox_otp_enabled', 'no') || 'yes' !== get_option('wox_otp_checkout', 'no')) {
            return;
        }

        $phone = $fields['billing_phone'] ?? '';
        if (empty($phone)) {
            return;
        }

        global $wpdb;
        $verified = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM {$wpdb->prefix}wox_otps WHERE phone = %s AND context = 'checkout' AND verified_at IS NOT NULL",
                $phone
            )
        );

        if (!$verified) {
            $errors->add('wox_otp_required', __('Please verify your phone number via WhatsApp OTP.', 'whatsapp-ox'));
        }
    }

    public function ajax_send_otp(): void
    {
        check_ajax_referer('wox_otp_nonce', 'nonce');

        $phone = sanitize_text_field($_POST['phone'] ?? '');

        if (empty($phone)) {
            wp_send_json_error(['message' => __('Phone number is required.', 'whatsapp-ox')]);
        }

        $otp_service = new OtpService();
        $code = $otp_service->generate($phone, 'checkout');

        if (null === $code) {
            wp_send_json_error(['message' => __('Please wait before requesting a new code.', 'whatsapp-ox')]);
        }

        wp_send_json_success(['message' => __('Code sent to your WhatsApp.', 'whatsapp-ox')]);
    }

    public function ajax_verify_otp(): void
    {
        check_ajax_referer('wox_otp_nonce', 'nonce');

        $phone = sanitize_text_field($_POST['phone'] ?? '');
        $code = sanitize_text_field($_POST['code'] ?? '');

        if (empty($phone) || empty($code)) {
            wp_send_json_error(['message' => __('Phone and code are required.', 'whatsapp-ox')]);
        }

        $otp_service = new OtpService();
        $verified = $otp_service->verify($phone, $code, 'checkout');

        if (!$verified) {
            wp_send_json_error(['message' => __('Invalid or expired code.', 'whatsapp-ox')]);
        }

        wp_send_json_success(['message' => __('Phone verified successfully.', 'whatsapp-ox')]);
    }
}
