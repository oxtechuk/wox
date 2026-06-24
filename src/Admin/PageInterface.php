<?php

namespace Wox\Admin;

defined('ABSPATH') || exit;

interface PageInterface
{
    public function get_title(): string;
    public function get_menu_title(): string;
    public function get_slug(): string;
    public function render(): void;
}
