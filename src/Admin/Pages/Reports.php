<?php

namespace Wox\Admin\Pages;

use Wox\Admin\PageInterface;
use Wox\Traits\Singleton;

defined('ABSPATH') || exit;

class Reports implements PageInterface
{
    use Singleton;

    public function get_title(): string
    {
        return __('Reports', 'whatsapp-ox');
    }

    public function get_menu_title(): string
    {
        return __('Reports', 'whatsapp-ox');
    }

    public function get_slug(): string
    {
        return 'whatsapp-ox-reports';
    }

    public function render(): void
    {
        wp_safe_redirect(admin_url('admin.php?page=whatsapp-ox-settings&tab=reports'));
        exit;
    }
}
