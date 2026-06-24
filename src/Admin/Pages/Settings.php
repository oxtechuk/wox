<?php

namespace Wox\Admin\Pages;

use Wox\Admin\PageInterface;
use Wox\Traits\Singleton;

defined('ABSPATH') || exit;

class Settings implements PageInterface
{
    use Singleton;

    public function get_title(): string
    {
        return __('Settings', 'whatsapp-ox');
    }

    public function get_menu_title(): string
    {
        return __('Settings', 'whatsapp-ox');
    }

    public function get_slug(): string
    {
        return 'whatsapp-ox-settings';
    }

    public function render(): void
    {
        $tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'general';

        if (isset($_POST['wox_save_settings']) && check_admin_referer('wox_settings')) {
            $this->save($tab);
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Settings saved.', 'whatsapp-ox') . '</p></div>';
        }

        $data = $this->get_data($tab);

        include WOX_PLUGIN_DIR . 'src/Admin/Views/settings.php';
    }

    private function get_data(string $tab): array
    {
        global $wpdb;

        $base = [
            'store_name' => get_option('wox_store_name', get_bloginfo('name')),
            'support_contact' => get_option('wox_support_contact', ''),
            'logs_retention_days' => get_option('wox_logs_retention_days', 90),
            'daily_report' => get_option('wox_daily_report', 'no'),
            'provider' => get_option('wox_provider', 'whatsapp_cloud'),
            'twilio_account_sid' => get_option('wox_twilio_account_sid', ''),
            'twilio_auth_token' => get_option('wox_twilio_auth_token', ''),
            'twilio_from_number' => get_option('wox_twilio_from_number', ''),
            'phone_number_id' => get_option('wox_phone_number_id', ''),
            'access_token' => get_option('wox_access_token', ''),
            'webhook_token' => get_option('wox_webhook_verify_token', ''),
            'test_phone' => get_option('wox_test_phone', ''),
            'sandbox' => get_option('wox_sandbox_mode', 'no'),
            'widget_enabled' => get_option('wox_chat_enabled', 'yes'),
            'widget_position' => get_option('wox_chat_position', 'right'),
            'widget_icon_style' => get_option('wox_chat_icon_style', 'round'),
            'widget_bottom' => get_option('wox_chat_bottom', '20'),
            'widget_side_offset' => get_option('wox_chat_side_offset', '20'),
            'widget_size' => get_option('wox_chat_size', '60'),
            'widget_greeting' => get_option('wox_chat_greeting', __('Hello, I have a question.', 'whatsapp-ox')),
            'product_inquiry_enabled' => get_option('wox_chat_product_inquiry', 'yes'),
            'product_button_text' => get_option('wox_chat_product_button_text', __('Ask via WhatsApp', 'whatsapp-ox')),
            'product_include_price' => get_option('wox_product_include_price', 'yes'),
            'product_include_sku' => get_option('wox_product_include_sku', 'no'),
            'product_include_url' => get_option('wox_product_include_url', 'yes'),
            'language' => get_option('wox_language', 'en'),
        ];

        if ('automations' === $tab) {
            $base['triggers'] = [
                'new_order' => __('New Order', 'whatsapp-ox'),
                'pending_payment' => __('Pending Payment', 'whatsapp-ox'),
                'processing' => __('Processing', 'whatsapp-ox'),
                'completed' => __('Completed', 'whatsapp-ox'),
                'cancelled' => __('Cancelled', 'whatsapp-ox'),
                'refunded' => __('Refunded', 'whatsapp-ox'),
                'failed' => __('Failed', 'whatsapp-ox'),
                'on_hold' => __('On Hold', 'whatsapp-ox'),
            ];
            $base['automations'] = get_option('wox_automations', []);
            $base['templates'] = $wpdb->get_results("SELECT id, name FROM {$wpdb->prefix}wox_templates ORDER BY name ASC");
        }

        if ('carts' === $tab) {
            $base['cart_enabled'] = get_option('wox_cart_enabled', 'no');
            $base['cart_delay'] = get_option('wox_cart_delay', 60);
            $base['cart_reminder_count'] = get_option('wox_cart_reminder_count', 3);
            $base['cart_coupon'] = get_option('wox_cart_coupon', '');
            $base['carts'] = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT * FROM {$wpdb->prefix}wox_carts WHERE status = %s ORDER BY updated_at DESC LIMIT 50",
                    'abandoned'
                )
            );
        }

        if ('otp' === $tab) {
            $base['otp_enabled'] = get_option('wox_otp_enabled', 'no');
            $base['otp_length'] = get_option('wox_otp_length', 6);
            $base['otp_expiry'] = get_option('wox_otp_expiry', 300);
            $base['otp_max_attempts'] = get_option('wox_otp_max_attempts', 5);
            $base['otp_cooldown'] = get_option('wox_otp_cooldown', 60);
            $base['otp_checkout'] = get_option('wox_otp_checkout', 'no');
            $base['otp_registration'] = get_option('wox_otp_registration', 'no');
            $base['otp_login'] = get_option('wox_otp_login', 'no');
        }

        if ('logs' === $tab) {
            $base['log_order_id'] = isset($_GET['order_id']) ? absint($_GET['order_id']) : 0;
            $base['log_status'] = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';
            $base['log_search'] = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

            $where = ['1=1'];
            $params = [];

            if ($base['log_order_id']) {
                $where[] = 'order_id = %d';
                $params[] = $base['log_order_id'];
            }
            if ($base['log_status']) {
                $where[] = 'status = %s';
                $params[] = $base['log_status'];
            }
            if ($base['log_search']) {
                $where[] = '(phone LIKE %s OR message_body LIKE %s)';
                $params[] = '%' . $wpdb->esc_like($base['log_search']) . '%';
                $params[] = '%' . $wpdb->esc_like($base['log_search']) . '%';
            }

            $sql = "SELECT * FROM {$wpdb->prefix}wox_messages WHERE " . implode(' AND ', $where) . ' ORDER BY created_at DESC LIMIT 100';
            $base['logs'] = !empty($params) ? $wpdb->get_results($wpdb->prepare($sql, $params)) : $wpdb->get_results($sql);
        }

        if ('reports' === $tab) {
            $period = isset($_GET['period']) ? sanitize_text_field($_GET['period']) : '7days';
            $since = $this->get_since_date($period);
            $base['report_period'] = $period;

            $base['report_sent'] = (int) $wpdb->get_var(
                $wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}wox_messages WHERE status = %s AND created_at >= %s", 'sent', $since)
            );
            $base['report_failed'] = (int) $wpdb->get_var(
                $wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}wox_messages WHERE status = %s AND created_at >= %s", 'failed', $since)
            );
            $base['report_delivered'] = (int) $wpdb->get_var(
                $wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}wox_messages WHERE status = %s AND created_at >= %s", 'delivered', $since)
            );
            $base['report_daily'] = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT DATE(created_at) as date, status, COUNT(*) as count
                    FROM {$wpdb->prefix}wox_messages
                    WHERE created_at >= %s
                    GROUP BY DATE(created_at), status
                    ORDER BY date ASC",
                    $since
                )
            );
            $base['report_recovered'] = (int) $wpdb->get_var(
                $wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}wox_carts WHERE status = %s AND recovered_at >= %s", 'recovered', $since)
            );
            $base['report_revenue'] = (float) $wpdb->get_var(
                $wpdb->prepare("SELECT COALESCE(SUM(cart_total), 0) FROM {$wpdb->prefix}wox_carts WHERE status = %s AND recovered_at >= %s", 'recovered', $since)
            );
        }

        return $base;
    }

    private function get_since_date(string $period): string
    {
        $map = [
            '7days' => '-7 days',
            '30days' => '-30 days',
            '90days' => '-90 days',
            'all' => '-5 years',
        ];
        return gmdate('Y-m-d H:i:s', strtotime($map[$period] ?? '-7 days'));
    }

    private function save(string $tab): void
    {
        switch ($tab) {
            case 'general':
                update_option('wox_store_name', sanitize_text_field($_POST['wox_store_name'] ?? ''));
                update_option('wox_support_contact', sanitize_text_field($_POST['wox_support_contact'] ?? ''));
                update_option('wox_logs_retention_days', absint($_POST['wox_logs_retention_days'] ?? 90));
                update_option('wox_daily_report', isset($_POST['wox_daily_report']) ? 'yes' : 'no');
                update_option('wox_language', sanitize_text_field($_POST['wox_language'] ?? 'en'));
                break;

            case 'provider':
                update_option('wox_provider', sanitize_text_field($_POST['wox_provider'] ?? 'whatsapp_cloud'));
                update_option('wox_twilio_account_sid', sanitize_text_field($_POST['wox_twilio_account_sid'] ?? ''));
                update_option('wox_twilio_auth_token', sanitize_text_field($_POST['wox_twilio_auth_token'] ?? ''));
                update_option('wox_twilio_from_number', sanitize_text_field($_POST['wox_twilio_from_number'] ?? ''));
                update_option('wox_phone_number_id', sanitize_text_field($_POST['wox_phone_number_id'] ?? ''));
                update_option('wox_access_token', sanitize_text_field($_POST['wox_access_token'] ?? ''));
                update_option('wox_webhook_verify_token', sanitize_text_field($_POST['wox_webhook_verify_token'] ?? ''));
                update_option('wox_test_phone', sanitize_text_field($_POST['wox_test_phone'] ?? ''));
                update_option('wox_sandbox_mode', isset($_POST['wox_sandbox_mode']) ? 'yes' : 'no');
                break;

            case 'widget':
                update_option('wox_chat_enabled', isset($_POST['wox_chat_enabled']) ? 'yes' : 'no');
                update_option('wox_chat_position', sanitize_text_field($_POST['wox_chat_position'] ?? 'right'));
                update_option('wox_chat_icon_style', sanitize_text_field($_POST['wox_chat_icon_style'] ?? 'round'));
                update_option('wox_chat_bottom', absint($_POST['wox_chat_bottom'] ?? 20));
                update_option('wox_chat_side_offset', absint($_POST['wox_chat_side_offset'] ?? 20));
                update_option('wox_chat_size', absint($_POST['wox_chat_size'] ?? 60));
                update_option('wox_chat_greeting', sanitize_text_field($_POST['wox_chat_greeting'] ?? ''));
                break;

            case 'product':
                update_option('wox_chat_product_inquiry', isset($_POST['wox_chat_product_inquiry']) ? 'yes' : 'no');
                update_option('wox_chat_product_button_text', sanitize_text_field($_POST['wox_chat_product_button_text'] ?? ''));
                update_option('wox_product_include_price', isset($_POST['wox_product_include_price']) ? 'yes' : 'no');
                update_option('wox_product_include_sku', isset($_POST['wox_product_include_sku']) ? 'yes' : 'no');
                update_option('wox_product_include_url', isset($_POST['wox_product_include_url']) ? 'yes' : 'no');
                break;

            case 'automations':
                $triggers = [
                    'new_order', 'pending_payment', 'processing', 'completed',
                    'cancelled', 'refunded', 'failed', 'on_hold',
                ];
                $automations = [];
                foreach ($triggers as $key) {
                    $automations[$key] = [
                        'enabled' => isset($_POST["automation_{$key}_enabled"]) ? 'yes' : 'no',
                        'template_id' => sanitize_text_field($_POST["automation_{$key}_template"] ?? ''),
                    ];
                }
                update_option('wox_automations', $automations);
                break;

            case 'carts':
                update_option('wox_cart_enabled', isset($_POST['wox_cart_enabled']) ? 'yes' : 'no');
                update_option('wox_cart_delay', absint($_POST['wox_cart_delay'] ?? 60));
                update_option('wox_cart_reminder_count', absint($_POST['wox_cart_reminder_count'] ?? 3));
                update_option('wox_cart_coupon', sanitize_text_field($_POST['wox_cart_coupon'] ?? ''));
                break;

            case 'otp':
                update_option('wox_otp_enabled', isset($_POST['wox_otp_enabled']) ? 'yes' : 'no');
                update_option('wox_otp_length', absint($_POST['wox_otp_length'] ?? 6));
                update_option('wox_otp_expiry', absint($_POST['wox_otp_expiry'] ?? 300));
                update_option('wox_otp_max_attempts', absint($_POST['wox_otp_max_attempts'] ?? 5));
                update_option('wox_otp_cooldown', absint($_POST['wox_otp_cooldown'] ?? 60));
                update_option('wox_otp_checkout', isset($_POST['wox_otp_checkout']) ? 'yes' : 'no');
                update_option('wox_otp_registration', isset($_POST['wox_otp_registration']) ? 'yes' : 'no');
                update_option('wox_otp_login', isset($_POST['wox_otp_login']) ? 'yes' : 'no');
                break;

            case 'logs':
            case 'reports':
                break;
        }
    }
}
