<?php

namespace Wox\Core;

use Wox\Admin\Menu;
use Wox\Admin\WooOrderActions;
use Wox\Frontend\ChatButton;
use Wox\Frontend\ProductInquiry;
use Wox\Database\Migrator;
use Wox\Services\NotificationService;
use Wox\Services\CartCronService;
use Wox\Services\ConversationService;
use Wox\Services\OtpCheckout;
use Wox\Api\Webhook;

defined('ABSPATH') || exit;

class Plugin
{
    private static ?Plugin $instance = null;
    private Loader $loader;

    private function __construct()
    {
        $this->loader = new Loader();
        $this->define_hooks();
        $this->loader->run();
    }

    public static function get_instance(): Plugin
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function define_hooks(): void
    {
        $this->loader->add_action('init', $this, 'load_textdomain');
        $this->loader->add_action('admin_menu', Menu::get_instance(), 'register_menu');
        $this->loader->add_action('admin_enqueue_scripts', $this, 'enqueue_admin_assets');
        $chat_button = ChatButton::get_instance();
        $this->loader->add_action('wp_enqueue_scripts', $chat_button, 'enqueue_assets');
        $this->loader->add_action('wp_footer', $chat_button, 'render_button');

        $this->loader->add_action('init', Migrator::get_instance(), 'migrate');

        $this->loader->add_action('rest_api_init', Webhook::get_instance(), 'register_routes');

        $this->loader->add_action('admin_post_wox_admin_send', WooOrderActions::get_instance(), 'handle_admin_send');

        $this->loader->add_filter('cron_schedules', CartCronService::get_instance(), 'add_cron_interval');

        $this->register_woocommerce_hooks();
    }

    public function load_textdomain(): void
    {
        $lang = get_option('wox_language', 'en');
        if ('ar' === $lang) {
            load_textdomain('whatsapp-ox', WOX_PLUGIN_DIR . 'languages/whatsapp-ox-ar.mo');
        } else {
            load_plugin_textdomain('whatsapp-ox', false, WOX_PLUGIN_DIR . 'languages');
        }
    }

    private function register_woocommerce_hooks(): void
    {
        if (!class_exists('WooCommerce')) {
            return;
        }

        $order_actions = WooOrderActions::get_instance();
        $otp_checkout = new OtpCheckout();
        $cart_cron = CartCronService::get_instance();

        $this->loader->add_action('woocommerce_new_order', $this, 'on_new_order', 10, 2);
        $this->loader->add_action('woocommerce_order_status_changed', $this, 'on_order_status_changed', 10, 4);

        $this->loader->add_action('add_meta_boxes', $order_actions, 'add_order_meta_box');
        $this->loader->add_filter('woocommerce_order_actions', $order_actions, 'add_order_list_action', 10, 2);
        $this->loader->add_action('admin_notices', $order_actions, 'admin_notice');

        $this->loader->add_action('wp_ajax_wox_send_otp', $otp_checkout, 'ajax_send_otp');
        $this->loader->add_action('wp_ajax_nopriv_wox_send_otp', $otp_checkout, 'ajax_send_otp');
        $this->loader->add_action('wp_ajax_wox_verify_otp', $otp_checkout, 'ajax_verify_otp');
        $this->loader->add_action('wp_ajax_nopriv_wox_verify_otp', $otp_checkout, 'ajax_verify_otp');
        $this->loader->add_filter('woocommerce_checkout_fields', $otp_checkout, 'add_checkout_fields');
        $this->loader->add_action('woocommerce_after_checkout_validation', $otp_checkout, 'validate_checkout', 10, 2);

        $this->loader->add_action('wox_cart_reminder', $cart_cron, 'process_reminders');

        $this->loader->add_action('woocommerce_single_product_summary', ProductInquiry::get_instance(), 'render_button', 35);

        $this->loader->add_action('wp_ajax_wox_reply_message', $this, 'ajax_reply_message');
    }

    public function enqueue_admin_assets(string $hook): void
    {
        if ('toplevel_page_whatsapp-ox' !== $hook) {
            return;
        }

        wp_enqueue_style(
            'wox-admin-dashboard',
            WOX_ASSETS_URL . '/css/admin-dashboard.css',
            [],
            '1.1.0'
        );

        wp_enqueue_script(
            'wox-admin-dashboard',
            WOX_ASSETS_URL . '/js/admin-dashboard.js',
            [],
            '1.1.0',
            true
        );

        wp_localize_script('wox-admin-dashboard', 'woxDashboard', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wox_reply_nonce'),
        ]);
    }

    public function ajax_reply_message(): void
    {
        check_ajax_referer('wox_reply_nonce');

        if (!current_user_can('manage_woocommerce')) {
            wp_send_json_error('Unauthorized');
        }

        $conversation_id = absint($_POST['conversation_id'] ?? 0);
        $reply_text = sanitize_textarea_field($_POST['reply_text'] ?? '');

        if (!$conversation_id || empty($reply_text)) {
            wp_send_json_error(__('Invalid request.', 'whatsapp-ox'));
        }

        $service = new ConversationService();
        $result = $service->send_reply($conversation_id, $reply_text);

        if ($result['success']) {
            wp_send_json_success();
        }

        wp_send_json_error($result['error'] ?? __('Failed to send reply.', 'whatsapp-ox'));
    }

    public function on_new_order($order_id, $order): void
    {
        if (is_numeric($order_id) && !$order instanceof \WC_Order) {
            $order = wc_get_order($order_id);
        }
        if (!$order instanceof \WC_Order) {
            return;
        }
        (new NotificationService())->send_order_notification($order, 'new_order');
    }

    public function on_order_status_changed($order_id, $from, $to, $order): void
    {
        if (!$order instanceof \WC_Order) {
            $order = wc_get_order($order_id);
        }
        if (!$order instanceof \WC_Order) {
            return;
        }

        $trigger = $this->map_status_to_trigger($to);
        if ($trigger) {
            (new NotificationService())->send_order_notification($order, $trigger);
        }
    }

    private function map_status_to_trigger(string $status): ?string
    {
        $map = [
            'pending'    => 'pending_payment',
            'processing' => 'processing',
            'completed'  => 'completed',
            'cancelled'  => 'cancelled',
            'refunded'   => 'refunded',
            'failed'     => 'failed',
            'on-hold'    => 'on_hold',
        ];

        return $map[$status] ?? null;
    }
}
