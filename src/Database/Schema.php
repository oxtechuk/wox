<?php

namespace Wox\Database;

defined('ABSPATH') || exit;

class Schema
{
    const DB_VERSION = '1.1.0';

    public static function create_tables(): void
    {
        global $wpdb;

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $charset_collate = $wpdb->get_charset_collate();
        $prefix = $wpdb->prefix . 'wox_';

        $tables = [
            "CREATE TABLE {$prefix}messages (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                order_id BIGINT UNSIGNED DEFAULT NULL,
                customer_id BIGINT UNSIGNED DEFAULT NULL,
                phone VARCHAR(20) NOT NULL,
                template_name VARCHAR(100) DEFAULT NULL,
                message_body TEXT NOT NULL,
                status VARCHAR(20) NOT NULL DEFAULT 'pending',
                error_message TEXT DEFAULT NULL,
                provider_message_id VARCHAR(255) DEFAULT NULL,
                sent_at DATETIME DEFAULT NULL,
                delivered_at DATETIME DEFAULT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_order (order_id),
                INDEX idx_status (status),
                INDEX idx_phone (phone)
            ) $charset_collate;",

            "CREATE TABLE {$prefix}templates (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                language VARCHAR(10) NOT NULL DEFAULT 'en',
                header TEXT DEFAULT NULL,
                body TEXT NOT NULL,
                footer TEXT DEFAULT NULL,
                variables TEXT DEFAULT NULL,
                status VARCHAR(20) NOT NULL DEFAULT 'pending',
                provider_template_id VARCHAR(255) DEFAULT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_name (name),
                INDEX idx_status (status)
            ) $charset_collate;",

            "CREATE TABLE {$prefix}otps (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                phone VARCHAR(20) NOT NULL,
                code VARCHAR(10) NOT NULL,
                context VARCHAR(50) NOT NULL DEFAULT 'checkout',
                attempts TINYINT UNSIGNED NOT NULL DEFAULT 0,
                max_attempts TINYINT UNSIGNED NOT NULL DEFAULT 5,
                expires_at DATETIME NOT NULL,
                verified_at DATETIME DEFAULT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_phone (phone),
                INDEX idx_context (context),
                INDEX idx_expires (expires_at)
            ) $charset_collate;",

            "CREATE TABLE {$prefix}carts (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                session_id VARCHAR(100) DEFAULT NULL,
                user_id BIGINT UNSIGNED DEFAULT NULL,
                phone VARCHAR(20) DEFAULT NULL,
                cart_data LONGTEXT NOT NULL,
                cart_total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                currency VARCHAR(3) NOT NULL DEFAULT 'USD',
                status VARCHAR(20) NOT NULL DEFAULT 'abandoned',
                reminder_count TINYINT UNSIGNED NOT NULL DEFAULT 0,
                recovered_at DATETIME DEFAULT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_user (user_id),
                INDEX idx_session (session_id),
                INDEX idx_status (status),
                INDEX idx_phone (phone)
            ) $charset_collate;",

            "CREATE TABLE {$prefix}campaigns (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(200) NOT NULL,
                template_id BIGINT UNSIGNED DEFAULT NULL,
                segment_data TEXT DEFAULT NULL,
                status VARCHAR(20) NOT NULL DEFAULT 'draft',
                sent_count INT UNSIGNED NOT NULL DEFAULT 0,
                total_count INT UNSIGNED NOT NULL DEFAULT 0,
                scheduled_at DATETIME DEFAULT NULL,
                started_at DATETIME DEFAULT NULL,
                completed_at DATETIME DEFAULT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_status (status)
            ) $charset_collate;",

            "CREATE TABLE {$prefix}campaign_log (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                campaign_id BIGINT UNSIGNED NOT NULL,
                customer_id BIGINT UNSIGNED DEFAULT NULL,
                phone VARCHAR(20) NOT NULL,
                status VARCHAR(20) NOT NULL DEFAULT 'pending',
                error_message TEXT DEFAULT NULL,
                sent_at DATETIME DEFAULT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_campaign (campaign_id),
                INDEX idx_status (status)
            ) $charset_collate;",

            "CREATE TABLE {$prefix}conversations (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                phone VARCHAR(20) NOT NULL,
                name VARCHAR(100) DEFAULT NULL,
                message_body TEXT NOT NULL,
                direction VARCHAR(10) NOT NULL DEFAULT 'incoming',
                status VARCHAR(20) NOT NULL DEFAULT 'unread',
                provider_message_id VARCHAR(255) DEFAULT NULL,
                replied_at DATETIME DEFAULT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_phone (phone),
                INDEX idx_status (status),
                INDEX idx_direction (direction),
                INDEX idx_created (created_at)
            ) $charset_collate;",

            "CREATE TABLE {$prefix}queue (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                phone VARCHAR(20) NOT NULL,
                payload TEXT NOT NULL,
                priority TINYINT UNSIGNED NOT NULL DEFAULT 10,
                attempts TINYINT UNSIGNED NOT NULL DEFAULT 0,
                max_attempts TINYINT UNSIGNED NOT NULL DEFAULT 3,
                status VARCHAR(20) NOT NULL DEFAULT 'pending',
                error_message TEXT DEFAULT NULL,
                available_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_status (status),
                INDEX idx_available (available_at)
            ) $charset_collate;",
        ];

        foreach ($tables as $sql) {
            dbDelta($sql);
        }
    }
}
