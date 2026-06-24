<?php

namespace Wox\Admin\Pages;

use Wox\Admin\PageInterface;
use Wox\Traits\Singleton;

defined('ABSPATH') || exit;

class Templates implements PageInterface
{
    use Singleton;

    public function get_title(): string
    {
        return __('Message Templates', 'whatsapp-ox');
    }

    public function get_menu_title(): string
    {
        return __('Templates', 'whatsapp-ox');
    }

    public function get_slug(): string
    {
        return 'whatsapp-ox-templates';
    }

    public function render(): void
    {
        global $wpdb;

        if (isset($_POST['wox_save_template']) && check_admin_referer('wox_template')) {
            $this->save_template();
        }

        if (isset($_GET['action']) && 'delete' === $_GET['action'] && isset($_GET['id'])) {
            $this->delete_template((int) $_GET['id']);
        }

        $templates = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wox_templates ORDER BY created_at DESC");

        include WOX_PLUGIN_DIR . 'src/Admin/Views/templates.php';
    }

    private function save_template(): void
    {
        global $wpdb;

        $data = [
            'name' => sanitize_text_field($_POST['name'] ?? ''),
            'language' => sanitize_text_field($_POST['language'] ?? 'en'),
            'header' => sanitize_textarea_field($_POST['header'] ?? ''),
            'body' => sanitize_textarea_field($_POST['body'] ?? ''),
            'footer' => sanitize_textarea_field($_POST['footer'] ?? ''),
            'variables' => sanitize_text_field($_POST['variables'] ?? ''),
        ];

        if (empty($data['name']) || empty($data['body'])) {
            echo '<div class="notice notice-error"><p>' . esc_html__('Name and body are required.', 'whatsapp-ox') . '</p></div>';
            return;
        }

        if (!empty($_POST['id'])) {
            $wpdb->update($wpdb->prefix . 'wox_templates', $data, ['id' => (int) $_POST['id']]);
        } else {
            $wpdb->insert($wpdb->prefix . 'wox_templates', $data);
        }

        echo '<div class="notice notice-success"><p>' . esc_html__('Template saved.', 'whatsapp-ox') . '</p></div>';
    }

    private function delete_template(int $id): void
    {
        global $wpdb;
        $wpdb->delete($wpdb->prefix . 'wox_templates', ['id' => $id]);
        echo '<div class="notice notice-success"><p>' . esc_html__('Template deleted.', 'whatsapp-ox') . '</p></div>';
    }
}
