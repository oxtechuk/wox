<?php

namespace Wox\Admin\Pages;

use Wox\Admin\PageInterface;
use Wox\Traits\Singleton;
use Wox\Services\ConversationService;

defined('ABSPATH') || exit;

class Dashboard implements PageInterface
{
    use Singleton;

    public function get_title(): string
    {
        return __('Dashboard', 'whatsapp-ox');
    }

    public function get_menu_title(): string
    {
        return __('Dashboard', 'whatsapp-ox');
    }

    public function get_slug(): string
    {
        return 'whatsapp-ox';
    }

    public function render(): void
    {
        global $wpdb;

        $period = isset($_GET['period']) ? sanitize_text_field($_GET['period']) : '7days';
        $since = $this->get_since_date($period);

        $conv_service = new ConversationService();

        $total_sent = $this->get_count_since($wpdb, 'sent', $since);
        $total_failed = $this->get_count_since($wpdb, 'failed', $since);
        $total_delivered = $this->get_count_since($wpdb, 'delivered', $since);
        $total_pending = $this->get_count_since($wpdb, 'pending', $since);
        $delivery_rate = $total_sent > 0 ? round(($total_delivered / $total_sent) * 100, 1) : 0;

        $active_customers = $this->get_active_customers($wpdb, $since);
        $recovered_revenue = $this->get_recovered_revenue($wpdb, $since);
        $carts_recovered = $this->get_carts_recovered($wpdb, $since);
        $otps_verified = $this->get_otps_verified($wpdb, $since);

        $unread_count = $conv_service->get_unread_count();

        $chart_messages = $wpdb->get_results($wpdb->prepare(
            "SELECT DATE(created_at) as date,
                SUM(status = 'sent') as sent,
                SUM(status = 'failed') as failed
            FROM {$wpdb->prefix}wox_messages
            WHERE created_at >= %s
            GROUP BY DATE(created_at)
            ORDER BY date ASC",
            $since
        ));

        $chart_revenue = $wpdb->get_results($wpdb->prepare(
            "SELECT DATE(recovered_at) as date, SUM(cart_total) as revenue
            FROM {$wpdb->prefix}wox_carts
            WHERE status = 'recovered' AND recovered_at >= %s
            GROUP BY DATE(recovered_at)
            ORDER BY date ASC",
            $since
        ));

        $chart_ots = $wpdb->get_results($wpdb->prepare(
            "SELECT DATE(created_at) as date,
                SUM(verified_at IS NOT NULL) as verified,
                SUM(verified_at IS NULL AND expires_at < NOW()) as expired,
                COUNT(*) as total
            FROM {$wpdb->prefix}wox_otps
            WHERE created_at >= %s
            GROUP BY DATE(created_at)
            ORDER BY date ASC",
            $since
        ));

        $inbox = $conv_service->get_inbox(5);

        $top_customers = $wpdb->get_results($wpdb->prepare(
            "SELECT phone, template_name, COUNT(*) as msg_count
            FROM {$wpdb->prefix}wox_messages
            WHERE created_at >= %s AND phone != ''
            GROUP BY phone
            ORDER BY msg_count DESC
            LIMIT 10",
            $since
        ));

        $recent_carts = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}wox_carts
            WHERE created_at >= %s
            ORDER BY updated_at DESC
            LIMIT 8",
            $since
        ));

        $recent_messages = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}wox_messages
            ORDER BY created_at DESC
            LIMIT 10",
            $since
        ));

        include WOX_PLUGIN_DIR . 'src/Admin/Views/dashboard.php';
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

    private function get_count_since(\wpdb $wpdb, string $status, string $since): int
    {
        return (int) $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}wox_messages WHERE status = %s AND created_at >= %s",
            $status,
            $since
        ));
    }

    private function get_active_customers(\wpdb $wpdb, string $since): int
    {
        return (int) $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(DISTINCT phone) FROM {$wpdb->prefix}wox_messages WHERE created_at >= %s",
            $since
        ));
    }

    private function get_recovered_revenue(\wpdb $wpdb, string $since): float
    {
        return (float) $wpdb->get_var($wpdb->prepare(
            "SELECT COALESCE(SUM(cart_total), 0) FROM {$wpdb->prefix}wox_carts WHERE status = 'recovered' AND recovered_at >= %s",
            $since
        ));
    }

    private function get_carts_recovered(\wpdb $wpdb, string $since): int
    {
        return (int) $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}wox_carts WHERE status = 'recovered' AND recovered_at >= %s",
            $since
        ));
    }

    private function get_otps_verified(\wpdb $wpdb, string $since): int
    {
        return (int) $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}wox_otps WHERE verified_at IS NOT NULL AND created_at >= %s",
            $since
        ));
    }
}
