<?php

namespace Wox\Admin;

use Wox\Traits\Singleton;
use Wox\Admin\Pages\Dashboard;
use Wox\Admin\Pages\Templates;
use Wox\Admin\Pages\Campaigns;
use Wox\Admin\Pages\Settings;

defined('ABSPATH') || exit;

class Menu
{
    use Singleton;

    private array $pages = [];

    private function __construct()
    {
        $this->pages = [
            Dashboard::class,
            Templates::class,
            Campaigns::class,
            Settings::class,
        ];
    }

    public function register_menu(): void
    {
        $icon = 'dashicons-phone';
        $icon_path = WOX_PLUGIN_DIR . 'assets/images/logo-admin.svg';
        if (file_exists($icon_path)) {
            $svg = file_get_contents($icon_path);
            if ($svg !== false) {
                $icon = 'data:image/svg+xml;base64,' . base64_encode($svg);
            }
        }

        add_menu_page(
            __('WhatsApp OX', 'whatsapp-ox'),
            __('WhatsApp OX', 'whatsapp-ox'),
            'manage_woocommerce',
            'whatsapp-ox',
            [Dashboard::get_instance(), 'render'],
            $icon,
            55
        );

        foreach ($this->pages as $page_class) {
            $page = $page_class::get_instance();
            add_submenu_page(
                'whatsapp-ox',
                $page->get_title(),
                $page->get_menu_title(),
                'manage_woocommerce',
                $page->get_slug(),
                [$page, 'render']
            );
        }
    }
}
