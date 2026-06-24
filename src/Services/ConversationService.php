<?php

namespace Wox\Services;

use Wox\Api\ProviderFactory;

defined('ABSPATH') || exit;

class ConversationService
{
    public function get_inbox(int $limit = 10, int $offset = 0): array
    {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}wox_conversations
            WHERE direction = 'incoming'
            ORDER BY FIELD(status, 'unread', 'read', 'replied'), created_at DESC
            LIMIT %d OFFSET %d",
            $limit,
            $offset
        ));
    }

    public function get_unread_count(): int
    {
        global $wpdb;
        return (int) $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->prefix}wox_conversations WHERE direction = 'incoming' AND status = 'unread'"
        );
    }

    public function mark_read(int $id): void
    {
        global $wpdb;
        $wpdb->update(
            $wpdb->prefix . 'wox_conversations',
            ['status' => 'read'],
            ['id' => $id]
        );
    }

    public function mark_replied(int $id): void
    {
        global $wpdb;
        $wpdb->update(
            $wpdb->prefix . 'wox_conversations',
            [
                'status' => 'replied',
                'replied_at' => current_time('mysql'),
            ],
            ['id' => $id]
        );
    }

    public function send_reply(int $conversation_id, string $reply_text): array
    {
        global $wpdb;

        $convo = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}wox_conversations WHERE id = %d",
            $conversation_id
        ));

        if (!$convo) {
            return ['success' => false, 'error' => __('Conversation not found.', 'whatsapp-ox')];
        }

        $provider = ProviderFactory::create();
        $result = $provider->send_text($convo->phone, $reply_text);

        $status = isset($result['error']) ? 'failed' : 'sent';

        $wpdb->insert($wpdb->prefix . 'wox_conversations', [
            'phone' => $convo->phone,
            'name' => $convo->name,
            'message_body' => $reply_text,
            'direction' => 'outgoing',
            'status' => $status,
            'provider_message_id' => $result['messages'][0]['id'] ?? null,
        ]);

        $this->mark_replied($conversation_id);

        if (isset($result['error'])) {
            return ['success' => false, 'error' => $result['error']];
        }

        return ['success' => true];
    }

    public function get_recent_outgoing(string $phone, int $limit = 5): array
    {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}wox_conversations
            WHERE phone = %s AND direction = 'outgoing'
            ORDER BY created_at DESC
            LIMIT %d",
            $phone,
            $limit
        ));
    }
}
