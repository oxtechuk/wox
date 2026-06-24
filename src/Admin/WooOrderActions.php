<?php

namespace Wox\Admin;

use Wox\Api\ProviderFactory;
use Wox\Traits\Singleton;

defined('ABSPATH') || exit;

class WooOrderActions
{
    use Singleton;
    public function add_order_meta_box(): void
    {
        add_meta_box(
            'wox_send_whatsapp',
            __('WhatsApp OX', 'whatsapp-ox'),
            [$this, 'render_meta_box'],
            'shop_order',
            'side',
            'default'
        );
    }

    public function render_meta_box($post): void
    {
        $order = wc_get_order($post->ID);
        if (!$order) {
            echo '<p>' . esc_html__('Order not found.', 'whatsapp-ox') . '</p>';
            return;
        }

        $phone = $order->get_billing_phone();
        if (!$phone) {
            echo '<p>' . esc_html__('No billing phone number.', 'whatsapp-ox') . '</p>';
            return;
        }

        global $wpdb;
        $templates = $wpdb->get_results("SELECT id, name FROM {$wpdb->prefix}wox_templates ORDER BY name ASC");
        $last_logs = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}wox_messages WHERE order_id = %d ORDER BY created_at DESC LIMIT 5",
                $order->get_id()
            )
        );

        include WOX_PLUGIN_DIR . 'src/Admin/Views/order-meta-box.php';
    }

    public function handle_admin_send(): void
    {
        if (!isset($_POST['wox_admin_send']) || !check_admin_referer('wox_order_send')) {
            return;
        }

        $order_id = absint($_POST['order_id'] ?? 0);
        $template_id = absint($_POST['template_id'] ?? 0);
        $custom_message = sanitize_textarea_field($_POST['custom_message'] ?? '');

        $order = wc_get_order($order_id);
        if (!$order) {
            return;
        }

        $phone = $order->get_billing_phone();
        if (!$phone) {
            return;
        }

        $provider = ProviderFactory::create();
        $result = ['error' => 'No message content'];

        if ($template_id) {
            global $wpdb;
            $template = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}wox_templates WHERE id = %d", $template_id));
            if ($template) {
                $variables = $this->build_order_variables($order);
                $body = str_replace(array_keys($variables), array_values($variables), $template->body);
                $result = $provider->send_text($phone, $body);
            }
        } elseif ($custom_message) {
            $result = $provider->send_text($phone, $custom_message);
        }

        global $wpdb;
        $wpdb->insert($wpdb->prefix . 'wox_messages', [
            'order_id' => $order_id,
            'customer_id' => $order->get_customer_id(),
            'phone' => $phone,
            'message_body' => $custom_message ?: ($template->body ?? ''),
            'template_name' => $template->name ?? null,
            'status' => isset($result['error']) ? 'failed' : 'sent',
            'error_message' => $result['error'] ?? null,
            'provider_message_id' => $result['messages'][0]['id'] ?? null,
            'sent_at' => current_time('mysql'),
        ]);

        wp_safe_redirect(add_query_arg('wox_sent', '1', wp_get_referer()));
        exit;
    }

    public function add_order_list_action($actions, $order): array
    {
        $actions['wox_send_whatsapp'] = sprintf(
            '<a href="%s">%s</a>',
            admin_url('post.php?post=' . $order->get_id() . '&action=edit#wox_send_whatsapp'),
            __('Send WhatsApp', 'whatsapp-ox')
        );
        return $actions;
    }

    public function admin_notice(): void
    {
        if (isset($_GET['wox_sent'])) {
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('WhatsApp message sent.', 'whatsapp-ox') . '</p></div>';
        }
    }

    private function build_order_variables(\WC_Order $order): array
    {
        return [
            '{{first_name}}' => $order->get_billing_first_name(),
            '{{full_name}}' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
            '{{phone}}' => $order->get_billing_phone(),
            '{{order_number}}' => $order->get_order_number(),
            '{{order_date}}' => $order->get_date_created()->format('Y-m-d'),
            '{{order_total}}' => (string) $order->get_total(),
            '{{currency}}' => $order->get_currency(),
            '{{payment_method}}' => $order->get_payment_method_title(),
            '{{shipping_method}}' => $order->get_shipping_method(),
            '{{order_status}}' => wc_get_order_status_name($order->get_status()),
            '{{store_name}}' => get_option('wox_store_name', get_bloginfo('name')),
            '{{support_contact}}' => get_option('wox_support_contact', ''),
        ];
    }
}
