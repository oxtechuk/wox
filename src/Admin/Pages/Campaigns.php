<?php

namespace Wox\Admin\Pages;

use Wox\Admin\PageInterface;
use Wox\Traits\Singleton;
use Wox\Services\CampaignService;

defined('ABSPATH') || exit;

class Campaigns implements PageInterface
{
    use Singleton;

    private CampaignService $service;

    private function __construct()
    {
        $this->service = new CampaignService();
    }

    public function get_title(): string
    {
        return __('Campaigns', 'whatsapp-ox');
    }

    public function get_menu_title(): string
    {
        return __('Campaigns', 'whatsapp-ox');
    }

    public function get_slug(): string
    {
        return 'whatsapp-ox-campaigns';
    }

    public function render(): void
    {
        global $wpdb;

        if (isset($_POST['wox_create_campaign']) && check_admin_referer('wox_campaign')) {
            $this->create_campaign();
        }

        if (isset($_POST['wox_send_campaign']) && check_admin_referer('wox_campaign')) {
            $this->send_campaign((int) $_POST['campaign_id']);
        }

        $action = $_GET['action'] ?? 'list';
        $campaign_id = isset($_GET['campaign_id']) ? (int) $_GET['campaign_id'] : 0;

        if ('view' === $action && $campaign_id) {
            $campaign = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}wox_campaigns WHERE id = %d", $campaign_id
            ));
            $logs = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}wox_campaign_log WHERE campaign_id = %d ORDER BY created_at DESC", $campaign_id
            ));
            $templates = $wpdb->get_results("SELECT id, name FROM {$wpdb->prefix}wox_templates ORDER BY name ASC");
            include WOX_PLUGIN_DIR . 'src/Admin/Views/campaign-view.php';
            return;
        }

        $campaigns = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wox_campaigns ORDER BY created_at DESC");
        $customers = $this->service->get_customers();
        $customer_phones = $this->get_all_customer_phones();
        $templates = $wpdb->get_results("SELECT id, name FROM {$wpdb->prefix}wox_templates ORDER BY name ASC");

        include WOX_PLUGIN_DIR . 'src/Admin/Views/campaigns.php';
    }

    private function create_campaign(): void
    {
        global $wpdb;

        $name = sanitize_text_field($_POST['name'] ?? '');
        $message = sanitize_textarea_field($_POST['message'] ?? '');
        $template_id = absint($_POST['template_id'] ?? 0);
        $segment_min = $_POST['segment_min_spent'] ?? '';
        $segment_max = $_POST['segment_max_spent'] ?? '';
        $segment_orders = $_POST['segment_min_orders'] ?? '';

        if (empty($name)) {
            echo '<div class="notice notice-error"><p>' . esc_html__('Campaign name is required.', 'whatsapp-ox') . '</p></div>';
            return;
        }

        $segment_data = wp_json_encode([
            'min_spent' => $segment_min,
            'max_spent' => $segment_max,
            'min_orders' => $segment_orders,
        ]);

        $customers = $this->service->get_customers([
            'min_spent' => $segment_min,
            'max_spent' => $segment_max,
            'min_orders' => $segment_orders,
        ]);

        $wpdb->insert($wpdb->prefix . 'wox_campaigns', [
            'name' => $name,
            'template_id' => $template_id ?: null,
            'segment_data' => $segment_data,
            'status' => 'draft',
            'total_count' => count($customers),
        ]);

        $campaign_id = $wpdb->insert_id;

        foreach ($customers as $c) {
            $wpdb->insert($wpdb->prefix . 'wox_campaign_log', [
                'campaign_id' => $campaign_id,
                'customer_id' => $c['id'],
                'phone' => $c['phone'],
                'status' => 'pending',
            ]);
        }

        echo '<div class="notice notice-success"><p>' . sprintf(
            esc_html__('Campaign "%s" created with %d customers.', 'whatsapp-ox'),
            $name,
            count($customers)
        ) . '</p></div>';
    }

    private function send_campaign(int $campaign_id): void
    {
        $result = $this->service->send_broadcast($campaign_id);

        if ($result['sent'] > 0) {
            echo '<div class="notice notice-success"><p>' . sprintf(
                esc_html__('Campaign sent: %d delivered, %d failed.', 'whatsapp-ox'),
                $result['sent'],
                $result['failed']
            ) . '</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>' . esc_html__('Campaign failed to send.', 'whatsapp-ox') . '</p></div>';
        }
    }

    private function get_all_customer_phones(): array
    {
        global $wpdb;

        $phones = $wpdb->get_col("SELECT DISTINCT meta_value FROM {$wpdb->usermeta} WHERE meta_key = 'billing_phone' AND meta_value != ''");

        $order_phones = $wpdb->get_col("SELECT DISTINCT billing_phone FROM {$wpdb->prefix}wc_orders WHERE billing_phone != ''");

        $all = array_unique(array_merge($phones, $order_phones));
        sort($all);

        return $all;
    }
}
