<?php

namespace Wox\Services;

use Wox\Api\ProviderInterface;
use Wox\Api\ProviderFactory;

defined('ABSPATH') || exit;

class NotificationService
{
    private ProviderInterface $provider;

    public function __construct()
    {
        $this->provider = $this->get_provider();
    }

    public function send_order_notification(\WC_Order $order, string $trigger): bool
    {
        $automations = get_option('wox_automations', []);

        if (!isset($automations[$trigger]) || 'yes' !== $automations[$trigger]['enabled']) {
            return false;
        }

        $template_id = $automations[$trigger]['template_id'] ?? '';
        if (empty($template_id)) {
            return false;
        }

        $phone = $order->get_billing_phone();
        if (empty($phone)) {
            return false;
        }

        $template = $this->get_template((int) $template_id);
        if (!$template) {
            return false;
        }

        $variables = $this->build_variables($order);
        $body = $this->render_template($template->body, $variables);

        $result = $this->provider->send_text($phone, $body);

        $this->log_message([
            'order_id' => $order->get_id(),
            'customer_id' => $order->get_customer_id(),
            'phone' => $phone,
            'template_name' => $template->name,
            'message_body' => $body,
            'status' => isset($result['error']) ? 'failed' : 'sent',
            'error_message' => $result['error'] ?? null,
            'provider_message_id' => $result['messages'][0]['id'] ?? null,
        ]);

        return !isset($result['error']);
    }

    private function get_template(int $id): ?object
    {
        global $wpdb;
        return $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM {$wpdb->prefix}wox_templates WHERE id = %d", $id)
        );
    }

    private function build_variables(\WC_Order $order): array
    {
        return [
            '{{first_name}}' => $order->get_billing_first_name(),
            '{{full_name}}' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
            '{{phone}}' => $order->get_billing_phone(),
            '{{order_number}}' => $order->get_order_number(),
            '{{order_date}}' => $order->get_date_created()->format('Y-m-d'),
            '{{order_total}}' => $order->get_total(),
            '{{currency}}' => $order->get_currency(),
            '{{payment_method}}' => $order->get_payment_method_title(),
            '{{shipping_method}}' => $order->get_shipping_method(),
            '{{order_status}}' => wc_get_order_status_name($order->get_status()),
            '{{store_name}}' => get_option('wox_store_name', get_bloginfo('name')),
            '{{support_contact}}' => get_option('wox_support_contact', ''),
        ];
    }

    private function render_template(string $body, array $variables): string
    {
        return str_replace(array_keys($variables), array_values($variables), $body);
    }

    private function log_message(array $data): void
    {
        global $wpdb;
        $wpdb->insert($wpdb->prefix . 'wox_messages', $data);
    }

    private function get_provider(): ProviderInterface
    {
        return ProviderFactory::create();
    }
}
