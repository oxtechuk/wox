<?php

namespace Wox\Admin\Pages;

use Wox\Admin\PageInterface;
use Wox\Traits\Singleton;

defined('ABSPATH') || exit;

class Connection implements PageInterface
{
    use Singleton;

    public function get_title(): string
    {
        return __('API Connection', 'whatsapp-ox');
    }

    public function get_menu_title(): string
    {
        return __('Connection', 'whatsapp-ox');
    }

    public function get_slug(): string
    {
        return 'whatsapp-ox-settings';
    }

    public function render(): void
    {
        wp_safe_redirect(admin_url('admin.php?page=whatsapp-ox-settings&tab=provider'));
        exit;
    }
}
