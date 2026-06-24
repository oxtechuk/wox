<?php

defined('ABSPATH') || exit;

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;

$prefix = $wpdb->prefix . 'wox_';

$tables = [
    "{$prefix}messages",
    "{$prefix}templates",
    "{$prefix}otps",
    "{$prefix}carts",
    "{$prefix}campaigns",
    "{$prefix}campaign_log",
    "{$prefix}conversations",
    "{$prefix}queue",
];

foreach ($tables as $table) {
    $wpdb->query("DROP TABLE IF EXISTS {$table}");
}

$options = [
    'wox_version',
    'wox_db_version',
    'wox_phone_number_id',
    'wox_access_token',
    'wox_webhook_verify_token',
    'wox_test_phone',
    'wox_sandbox_mode',
    'wox_automations',
    'wox_otp_enabled',
    'wox_otp_length',
    'wox_otp_expiry',
    'wox_otp_max_attempts',
    'wox_otp_cooldown',
    'wox_otp_checkout',
    'wox_otp_registration',
    'wox_otp_login',
    'wox_cart_enabled',
    'wox_cart_delay',
    'wox_cart_reminder_count',
    'wox_cart_coupon',
    'wox_store_name',
    'wox_support_contact',
    'wox_logs_retention_days',
    'wox_daily_report',
    'wox_chat_enabled',
    'wox_chat_position',
    'wox_language',
    'wox_templates_list',
];

foreach ($options as $option) {
    delete_option($option);
}
